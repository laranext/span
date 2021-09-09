<?php

namespace Laranext\Span\Http\Middleware;

use Laranext\Span\Span;

class ServePackage
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        if ($provider = $this->isSpanRequest($request)) {
            app()->register($provider);
        }

        return $next($request);
    }

    /**
     * Determine if the given request is intended for Span.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function isSpanRequest($request)
    {
        // i have to refactor this whole block.
        // waiting for better idea.

        if (in_array($request->segment(1), config('span.excluded_routes'))) {
            return false;
        }

        $hasPrefix = $request->segment(1) == config('span.prefix');
        $key = $hasPrefix ? $request->segment(2) : $request->segment(1);
        $providers = $hasPrefix
            ? config('span.prefix_providers')
            : config('span.providers');

        if (array_key_exists($key, $providers)) {
            Span::key($key);
            Span::prefix($hasPrefix ? config('span.prefix') . '/' . $key : $key);

            return $providers[$key];
        } elseif (array_key_exists('', $providers)) {
            Span::prefix($hasPrefix ? config('span.prefix') : '');

            return $providers[''];
        }
    }
}
