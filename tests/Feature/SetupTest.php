<?php

namespace jeremykenedy\LaravelBlocker\Tests\Feature;

use Illuminate\Filesystem\Filesystem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;
use jeremykenedy\LaravelBlocker\Database\Seeders\DefaultBlockedItemsTableSeeder;
use jeremykenedy\LaravelBlocker\Database\Seeders\DefaultBlockedTypeTableSeeder;
use jeremykenedy\LaravelBlocker\LaravelBlockerServiceProvider;
use jeremykenedy\LaravelBlocker\Tests\TestCase;

class SetupTest extends TestCase
{
    private $directory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->directory = sys_get_temp_dir().'/blocker-'.bin2hex(random_bytes(6));
        $this->app->setBasePath($this->directory);
        $provider = new LaravelBlockerServiceProvider($this->app);
        $provider->register();
    }

    protected function tearDown(): void
    {
        (new Filesystem())->deleteDirectory($this->directory);
        parent::tearDown();
    }

    public function test_defaults_and_existing_config_are_preserved(): void
    {
        $this->assertSame('legacy', config('laravelblocker.frontend'));
        $this->assertSame('4', config('laravelblocker.blockerBootstapVersion'));
        $files = new Filesystem();
        $files->makeDirectory(config_path(), 0755, true);
        $files->put(config_path('laravelblocker.php'), '<?php return ["custom" => true];');
        $this->artisan('blocker:install', ['--no-interaction' => true])->assertExitCode(0);
        $this->assertSame('<?php return ["custom" => true];', $files->get(config_path('laravelblocker.php')));
        $this->assertSame('legacy', (require config_path('laravelblocker-ui.php'))['frontend']);
        $this->assertFalse($files->isDirectory(resource_path('views/vendor/laravelblocker')));
    }

    public function test_framework_switching_and_explicit_view_replacement_make_backups(): void
    {
        $this->artisan('blocker:install', ['--framework' => 'tailwind', '--theme' => 'dark', '--views' => true, '--no-interaction' => true])->assertExitCode(0);
        $path = resource_path('views/vendor/laravelblocker/modern/index.blade.php');
        file_put_contents($path, 'custom view');
        $this->artisan('blocker:update', ['--framework' => 'bootstrap5', '--views' => true, '--no-interaction' => true])->assertExitCode(0);
        $this->assertSame('custom view', file_get_contents($path));
        $this->assertSame('bootstrap5', (require config_path('laravelblocker-ui.php'))['frontend']);
        $this->artisan('blocker:update', ['--framework' => 'bootstrap3', '--views' => true, '--force' => true, '--no-interaction' => true])->assertExitCode(0);
        $this->assertNotSame('custom view', file_get_contents($path));
        $backups = glob(resource_path('views/vendor/laravelblocker.backup-*'));
        $this->assertCount(1, $backups);
        $this->assertSame('custom view', file_get_contents($backups[0].'/modern/index.blade.php'));
        $this->assertSame('3', (require config_path('laravelblocker-ui.php'))['blockerBootstapVersion']);
    }

    public function test_invalid_options_and_missing_optional_ui_kit_do_not_write_files(): void
    {
        $this->artisan('blocker:install', ['--framework' => 'unknown', '--no-interaction' => true])->assertExitCode(1);
        $this->artisan('blocker:install', ['--theme' => 'unknown', '--no-interaction' => true])->assertExitCode(1);
        $this->artisan('blocker:install', ['--ui-kit' => true, '--no-interaction' => true])->assertExitCode(1);
        $this->assertFalse(file_exists(config_path('laravelblocker-ui.php')));
    }

    public function test_seeders_are_registered_autoloadable_and_idempotent(): void
    {
        $this->assertContains(DefaultBlockedTypeTableSeeder::class, $this->app['seed.handler']->seeders());
        ob_start();

        try {
            (new DefaultBlockedTypeTableSeeder())->run();
            (new DefaultBlockedItemsTableSeeder())->run();
            BlockedItem::first()->delete();
            (new DefaultBlockedTypeTableSeeder())->run();
            (new DefaultBlockedItemsTableSeeder())->run();
        } finally {
            ob_end_clean();
        }
        $this->assertSame(10, BlockedType::count());
        $this->assertSame(5, BlockedItem::withTrashed()->count());
        $this->assertSame(1, BlockedItem::onlyTrashed()->count());
    }
}
