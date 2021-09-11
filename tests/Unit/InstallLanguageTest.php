<?php

namespace Luisprmat\LaravelLangInstaller\Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Luisprmat\LaravelLangInstaller\Tests\TestCase;

class InstallLanguageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->setBasePath(__DIR__ . '/../fixtures');

        File::ensureDirectoryExists(config_path());
        copy(__DIR__ . '/../stubs/config/app.php', config_path('app.php'));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        File::deleteDirectory(config_path());
        File::deleteDirectory(resource_path());
    }

    /** @test */
    function installs_supported_language()
    {
        $this->assertFalse(File::exists(resource_path('lang/xx_GB')));
        $this->assertFalse(File::exists(resource_path('lang/xx_GB/auth.php')));
        $this->assertFalse(File::exists(resource_path('lang/xx_GB/passwords.php')));
        $this->assertFalse(File::exists(resource_path('lang/xx_GB/pagination.php')));
        $this->assertFalse(File::exists(resource_path('lang/xx_GB/validation.php')));
        $this->assertFalse(File::exists(resource_path('lang/xx_GB.json')));

        $this->artisan('lang:add xx_GB')
            ->expectsOutput("Language [xx_GB] installed successfully as default.")
            ->doesntExpectOutput("Language [es] installed successfully as default.");

        $this->assertTrue(File::exists(resource_path('lang/xx_GB')));
        $this->assertTrue(File::exists(resource_path('lang/xx_GB/auth.php')));
        $this->assertTrue(File::exists(resource_path('lang/xx_GB/passwords.php')));
        $this->assertTrue(File::exists(resource_path('lang/xx_GB/pagination.php')));
        $this->assertTrue(File::exists(resource_path('lang/xx_GB/validation.php')));
        $this->assertTrue(File::exists(resource_path('lang/xx_GB.json')));
    }

    /** @test */
    function installs_supported_language_with_validation_inline()
    {
        $this->artisan('lang:add es --inline')
            ->expectsOutput("Language [es] installed successfully as default.");

        //TODO: Test if generated file match
    }

    /** @test */
    function installs_spanish_language_by_default()
    {
        $this->artisan('lang:add')
            ->expectsOutput("Language [es] installed successfully as default.");
    }

    /** @test */
    function doesnt_install_language_if_not_supported()
    {
        $this->assertFalse(File::exists(resource_path('lang/no_valid')));
        $this->artisan('lang:add no-valid')
            ->expectsOutput("Language [no-valid] is not supported!");

        $this->assertFalse(File::exists(resource_path('lang/no_valid')));

        $this->artisan('lang:add es')
            ->doesntExpectOutput("Language [es] is not supported!");
    }

    /** @test */
    function modifies_config_app_locale_by_default()
    {
        $this->assertTrue(File::exists(config_path('app.php')));
        $this->assertTrue(Str::contains(File::get(config_path('app.php')), "'locale' => 'en'"));

        $this->artisan('lang:add xx_GB');

        $this->assertTrue(File::exists(config_path('app.php')));
        $this->assertTrue(Str::contains(File::get(config_path('app.php')), "'locale' => 'xx_GB'"));
        $this->assertFalse(Str::contains(File::get(config_path('app.php')), "'locale' => 'en'"));
    }

    /** @test */
    function doesnt_modify_config_app_locale_if_pass_no_default_option()
    {
        $this->assertTrue(File::exists(config_path('app.php')));
        $this->assertTrue(Str::contains(File::get(config_path('app.php')), "'locale' => 'en'"));

        $this->artisan('lang:add xx_GB --no-default');

        $this->assertTrue(File::exists(config_path('app.php')));
        $this->assertFalse(Str::contains(File::get(config_path('app.php')), "'locale' => 'xx_GB'"));
        $this->assertTrue(Str::contains(File::get(config_path('app.php')), "'locale' => 'en'"));
    }
}
