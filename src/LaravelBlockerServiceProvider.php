<?php

namespace jeremykenedy\LaravelBlocker;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController;
use jeremykenedy\LaravelBlocker\App\Http\Middleware\LaravelBlocker;
use jeremykenedy\LaravelBlocker\Database\Seeders\DefaultBlockedItemsTableSeeder;
use jeremykenedy\LaravelBlocker\Database\Seeders\DefaultBlockedTypeTableSeeder;

class LaravelBlockerServiceProvider extends ServiceProvider
{
    private $_packageTag = 'laravelblocker';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router): void
    {
        $router->middlewareGroup('checkblocked', [LaravelBlocker::class]);
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang/', $this->_packageTag);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->packageRegistration();
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views/', $this->_packageTag);
        $this->mergeConfigFrom(__DIR__.'/config/'.$this->_packageTag.'.php', $this->_packageTag);
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadSeedsFrom();
        $this->publishFiles();
    }

    /**
     * Package Registration.
     *
     * @return void
     */
    private function packageRegistration(): void
    {
        $this->app->make(LaravelBlockerController::class);
        $this->app->singleton(LaravelBlockerController::class, function () {
            return new App\Http\Controllers\LaravelBlockerController();
        });
        $this->app->alias(LaravelBlockerController::class, $this->_packageTag);
    }

    /**
     * Loads a seeds.
     *
     * @return void
     */
    private function loadSeedsFrom(): void
    {
        if (config('laravelblocker.seedDefaultBlockedTypes')) {
            $this->app['seed.handler']->register(
                DefaultBlockedTypeTableSeeder::class
            );
        }
        if (config('laravelblocker.seedDefaultBlockedItems')) {
            $this->app['seed.handler']->register(
                DefaultBlockedItemsTableSeeder::class
            );
        }

        if (config('laravelblocker.useSeededBlockedTypes')) {
            $this->app['seed.handler']->register(
                \Database\Seeders\BlockedTypeTableSeeder::class
            );
        }

        if (config('laravelblocker.useSeededBlockedItems')) {
            $this->app['seed.handler']->register(
                \Database\Seeders\BlockedItemsTableSeeder::class
            );
        }
    }

    /**
     * Publish files for Laravel Blocker.
     *
     * @return void
     */
    private function publishFiles(): void
    {
        $publishTag = $this->_packageTag;

        $this->publishes([
            __DIR__.'/config/'.$this->_packageTag.'.php' => base_path('config/'.$this->_packageTag.'.php'),
        ], $publishTag.'-config');

        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/vendor/'.$this->_packageTag),
        ], $publishTag.'-views');

        $this->publishes([
            __DIR__.'/resources/lang' => base_path('resources/lang/vendor/'.$this->_packageTag),
        ], $publishTag.'-lang');

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ], $publishTag.'-migrations');

        $this->publishes([
            __DIR__.'/database/seeders/publish' => database_path('seeds'),
        ], $publishTag.'-seeds');
    }
}
