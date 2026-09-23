<?php

namespace jeremykenedy\LaravelBlocker\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelBlocker\App\Http\Requests\StoreBlockerRequest;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;
use jeremykenedy\LaravelBlocker\LaravelBlockerFacade;
use jeremykenedy\LaravelBlocker\LaravelBlockerServiceProvider;
use jeremykenedy\LaravelBlocker\Tests\TestCase;

class CompatibilityTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('laravelblocker.blockerDatabaseTable', 'custom_blocks');
        $app['config']->set('laravelblocker.blockerTypeDatabaseTable', 'custom_block_types');
    }

    public function test_custom_tables_and_connection_are_used_for_validation_and_crud(): void
    {
        config(['database.connections.unrelated' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''], 'database.default' => 'unrelated']);
        $type = BlockedType::create(['slug' => 'domain', 'name' => 'Domain']);
        $data = ['typeId' => $type->id, 'value' => 'configured.example'];
        $this->post('/blocker', $data)->assertRedirect('/blocker');
        $item = BlockedItem::firstOrFail();
        $this->postJson('/blocker', $data)->assertStatus(422)->assertJsonValidationErrors('value');
        $this->put('/blocker/'.$item->id, $data)->assertRedirect();
        $this->assertTrue(Schema::connection('testing')->hasTable('custom_blocks'));
        $this->assertFalse(Schema::connection('unrelated')->hasTable('custom_blocks'));
    }

    public function test_user_validation_uses_the_configured_model_connection_table_and_key(): void
    {
        config(['database.connections.accounts' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''], 'laravelblocker.defaultUserModel' => HostUser::class]);
        Schema::connection('accounts')->create('members', function ($table) {
            $table->increments('user_id');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });
        $user = HostUser::create(['name' => 'Member', 'email' => 'member@example.org']);
        $type = BlockedType::create(['slug' => 'user', 'name' => 'User']);
        $data = ['typeId' => $type->id, 'value' => $user->email, 'userId' => $user->getKey()];
        $request = StoreBlockerRequest::create('/blocker', 'POST', $data);
        $this->assertTrue(Validator::make($data, $request->rules())->passes());
        $user->delete();
        $this->assertTrue(Validator::make($data, $request->rules())->errors()->has('userId'));
    }

    public function test_provider_keeps_alias_publish_tags_and_route_names(): void
    {
        $this->assertSame($this->app['laravelblocker'], LaravelBlockerFacade::getFacadeRoot());
        foreach (['config', 'views', 'lang', 'migrations', 'seeders'] as $tag) {
            $this->assertNotEmpty(LaravelBlockerServiceProvider::pathsToPublish(LaravelBlockerServiceProvider::class, 'laravelblocker-'.$tag));
        }
        $this->assertSame('/blocker', route('laravelblocker::blocker.index', [], false));
        $this->assertSame('/search-blocked', route('laravelblocker::search-blocked', [], false));
        $this->assertSame('/blocker-deleted/1', route('laravelblocker::blocker-item-restore', 1, false));
    }

    public function test_ui_profile_overrides_only_presentation_settings(): void
    {
        config(['laravelblocker-ui' => ['frontend' => 'tailwind', 'theme' => 'dark', 'authEnabled' => true]]);
        (new LaravelBlockerServiceProvider($this->app))->register();
        $this->assertSame('tailwind', config('laravelblocker.frontend'));
        $this->assertSame('dark', config('laravelblocker.theme'));
        $this->assertFalse(config('laravelblocker.authEnabled'));
    }

    public function test_migrations_are_repeatable_and_can_be_rolled_back(): void
    {
        $typeMigration = new \CreateLaravelBlockerTypesTable();
        $itemMigration = new \CreateLaravelBlockerTable();
        $typeMigration->up();
        $itemMigration->up();
        $this->assertTrue(Schema::hasTable('custom_blocks'));
        $itemMigration->down();
        $typeMigration->down();
        $this->assertFalse(Schema::hasTable('custom_blocks'));
        $this->assertFalse(Schema::hasTable('custom_block_types'));
    }
}

class HostUser extends \App\User
{
    protected $connection = 'accounts';

    protected $table = 'members';

    protected $primaryKey = 'user_id';
}
