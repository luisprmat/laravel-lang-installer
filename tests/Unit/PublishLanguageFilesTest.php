<?php

namespace Luisprmat\LaravelLangInstaller\Tests\Unit;

use Luisprmat\LaravelLangInstaller\Tests\TestCase;

class PublishLanguageFilesTest extends TestCase
{
    /** @test */
    function command_publishes_language_files()
    {
        $this->artisan('lang:add es')
            ->expectsOutput("Language [es] installed successfully as default.");

        $this->artisan('lang:add fr')
            ->expectsOutput("Language [fr] installed successfully as default.");

        $this->artisan('lang:add fr --no-def')
            ->expectsOutput("Language [fr] installed successfully, but it isn't the default language.");
    }
}
