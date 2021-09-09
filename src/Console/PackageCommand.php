<?php

namespace Laranext\Span\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class PackageCommand extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'span:package {package : The span package name} {--namespace= : The root namespace of the package if it is different from package name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new span package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ( is_dir($this->packagePath()) ) {
            $this->error('Package already exists!');

            return false;
        }

        (new Filesystem)->copyDirectory(
            __DIR__ . '/package-stub',
            $this->packagePath()
        );

        $this->updateFiles();

        // Register the package...
        if ($this->confirm('Would you like to update your composer package?', true)) {
            $this->addPackageRepositoryToRootComposer();
            $this->addPackageToRootComposer();

            $this->composerUpdate();
        }

        $this->info('Span package generated successfully.');
    }

    /**
     * Update Stubs.
     *
     * @return void
     */
    protected function updateFiles()
    {
        // Package Name - name (my-admin-blog)
        // Package Name - title (My Admin Blog)
        // Package Name - pascleName (MyAdminBlog)
        // Root Namespace - rootNamespace (Laranext\Span\Admin\Blog)
        // Root Namespace - rootNamespaceComposer (Laranext\\Span\\Admin\\Blog)

        // composer.json replacements...
        $this->replace('{{ name }}', $this->argument('package'), $this->packagePath('composer.json'));
        $this->replace('{{ rootNamespaceComposer }}', $this->rootNamespaceComposer(), $this->packagePath('composer.json'));

        // views/home.blade.php replacements...
        $this->replace('{{ title }}', $this->title(), $this->packagePath('resources/views/home.blade.php'));

        // rename service provider and replacements...
        $this->replace('{{ rootNamespace }}', $this->rootNamespace(), $this->packagePath('src/ServiceProvider.stub'));
        $this->replace('{{ pascleName }}', $this->pascleName(), $this->packagePath('src/ServiceProvider.stub'));
        (new Filesystem)->move(
            $this->packagePath('src/ServiceProvider.stub'),
            $this->packagePath( 'src/' . $this->pascleName() . 'ServiceProvider.php' )
        );
    }

    /**
     * Add a path repository for the package to the application's composer.json file.
     *
     * @return void
     */
    protected function addPackageRepositoryToRootComposer()
    {
        $url = './packages/' . $this->argument('package');
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $composer['repositories'] ?? $composer['repositories'] = [];

        if (! collect($composer['repositories'])->firstWhere('url', $url)) {
            $composer['repositories'][] = [
                'type' => 'path',
                'url' => $url,
            ];

            file_put_contents(
                base_path('composer.json'),
                json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }
    }

    /**
     * Add a package entry for the package to the application's composer.json file.
     *
     * @return void
     */
    protected function addPackageToRootComposer()
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        $composer['require']['span/'.$this->argument('package')] = '*';

        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Update the project's composer dependencies.
     *
     * @return void
     */
    protected function composerUpdate()
    {
        $this->executeCommand(['composer', 'update']);
    }

    /**
     * Run the given command as a process.
     *
     * @param  string  $command
     * @param  string  $path
     * @return void
     */
    protected function executeCommand($command)
    {
        $process = (new Process($command))->setTimeout(null);

        // $process->setTty(Process::isTtySupported());

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
