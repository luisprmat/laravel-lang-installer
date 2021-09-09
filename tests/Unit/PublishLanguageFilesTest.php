<?php

namespace Luisprmat\LaravelLangInstaller\Tests\Unit;

use Illuminate\Filesystem\Filesystem;
use Luisprmat\LaravelLangInstaller\Tests\TestCase;

class PublishLanguageFilesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();


    }

    /** @test */
    function command_publishes_language_files()
    {
        $this->artisan('lang:add es')
            ->expectsOutput("Language [es] installed successfully as default.");

        $this->artisan('lang:add fr')
            ->expectsOutput("Language [fr] installed successfully as default.");

        $this->artisan('lang:add fr --no-default')
            ->expectsOutput("Language [fr] installed successfully, but it isn't the default language.");
    }

    /** @test */
    function validate_if_locale_directory_exists()
    {
        $locales = $this->getLocales();

        $this->artisan('lang:add no-valid')
            ->expectsOutput("Language [no-valid] is not supported!");

//        dd($locales);
    }

    /**
     * @return array
     */
    protected function getLocales(): array
    {
        $filesystem = new Filesystem;

        $path = __DIR__ . "/../../vendor/laravel-lang/lang/locales";
        $directories = $filesystem->directories($path);

        $locales = [];

        foreach ($directories as $directory) {
            $locales[] = $filesystem->name($directory);
        }
        return $locales;
    }
}
