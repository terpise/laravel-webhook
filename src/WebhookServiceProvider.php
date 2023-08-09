<?php

namespace Terpise\Webhook;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Terpise\Webhook\Commands\WebhookTestCommand;
use Terpise\Webhook\Commands\WebhookMakeCommand;
use Terpise\Webhook\Commands\WebhookRunCommand;

class WebhookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerPublishing();
        $this->registerCommands();
    }

    /**
     * Register the Webhook routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (Webhook::$registersRoutes) {
            Route::group([
                'prefix' => config('webhook.prefix', 'webhooks'),
            ], function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            });
        }
    }

    /**
     * Register the Webhook migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole() && Webhook::$runsMigrations) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'webhook-migrations');
            $this->publishes([
                __DIR__ . '/../config/webhook.php' => config_path('webhook.php'),
            ], 'webhook-config');
        }
    }

    /**
     * Register the Webhook Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                WebhookTestCommand::class,
                WebhookMakeCommand::class,
            ]);
        }
    }
}
