<?php

namespace Luisprmat\LaravelLangInstaller\Tests;

use Luisprmat\LaravelLangInstaller\LangServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LangServiceProvider::class
        ];
    }

    /**
     * @param array $require
     * @param array $requireDev
     * @return string
     */
    protected function buildComposerWithDependencies(array $require = [], array $requireDev = []): string
    {
        $composerString = '{"name":"luisprmat/package","require":{';
        $composerString .= implode(',', $require);
        $composerString .= '},"require-dev": {';
        $composerString .= implode(',', $requireDev);
        $composerString .= '}}';

        return json_encode(
            json_decode($composerString),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
