<?php

declare(strict_types=1);

namespace DennisCuijpers\Browser;

use Illuminate\Support\ServiceProvider;

class BrowserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/browser.php', 'browser');
    }

    public function boot(): void
    {
        Browser::init();

        $this->loadRoutesFrom(__DIR__ . '/../routes/browser.php');

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/browser.php' => config_path('browser.php'),
        ], 'browser-config');
    }
}
