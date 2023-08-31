<?php

namespace Laranext\Span\Console;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'span:controller')]
class ControllerNewCommand extends ControllerMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'span:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class for span package';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        parent::handle();

        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        $replace["namespace App\\"] = 'namespace '.$this->getMyNamespace();
        $replace["use App\Http\Controllers\Controller;"] = '';
        $replace["extends Controller"] = '';

        if (
            in_array(Str::afterLast($this->getStub(), '/'), [
                'controller.plain.stub', 'controller.invokable.stub'
            ])
        ) {
            $replace["use Illuminate\Http\Request;\n\n"] = '';
        }

        $fileContent = file_get_contents($path);
        $fileName = $this->argument('name');

        $updatedContent = str_replace(array_keys($replace), array_values($replace), $fileContent);

        file_put_contents($path, $updatedContent);

        $destinationFilePath = $this->laravel->basePath('packages/' . $this->argument('package') . '/src/Http/Controllers/' . $fileName . '.php');

        $this->makeDirectory($destinationFilePath);

        rename($path, $destinationFilePath);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function getMyNamespace()
    {
        return $this->option('namespace')
                    ? str_replace('/', '\\', $this->option('namespace')) . '\\'
                    : Str::studly($this->argument('package')) . '\\';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller'],
            ['package', InputArgument::REQUIRED, 'The span package dir name'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['namespace', 'N', InputOption::VALUE_REQUIRED, 'Provide the root namespace if it\'s different from your package directory name'],
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller'],
            ['type', null, InputOption::VALUE_REQUIRED, 'Manually specify the controller stub file to use'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
            ['invokable', 'i', InputOption::VALUE_NONE, 'Generate a single method, invokable controller class'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model'],
            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class'],
            ['requests', 'R', InputOption::VALUE_NONE, 'Generate FormRequest classes for store and update'],
            ['singleton', 's', InputOption::VALUE_NONE, 'Generate a singleton resource controller class'],
            ['creatable', null, InputOption::VALUE_NONE, 'Indicate that a singleton resource should be creatable'],
        ];
    }
}
