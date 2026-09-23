<?php

namespace jeremykenedy\LaravelBlocker\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use jeremykenedy\LaravelBlocker\LaravelBlockerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [\Spatie\Html\HtmlServiceProvider::class, \Seedster\SeedsterServiceProvider::class, LaravelBlockerServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true]);
        $app['config']->set('laravelblocker.blockerDatabaseConnection', 'testing');
        $app['config']->set('laravelblocker.laravelBlockerEnabled', false);
        $app['config']->set('laravelblocker.authEnabled', false);
        $app['config']->set('laravelblocker.enablejQueryCDN', false);
        $app['config']->set('laravelblocker.blockerEnableFontAwesomeCDN', false);
        $app['config']->set('view.paths', [__DIR__.'/Fixtures/views']);
        $app['config']->set('auth.providers.users.model', \App\User::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app['view']->addLocation(__DIR__.'/Fixtures/views');
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->timestamps();
        });
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }
}
