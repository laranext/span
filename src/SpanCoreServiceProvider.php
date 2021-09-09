<?php

namespace Laranext\Span;

use Illuminate\Support\ServiceProvider;
use Laranext\Span\Http\Middleware\ServePackage;
use Illuminate\Contracts\Http\Kernel as HttpKernel;

class SpanCoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/span.php', 'span');
        }

        if ($this->app->runningInConsole()) {
            Span::registerAllProviders();
        }

        $this->app->make(HttpKernel::class)
                    ->pushMiddleware(ServePackage::class);
    }
}
