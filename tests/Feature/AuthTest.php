<?php

namespace jeremykenedy\LaravelBlocker\Tests\Feature;

use Illuminate\Support\Facades\Route;
use jeremykenedy\LaravelBlocker\Tests\TestCase;

class AuthTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('laravelblocker.authEnabled', true);
        $app['config']->set('laravelblocker.rolesEnabled', true);
        $app['config']->set('laravelblocker.rolesMiddlware', DenyRole::class);
    }

    public function test_static_assets_are_public_and_restricted_to_package_files(): void
    {
        foreach (['blocker.css' => 'text/css', 'legacy.css' => 'text/css', 'blocker.js' => 'application/javascript'] as $asset => $type) {
            $response = $this->get('/laravel-blocker/assets/'.$asset)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
            $this->assertSame($type, explode(';', $response->headers->get('Content-Type'))[0]);
        }
        $this->get('/laravel-blocker/assets/laravelblocker.php')->assertNotFound();
        $this->get('/laravel-blocker/assets/%2e%2e%2fconfig%2flaravelblocker.php')->assertNotFound();
    }

    public function test_guest_and_unauthorized_users_cannot_read_or_write(): void
    {
        Route::get('/login', function () {
            return 'login';
        })->name('login');
        $this->getJson('/blocker')->assertStatus(401);
        $this->postJson('/blocker', [])->assertStatus(401);
        $this->getJson('/blocker-deleted')->assertStatus(401);
        $this->actingAs(\App\User::create(['name' => 'Member', 'email' => 'member@example.org']));
        foreach (['/blocker', '/blocker/create', '/blocker-deleted'] as $url) {
            $this->getJson($url)->assertStatus(403);
        }
        $this->postJson('/blocker', [])->assertStatus(403);
        $this->deleteJson('/blocker-deleted-destroy-all')->assertStatus(403);
    }
}

class DenyRole
{
    public function handle($request, $next)
    {
        abort(403);
    }
}
