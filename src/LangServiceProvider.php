<?php

namespace Luisprmat\LaravelLangInstaller;

use Illuminate\Support\ServiceProvider;

class LangServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureCommands();
    }

    public function register()
    {
        //
    }

    protected function configureCommands(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }
}
