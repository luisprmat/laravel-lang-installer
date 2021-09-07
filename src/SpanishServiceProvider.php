<?php

namespace Luisprmat\Spanish;

use Illuminate\Support\ServiceProvider;

class SpanishServiceProvider extends ServiceProvider
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