<?php

namespace Laranext\Span\Http\Middleware;

use Laranext\Span\Span;

class ServePackage
{
    /**
     * The request segment 1.
     *
     * @var string
     */
    protected $segmentOne;

    /**
     * The request segment 2.
     *
     * @var string
     */
    protected $segmentTwo;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        $this->setSegments($request);

        if ($provider = $this->isSpanRequest($request)) {
            app()->register($provider);
        }

        return $next($request);
    }

    /**
     * Set segments for livewire and default request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function setSegments($request)
    {
        // i have to refactor this whole block.
        // waiting for better idea.

        if ($request->hasHeader('X-Livewire')) {
            $path = explode('/', $request->fingerprint['path']);
            $this->segmentOne = $path[0] ?? null;
            $this->segmentTwo = $path[1] ?? null;
            return;
        }

        $this->segmentOne = $request->segment(1);
        $this->segmentTwo = $request->segment(2);
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

        if (in_array($this->segmentOne, config('span.excluded_routes'))) {
            return false;
        }

        $hasPrefix = $this->segmentOne == config('span.prefix');
        $key = $hasPrefix ? $this->segmentTwo : $this->segmentOne;
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
