<?php

namespace Luisprmat\LaravelLangInstaller\Tests;

use Luisprmat\LaravelLangInstaller\LangServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LangServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            //
        ];
    }
}
