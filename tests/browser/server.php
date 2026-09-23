<?php

require __DIR__.'/../../vendor/autoload.php';

$assets = [
    '/assets/bootstrap3.css' => __DIR__.'/node_modules/bootstrap3/dist/css/bootstrap.min.css',
    '/assets/bootstrap4.css' => __DIR__.'/node_modules/bootstrap4/dist/css/bootstrap.min.css',
    '/assets/bootstrap5.css' => __DIR__.'/node_modules/bootstrap/dist/css/bootstrap.min.css',
    '/assets/bootstrap3.js'  => __DIR__.'/node_modules/bootstrap3/dist/js/bootstrap.min.js',
    '/assets/bootstrap4.js'  => __DIR__.'/node_modules/bootstrap4/dist/js/bootstrap.bundle.min.js',
    '/assets/jquery.js'      => __DIR__.'/node_modules/jquery/dist/jquery.min.js',
    '/assets/tailwind.css'   => __DIR__.'/tailwind.css',
];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (isset($assets[$path])) {
    header('Content-Type: '.(substr($path, -4) === '.css' ? 'text/css' : 'application/javascript'));
    readfile($assets[$path]);

    return;
}

class BrowserApplication extends \jeremykenedy\LaravelBlocker\Tests\TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $database = __DIR__.'/database.sqlite';
        if (!file_exists($database)) {
            touch($database);
        }
        $framework = $_COOKIE['blocker_framework'] ?? 'bootstrap5';
        $app['config']->set('database.connections.testing.database', $database);
        $app['config']->set('laravelblocker.blockerBladePlacementCss', 'blocker_css');
        $app['config']->set('laravelblocker.blockerBladePlacementJs', 'blocker_js');
        $keyFile = __DIR__.'/app.key';
        if (!file_exists($keyFile)) {
            file_put_contents($keyFile, 'base64:'.base64_encode(random_bytes(32)));
        }
        $app['config']->set('app.key', file_get_contents($keyFile));
        $app['config']->set('app.env', 'local');
        $app['config']->set('app.debug', true);
        $app['config']->set('session.driver', 'file');
        $app['config']->set('laravelblocker.frontend', in_array($framework, ['bootstrap5', 'tailwind'], true) ? $framework : 'legacy');
        $app['config']->set('laravelblocker.blockerBootstapVersion', $framework === 'bootstrap3' ? '3' : '4');
        $app['config']->set('laravelblocker.enablejQueryCDN', false);
        $app['config']->set('laravelblocker.JQueryCDN', '/assets/jquery.js');
        $app['config']->set('laravelblocker.jQueryIpMaskEnabled', false);
        $app['config']->set('view.paths', [__DIR__.'/views', dirname(__DIR__).'/Fixtures/views']);
    }
}

$app = (new BrowserApplication('browser'))->createApplication();
if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
    \Illuminate\Support\Facades\Schema::create('users', function ($table) {
        $table->bigIncrements('id');
        $table->string('name');
        $table->string('email');
        $table->string('password')->nullable();
        $table->timestamps();
    });
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    ob_start();
    (new \jeremykenedy\LaravelBlocker\Database\Seeders\DefaultBlockedTypeTableSeeder())->run();
    (new \jeremykenedy\LaravelBlocker\Database\Seeders\DefaultBlockedItemsTableSeeder())->run();
    ob_end_clean();
}
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
