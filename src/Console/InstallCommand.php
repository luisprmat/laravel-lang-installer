<?php

namespace Luisprmat\LaravelLangInstaller\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    protected $signature = 'lang:add {locale=es : The language that should be installed (es, fr, pt, ...)}
                                     {--I|inline : Install validation.php with generic attributes (no name for attribute)}
                                     {--D|no-default : It does not change the default language in config/app.php}';

    protected $description = "Install translations for language 'locale' (default 'es')";

    public function handle()
    {
        $locale = (string)$this->argument('locale');

        (new Filesystem)->ensureDirectoryExists(resource_path("lang/{$locale}"));

        copy(__DIR__ . "/../../vendor/laravel-lang/lang/locales/{$locale}/auth.php", resource_path("lang/{$locale}/auth.php"));
        copy(__DIR__ . "/../../vendor/laravel-lang/lang/locales/{$locale}/pagination.php", resource_path("lang/{$locale}/pagination.php"));
        copy(__DIR__ . "/../../vendor/laravel-lang/lang/locales/{$locale}/passwords.php", resource_path("lang/{$locale}/passwords.php"));

        if ($this->option('inline')) {
            copy(__DIR__ . "/../../vendor/laravel-lang/lang/locales/{$locale}/validation-inline.php", resource_path("lang/{$locale}/validation.php"));
        } else {
            copy(__DIR__ . "/../../vendor/laravel-lang/lang/locales/{$locale}/validation.php", resource_path("lang/{$locale}/validation.php"));
        }

        $this->loadJsonFile($locale);

        if (!$this->option('no-default')) {
            // Set config('app.locale')
            $this->pregReplaceInFile("/('locale')\s{0,}(=>)\s{0,}(')[A-Za-z_-]+(')\s{0,}(,)/", "'locale' => '{$locale}',", config_path('app.php'));
            $this->info("Language [{$locale}] installed successfully as default.");
        } else {
            $this->info("Language [{$locale}] installed successfully, but it isn't the default language.");
        }
    }

    /**
     * Replace a given string within a given file matching with a regular expression.
     *
     * @param string $search // (RegExp)
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function pregReplaceInFile($search, $replace, $path)
    {
        file_put_contents($path, preg_replace($search, $replace, file_get_contents($path)));
    }

    private function loadJsonFile($locale)
    {
        copy(__DIR__ . "/../../vendor/laravel-lang/lang/locales/{$locale}/{$locale}.json", resource_path("lang/{$locale}.json"));
    }
}
