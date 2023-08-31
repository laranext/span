<?php

namespace Laranext\Span;

class Span
{
    /**
     * Get the current Span version.
     */
    public static function version()
    {
        return '0.1.7';
    }

    /**
     * Current Prefix.
     */
    public static $prefix = '';

    /**
     * Current Package Key.
     */
    public static $key = '';

    /**
     * Register all span service providers.
     *
     * @return void
     */
    public static function registerAllProviders()
    {
        app()->register(SpanServiceProvider::class);

        foreach (config('span.providers') as $key => $provider) {
            app()->register($provider);
        }

        foreach (config('span.prefix_providers') as $key => $provider) {
            app()->register($provider);
        }
    }

    /**
     * Set and get current prefix on runtime.
     *
     * @return string
     */
    public static function prefix($prefix = null)
    {
        return $prefix ? static::$prefix = $prefix : static::$prefix;
    }

    /**
     * Set and get current key on runtime.
     *
     * @return string
     */
    public static function key($key = null)
    {
        return $key ? static::$key = $key : static::$key;
    }
}
