<?php

namespace Luisprmat\LaravelLangInstaller\Tests\Feature;

use Illuminate\Support\Facades\File;
use Luisprmat\LaravelLangInstaller\Tests\TestCase;

class CopyPhpFilesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        parent::setUp();
        $this->app->setBasePath(__DIR__ . '/../fixtures');

        File::ensureDirectoryExists(config_path());
        File::copy(__DIR__ . '/../stubs/config/app.php', config_path('app.php'));
        File::put(base_path('composer.json'), $this->buildComposerWithDependencies(['"any/package": "^1.0"']));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        File::deleteDirectory(config_path());
        File::deleteDirectory(resource_path());
        File::delete(base_path('composer.json'));
    }

    /** @test */
    function merge_attributes_in_validation()
    {
        $this->artisan('lang:add');

        $this->assertTrue(File::exists(resource_path('lang/es/validation.php')));

        $expected = <<<PHP
<?php

return [
    'before_or_equal' => ':attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'array' => ':attribute tiene que tener entre :min - :max elementos.',
        'file' => ':attribute debe pesar entre :min - :max kilobytes.',
        'numeric' => ':attribute tiene que estar entre :min - :max.',
        'string' => ':attribute tiene que tener entre :min - :max caracteres.',
    ],
    'boolean' => 'El campo :attribute debe tener un valor verdadero o falso.',
    'attributes' => [
        'address' => 'dirección',
        'hour'    => 'hora',
    ],
];

PHP;
        $this->assertEquals(
            $expected,
            File::get(resource_path('lang/es/validation.php'))
        );
    }

    /** @test */
    function merge_attributes_in_validation_other_language()
    {
        $this->artisan('lang:add xx_GB');

        $this->assertTrue(File::exists(resource_path('lang/xx_GB/validation.php')));

        $expected = <<<PHP
<?php

return [
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'attributes' => [
        'address' => 'address',
        'hour'    => 'hour',
    ],
];

PHP;
        $this->assertEquals(
            $expected,
            File::get(resource_path('lang/xx_GB/validation.php'))
        );
    }
}
