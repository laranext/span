<?php

namespace Laranext\Span;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\MigrationCreator;

class SpanServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Console\InstallCommand::class,
            Console\ControllerCommand::class,
            Console\MigrationCommand::class,
            Console\ModelCommand::class,
            Console\PackageCommand::class,
            // Console\SeedCommand::class,
        ]);
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(MigrationCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {});

        $this->publishes([
            __DIR__.'/../config/span.php' => config_path('span.php'),
        ], 'span-config');
    }
}
