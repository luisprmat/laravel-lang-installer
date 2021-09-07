<?php

namespace Luisprmat\Spanish\Tests\Unit;

use Luisprmat\Spanish\Tests\TestCase;

class PublishLanguageFilesTest extends TestCase
{
    /** @test */
    function command_publishes_language_files()
    {
        $this->artisan('lang:add-spanish')
            ->expectsOutput('Language spanish installed successful');

        $this->assertTrue(true);
    }
}
