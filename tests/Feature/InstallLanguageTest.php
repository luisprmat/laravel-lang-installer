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
        File::delete(base_path('composer.json'));
    }

    /** @test */
    function it_installs_supported_language()
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
    function it_installs_supported_language_with_validation_inline()
    {
        $this->artisan('lang:add es --inline')
            ->expectsOutput("Language [es] installed successfully as default.");

        $this->assertStringContainsString(
            'El campo debe ser verdadero o falso.',
            File::get(resource_path('lang/es/validation.php'))
        );

        $this->assertStringNotContainsString(
            'El campo :attribute debe tener un valor verdadero o falso.',
            File::get(resource_path('lang/es/validation.php'))
        );
    }

    /** @test */
    function it_installs_spanish_language_by_default()
    {
        $this->artisan('lang:add')
            ->expectsOutput("Language [es] installed successfully as default.");
    }

    /** @test */
    function it_doesnt_install_language_if_not_supported()
    {
        $this->assertFalse(File::exists(resource_path('lang/no_valid')));
        $this->artisan('lang:add no-valid')
            ->expectsOutput("Language [no-valid] is not supported!");

        $this->assertFalse(File::exists(resource_path('lang/no_valid')));

        $this->artisan('lang:add es')
            ->doesntExpectOutput("Language [es] is not supported!");
    }

    /** @test */
    function it_modifies_config_app_locale_by_default()
    {
        $this->assertTrue(File::exists(config_path('app.php')));
        $this->assertTrue(Str::contains(File::get(config_path('app.php')), "'locale' => 'en'"));

        $this->artisan('lang:add xx_GB');

        $this->assertTrue(File::exists(config_path('app.php')));
        $this->assertTrue(Str::contains(File::get(config_path('app.php')), "'locale' => 'xx_GB'"));
        $this->assertFalse(Str::contains(File::get(config_path('app.php')), "'locale' => 'en'"));
    }

    /** @test */
    function it_doesnt_modify_config_app_locale_if_pass_no_default_option()
    {
        $this->assertTrue(File::exists(config_path('app.php')));
        $this->assertTrue(Str::contains(File::get(config_path('app.php')), "'locale' => 'en'"));

        $this->artisan('lang:add xx_GB --no-default');

        $this->assertTrue(File::exists(config_path('app.php')));
        $this->assertFalse(Str::contains(File::get(config_path('app.php')), "'locale' => 'xx_GB'"));
        $this->assertTrue(Str::contains(File::get(config_path('app.php')), "'locale' => 'en'"));
    }

    /** @test */
    function it_install_json_locale_only_with_base_translations()
    {
        $this->artisan('lang:add es');
        $expected = <<<JSON
{
    "Add Base": "Añadir base",
    "Changes Base": "Cambios base",
    "If you already have an account, you may accept this invitation by clicking the button below: base": "Si ya tiene una cuenta, puede aceptar esta invitación haciendo clic en el botón de abajo: base",
    "Whoops! all": "¡Ups! todo"
}
JSON;
        $this->assertEquals(
            $expected,
            File::get(resource_path('lang/es.json'))
        );

        $this->artisan('lang:add xx_GB');
        $expected = <<<JSON
{
    "Add Base": "Add base",
    "Changes Base": "Changes base",
    "If you already have an account, you may accept this invitation by clicking the button below: base": "If you already have an account, you may accept this invitation by clicking the button below: base",
    "Whoops! all": "Whoops! all"
}
JSON;
        $this->assertEquals(
            $expected,
            File::get(resource_path('lang/xx_GB.json'))
        );
    }

    /** @test */
    function it_doesnt_execute_if_composer_json_doesnt_exist()
    {
        $this->artisan('lang:add')
            ->expectsOutput('composer.json not found!')
            ->assertExitCode(0);
    }

    /** @test */
    function it_discovers_supported_packages_installed_from_composer_json()
    {
        File::put(base_path('composer.json'), $this->buildComposerWithDependencies(
            ['"laravel/cashier": "^13.5"', '"package/other": "^2.0"'],
            ['"laravel/breeze": "^1.4"', '"laravel/no-supported": "^1.0"']
        ));

        $command = $this->artisan('lang:add');
        $command->expectsOutput('Translations for [breeze, cashier] packages merged!');

        $this->setUp();

        File::put(base_path('composer.json'), $this->buildComposerWithDependencies(
            ['"package/other": "^2.0"'],
            ['"laravel/breeze": "^1.4"', '"laravel/no-supported": "^1.0"']
        ));

        $command = $this->artisan('lang:add');
        $command->expectsOutput('Translations for [breeze] package merged!');

        $this->setUp();

        File::put(base_path('composer.json'), $this->buildComposerWithDependencies(
            ['"laravel/cashier": "^13.5"'], []
        ));

        $command = $this->artisan('lang:add');
        $command->expectsOutput('Translations for [cashier] package merged!');
    }

    /**
     * @param array $require
     * @param array $requireDev
     * @return string
     */
    private function buildComposerWithDependencies(array $require = [], array $requireDev = []): string
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
