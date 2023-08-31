<?php

namespace Laranext\Span;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\MigrationCreator;

class SpanServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\ControllerNewCommand::class,
                Console\MigrationCommand::class,
                Console\ModelCommand::class,
                Console\PackageCommand::class,
                // Console\SeedCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->app->when(MigrationCreator::class)
            ->needs('$customStubPath')
            ->give(function () {});

        $this->publishes([
            __DIR__.'/../config/span.php' => config_path('span.php'),
        ], 'span-config');
    }
}
