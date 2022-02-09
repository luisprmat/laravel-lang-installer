<?php

namespace Luisprmat\LaravelLangInstaller\Tests\Feature;

use Illuminate\Support\Facades\File;
use Luisprmat\LaravelLangInstaller\Tests\TestCase;

class DiscoverPackagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->setBasePath(__DIR__ . '/../fixtures');

        File::ensureDirectoryExists(config_path());
        File::copy(__DIR__ . '/../stubs/config/app.php', config_path('app.php'));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        File::deleteDirectory(config_path());
        File::deleteDirectory(base_path('lang'));
        File::delete(base_path('composer.json'));
    }

    /** @test */
    function it_doesnt_execute_if_composer_json_doesnt_exist()
    {
        $this->artisan('lang:add')
            ->expectsOutput('composer.json not found!')
            ->assertExitCode(0);
    }

    /** @test */
    function it_discovers_several_supported_packages_installed_from_composer_json()
    {
        File::put(base_path('composer.json'), $this->buildComposerWithDependencies(
            ['"laravel/cashier": "^13.5"', '"package/other": "^2.0"'],
            ['"laravel/breeze": "^1.4"', '"laravel/no-supported": "^1.0"']
        ));

        $command = $this->artisan('lang:add');
        $command->expectsOutput('Translations for [breeze, cashier] packages merged!');
    }

    /** @test */
    function it_discovers_one_supported_package_installed_from_composer_json_require_dev()
    {
        File::put(base_path('composer.json'), $this->buildComposerWithDependencies(
            ['"package/other": "^2.0"'],
            ['"laravel/breeze": "^1.4"', '"laravel/no-supported": "^1.0"']
        ));

        $command = $this->artisan('lang:add');
        $command->expectsOutput('Translations for [breeze] package merged!');
    }

    /** @test */
    function it_discovers_one_supported_package_installed_from_composer_json_require()
    {
        File::put(base_path('composer.json'), $this->buildComposerWithDependencies(
            ['"laravel/cashier": "^13.5"']
        ));

        $command = $this->artisan('lang:add');
        $command->expectsOutput('Translations for [cashier] package merged!');
    }
}
