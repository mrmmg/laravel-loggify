<?php


namespace Mrmmg\LaravelLoggify;


use Illuminate\Support\ServiceProvider;
use Mrmmg\LaravelLoggify\Console\Commands\InstallLoggify;
use Mrmmg\LaravelLoggify\Console\Commands\PublishAssets;
use stdClass;

class LaravelLoggifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }

        $this->extendApplicationRedisConnections();
        $this->extendApplicationLoggingConnections();

        $this->publishAssets();
        $this->registerViews();

        $this->registerRoutes();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/loggify.php', 'laravelLoggify');
        $this->registerCommands();
    }

    private function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/loggify.php' => config_path('loggify.php'),
        ], 'loggify-config');
    }

    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    private function publishAssets()
    {
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/loggify'),
        ], 'loggify-assets');
    }

    private function registerViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'loggify');
    }

    private function registerCommands(): void
    {
        $this->commands([
            PublishAssets::class,
            InstallLoggify::class
        ]);
    }

    private function extendApplicationLoggingConnections(): void
    {
        config([
            "logging.channels.loggify" => config("loggify.logging.channels.loggify")
        ]);
    }

    private function extendApplicationRedisConnections(): void
    {
        config([
            "database.redis.loggify" => config('loggify.database.redis')
        ]);
    }
}
