<?php

namespace Laranext\Span\Tests;

use Mockery;
use Orchestra\Testbench\TestCase;
use Laranext\Span\SpanCoreServiceProvider;

abstract class OrchestraTestCase extends TestCase
{
    public function tearDown(): void
    {
        // Mockery::close();
    }

    protected function getPackageProviders($app)
    {
        return [SpanCoreServiceProvider::class];
    }
}
