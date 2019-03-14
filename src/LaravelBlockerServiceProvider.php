<?php

namespace jeremykenedy\LaravelBlocker;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use jeremykenedy\LaravelBlocker\App\Http\Middleware\LaravelBlocker;
use jeremykenedy\LaravelBlocker\Database\Seeds\DefaultBlockedItemsTableSeeder;
use jeremykenedy\LaravelBlocker\Database\Seeds\DefaultBlockedTypeTableSeeder;

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
    public function boot(Router $router)
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
    public function register()
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
    private function packageRegistration()
    {
        $this->app->make('jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController');
        $this->app->singleton(jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController::class, function () {
            return new App\Http\Controllers\LaravelBlockerController();
        });
        $this->app->alias(LaravelBlockerController::class, $this->_packageTag);
    }

    /**
     * Loads a seeds.
     *
     * @return void
     */
    private function loadSeedsFrom()
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
                \Database\Seeds\BlockedTypeTableSeeder::class
            );
        }

        if (config('laravelblocker.useSeededBlockedItems')) {
            $this->app['seed.handler']->register(
                \Database\Seeds\BlockedItemsTableSeeder::class
            );
        }
    }

    /**
     * Publish files for Laravel Blocker.
     *
     * @return void
     */
    private function publishFiles()
    {
        $publishTag = $this->_packageTag;

        $this->publishes([
            __DIR__.'/config/'.$this->_packageTag.'.php' => base_path('config/'.$this->_packageTag.'.php'),
        ], $publishTag.'-config');

        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/vendor/'),
        ], $publishTag.'-views');

        $this->publishes([
            __DIR__.'/resources/lang' => base_path('resources/lang/vendor/'.$this->_packageTag),
        ], $publishTag.'-lang');

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ], $publishTag.'-migrations');

        $this->publishes([
            __DIR__.'/database/seeds/publish' => database_path('seeds'),
        ], $publishTag.'-seeds');
    }
}
