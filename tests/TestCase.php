<?php

namespace Luisprmat\Spanish\Tests;

use Luisprmat\Spanish\Facades\SpanishHello;
use Luisprmat\Spanish\SpanishServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SpanishServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Hello' => SpanishHello::class
        ];
    }
}