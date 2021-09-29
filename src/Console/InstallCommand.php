<?php

namespace Luisprmat\LaravelLangInstaller\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'lang:add {locale=es : The language that should be installed (es, fr, pt, ...)}
                                     {--I|inline : Install validation.php with generic attributes (no name for attribute)}
                                     {--D|no-default : It does not change the default language in config/app.php}';

    protected $description = "Install translations for language 'locale' (default 'es')";

    private array $supportedPackages = [
        'breeze' => 'laravel/breeze',
        'fortify' => 'laravel/fortify',
        'cashier' => 'laravel/cashier',
        'jetstream' => 'laravel/jetstream'
    ];

    public function handle()
    {
        $locale = (string)$this->argument('locale');

        if (!in_array($locale, $this->getLocales(base_path('vendor/laravel-lang/lang/locales')))) {
            $this->error("Language [{$locale}] is not supported!");
            return;
        }

        if (!File::exists(base_path('composer.json'))) {
            $this->error('composer.json not found!');
            return;
        }

        (new Filesystem)->ensureDirectoryExists(resource_path("lang/{$locale}"));

        copy(base_path("vendor/laravel-lang/lang/locales/{$locale}/auth.php"), resource_path("lang/{$locale}/auth.php"));
        copy(base_path("vendor/laravel-lang/lang/locales/{$locale}/pagination.php"), resource_path("lang/{$locale}/pagination.php"));
        copy(base_path("vendor/laravel-lang/lang/locales/{$locale}/passwords.php"), resource_path("lang/{$locale}/passwords.php"));

        if ($this->option('inline')) {
            copy(base_path("vendor/laravel-lang/lang/locales/{$locale}/validation-inline.php"), resource_path("lang/{$locale}/validation.php"));
        } else {
            copy(base_path("vendor/laravel-lang/lang/locales/{$locale}/validation.php"), resource_path("lang/{$locale}/validation.php"));
            $this->mergeAttributes($locale);
        }

        $discoveredPackages = $this->discoveredPackages();

        // Add 'fortify' translations if 'jetstream' is installed
        if (in_array('jetstream', $discoveredPackages)) {
            array_push($discoveredPackages, 'fortify', 'jetstream-ext');
        }

        $this->loadJsonFile($locale, $discoveredPackages);

        if (!$this->option('no-default')) {
            // Set config('app.locale')
            $this->pregReplaceInFile("/('locale')\s{0,}(=>)\s{0,}(')[A-Za-z_-]+(')\s{0,}(,)/", "'locale' => '{$locale}',", config_path('app.php'));
            $this->info("Language [{$locale}] installed successfully as default.");
        } else {
            $this->info("Language [{$locale}] installed successfully, but it isn't the default language.");
        }

        if (in_array('jetstream-ext', $discoveredPackages)) {
            unset($discoveredPackages[array_search('jetstream-ext', $discoveredPackages)]);
        }

        if (!empty($discoveredPackages)) {
            $this->info(
                'Translations for [' . implode(', ', $discoveredPackages) . '] '
                . Str::plural('package', count($discoveredPackages)) . ' merged!'
            );
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

    private function loadJsonFile($locale, $packages = [])
    {
        $baseSource = json_decode(File::get(base_path('vendor/laravel-lang/lang/source/en.json')));
        $jsonLocale = json_decode(File::get(base_path("vendor/laravel-lang/lang/locales/{$locale}/{$locale}.json")), true);

        $showTags = $baseSource;

        foreach ($packages as $package) {
            $showTags = array_merge(
                $showTags,
                json_decode(File::get(base_path("vendor/laravel-lang/lang/source/packages/{$package}.json")))
            );
        }

        $showTags = array_unique($showTags);
        sort($showTags);

        $modify = array_filter($jsonLocale, function ($item) use ($showTags) {
            return in_array($item, $showTags);
        }, ARRAY_FILTER_USE_KEY);

        $modifiedJson = json_encode($modify, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        File::put(resource_path("lang/{$locale}.json"), $modifiedJson);
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getLocales(string $path): array
    {
        $filesystem = new Filesystem;

        $directories = $filesystem->directories($path);

        $locales = [];

        foreach ($directories as $directory) {
            $locales[] = $filesystem->name($directory);
        }
        return $locales;
    }

    /**
     * Returns list of installed packages that are supported according to composer.json
     *
     * @return array
     */
    protected function discoveredPackages(): array
    {
        $composer = json_decode(File::get(base_path('composer.json')), true);

        $jsonToCreate = array_keys(array_merge($composer['require'], $composer['require-dev']));

        $packagesToInstall = array_filter($jsonToCreate, function ($package) {
            return in_array($package, $this->supportedPackages);
        });

        return array_keys(array_filter($this->supportedPackages, function ($package) use ($packagesToInstall) {
            return in_array($package, $packagesToInstall);
        }));
    }

    private function mergeAttributes($locale)
    {
        $attributesSource = base_path("vendor/laravel-lang/lang/locales/{$locale}/validation-attributes.php");
        if (!File::exists($attributesSource)) return;

        $attributes = File::get($attributesSource);

        $separator = <<<PHP
return [

PHP;
        $endOfLine = <<<PHP
];

PHP;
        $attributes = Str::replace($endOfLine, "];", $attributes);
        $split = explode($separator, $attributes);
        $this->replaceInFile("];", $split[1], resource_path("lang/{$locale}/validation.php"));
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
