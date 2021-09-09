<?php

namespace Laranext\Span\Console;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

trait CommandHelpers
{
    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($path)
    {
        return (new Filesystem)->exists($path);
    }

    /**
     * Replace the given string in the given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replace($search, $replace, $path)
    {
        if ((new Filesystem)->exists($path)) {
            file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
        }
    }

    /**
     * Get the root namespace.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->option('namespace') ? str_replace('/', '\\', $this->option('namespace')) : Str::studly($this->argument('package'));
    }

    /**
     * Get the root namespace for composer.
     *
     * @return string
     */
    protected function rootNamespaceComposer()
    {
        return $this->option('namespace') ? str_replace('/', '\\\\', $this->option('namespace')) : Str::studly($this->argument('package'));
    }

    /**
     * Build the directory if not exists.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDir($path)
    {
        if (! is_dir($directory = $this->packagePath($path))) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Rename the stub with PHP file extensions.
     *
     * @return void
     */
    protected function renameStub($stub)
    {
        (new Filesystem)->move($stub, str_replace('.stub', '.php', $stub));
    }

    /**
     * Rename the stubs with PHP file extensions.
     *
     * @return void
     */
    protected function renameStubs()
    {
        foreach ($this->stubsToRename() as $stub) {
            (new Filesystem)->move($stub, str_replace('.stub', '.php', $stub));
        }
    }

    /**
     * Get the camel case.
     *
     * @return string
     */
    protected function camel()
    {
        return Str::camel($this->argument('name'));
    }

    /**
     * Get the kebab case.
     *
     * @return string
     */
    protected function kebab()
    {
        return Str::kebab($this->argument('name'));
    }

    /**
     * Get the plural kebab case.
     *
     * @return string
     */
    protected function kebabPlural()
    {
        return Str::kebab(Str::plural( $this->argument('name') ));
    }

    /**
     * Get the plural name.
     *
     * @return string
     */
    protected function plural()
    {
        return Str::plural( $this->argument('name') );
    }

    /**
     * Get the title case with space from package name.
     *
     * @return string
     */
    protected function title()
    {
        return Str::of($this->argument('package'))->replace('-', ' ')->title();
    }

    /**
     * Get the pascle case package name.
     *
     * @return string
     */
    protected function pascleName()
    {
        return Str::studly( $this->argument('package') );
    }

    /**
     * Get the path to the package.
     *
     * @return string
     */
    protected function packagePath($path = null)
    {
        return base_path('packages/' . $this->argument('package') . '/' . $path);
    }
}
