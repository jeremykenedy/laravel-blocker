<?php

namespace jeremykenedy\LaravelBlocker\Tests\Feature;

use Illuminate\Support\Facades\Route;
use jeremykenedy\LaravelBlocker\App\Http\Middleware\LaravelBlocker;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;
use jeremykenedy\LaravelBlocker\Tests\TestCase;

class MiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['laravelblocker.laravelBlockerEnabled' => true]);
        $this->app->bind(LaravelBlocker::class, FakeLocationBlocker::class);
        Route::middleware(['web', 'checkblocked'])->get('/protected', function () {
            return 'allowed';
        });
        Route::middleware(['web', 'checkblocked'])->post('/register', function () {
            return 'registered';
        });
        Route::middleware(['web', 'checkblocked'])->get('/blocked', function () {
            return 'landing';
        });
    }

    private function block($value)
    {
        $type = BlockedType::firstOrCreate(['slug' => 'ipAddress'], ['name' => 'IP']);

        return BlockedItem::create(['typeId' => $type->id, 'value' => $value]);
    }

    public function test_ip_blocks_and_runtime_rule_changes_are_observed(): void
    {
        $this->get('/protected')->assertOk();
        $item = $this->block('127.0.0.1');
        $this->get('/protected')->assertStatus(403);
        $item->delete();
        $this->get('/protected')->assertOk();
        $item->restore();
        $this->get('/protected')->assertStatus(403);
    }

    public function test_unrelated_rules_are_not_loaded_for_string_candidates(): void
    {
        $this->block('unrelated.example');
        $retrieved = 0;
        BlockedItem::retrieved(function () use (&$retrieved) {
            $retrieved++;
        });
        $this->get('/protected')->assertOk();
        $this->assertSame(0, $retrieved);
        $this->block('127.0.0.1');
        $this->get('/protected')->assertStatus(403);
        $this->assertSame(1, $retrieved);
    }

    public function test_numeric_location_values_keep_legacy_comparison_behavior(): void
    {
        config(['blocker_test_location' => ['region' => '1']]);
        $this->block('01');
        $this->get('/protected')->assertStatus(403);
    }

    public function test_disabled_middleware_bypasses_blocking(): void
    {
        $this->block('127.0.0.1');
        config(['laravelblocker.laravelBlockerEnabled' => false]);
        $this->get('/protected')->assertOk();
    }

    public function test_registration_blocks_email_and_domain_and_handles_missing_email(): void
    {
        $this->block('blocked.example');
        $this->from('/signup')->post('/register', ['email' => 'a@blocked.example'])->assertRedirect('/signup')->assertSessionHas('error');
        $this->post('/register', [])->assertOk();
        $this->post('/register', ['email' => ['invalid']])->assertOk();
        $this->block('person@other.example');
        $this->from('/signup')->post('/register', ['email' => 'person@other.example'])->assertRedirect('/signup');
    }

    public function test_authenticated_email_and_domain_are_blocked(): void
    {
        $user = \App\User::create(['name' => 'Member', 'email' => 'a@blocked.example']);
        $item = $this->block('blocked.example');
        $this->actingAs($user)->get('/protected')->assertStatus(403);
        $item->delete();
        $this->get('/protected')->assertOk();
        $this->block($user->email);
        $this->get('/protected')->assertStatus(403);
    }

    public function test_location_fields_are_checked(): void
    {
        foreach (['Paris', 'Ile-de-France', 'France', 'FR', 'Europe', 'IDF'] as $value) {
            $item = $this->block($value);
            $this->get('/protected')->assertStatus(403);
            $item->forceDelete();
        }
    }

    public function test_redirect_and_view_actions_stop_the_request_without_redirect_loops(): void
    {
        $this->block('127.0.0.1');
        config(['laravelblocker.blockerDefaultAction' => 'redirect', 'laravelblocker.blockerDefaultActionRedirect' => '/blocked']);
        $this->get('/protected')->assertRedirect('/blocked');
        $this->get('/blocked')->assertOk()->assertSee('landing');
        config(['laravelblocker.blockerDefaultAction' => 'view', 'laravelblocker.blockerDefaultActionView' => 'blocked']);
        $this->get('/protected')->assertOk()->assertSee('Access restricted')->assertDontSee('allowed');
        config(['laravelblocker.blockerDefaultAction' => 'abort', 'laravelblocker.blockerDefaultActionAbortType' => 451]);
        $this->get('/protected')->assertStatus(451);
    }
}

class FakeLocationBlocker extends LaravelBlocker
{
    public static function checkIP($ip = null, $purpose = 'location', $deep_detect = true)
    {
        return config('blocker_test_location', ['city' => 'Paris', 'state' => 'Ile-de-France', 'country' => 'France', 'countryCode' => 'FR', 'continent' => 'Europe', 'region' => 'IDF']);
    }
}
