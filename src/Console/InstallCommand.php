<?php

namespace jeremykenedy\LaravelBlocker\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    protected $signature = 'blocker:install
        {--framework= : bootstrap3, bootstrap4, bootstrap5, or tailwind}
        {--theme= : light, dark, or system}
        {--views : Publish views for customization}
        {--force : Back up and replace published views}
        {--ui-kit : Run the optional Laravel UI Kit installer}';

    protected $description = 'Configure Laravel Blocker without replacing application settings';

    public function handle(Filesystem $files)
    {
        $frameworks = ['bootstrap3', 'bootstrap4', 'bootstrap5', 'tailwind'];
        $current = config('laravelblocker.frontend', 'legacy');
        if ($current === 'legacy') {
            $current = config('laravelblocker.blockerBootstapVersion') == '3' ? 'bootstrap3' : 'bootstrap4';
        }
        $framework = $this->option('framework');
        if (!$framework && $this->input->isInteractive()) {
            $framework = $this->choice('Frontend framework', $frameworks, array_search($current, $frameworks));
        }
        $framework = $framework ?: $current;
        $theme = $this->option('theme') ?: config('laravelblocker.theme', 'light');
        if (!in_array($framework, $frameworks, true) || !in_array($theme, ['light', 'dark', 'system'], true)) {
            $this->error('Choose a supported framework and theme. No files were changed.');

            return 1;
        }
        if ($this->option('ui-kit')) {
            if ($framework === 'bootstrap3' || !isset($this->getApplication()->all()['ui-kit:install'])) {
                $this->error('UI Kit requires Bootstrap 4, Bootstrap 5, or Tailwind and an installed jeremykenedy/laravel-ui-kit package.');

                return 1;
            }
            if ($this->call('ui-kit:install', ['--css' => $framework, '--frontend' => 'blade']) !== 0) {
                return 1;
            }
        }

        if (!$files->isDirectory(config_path())) {
            $files->makeDirectory(config_path(), 0755, true);
        }
        $settings = [
            'frontend'               => in_array($framework, ['bootstrap3', 'bootstrap4'], true) ? 'legacy' : $framework,
            'blockerBootstapVersion' => $framework === 'bootstrap3' ? '3' : '4',
            'theme'                  => $theme,
        ];
        $path = config_path('laravelblocker-ui.php');
        if ($files->exists($path)) {
            $this->backup($files, $path);
        }
        $files->put($path, "<?php\n\nreturn ".var_export($settings, true).";\n");
        $this->call('vendor:publish', ['--tag' => 'laravelblocker-config']);

        if ($this->option('views')) {
            $views = resource_path('views/vendor/laravelblocker');
            if ($this->option('force') && $files->isDirectory($views)) {
                $this->backup($files, $views);
            }
            $this->call('vendor:publish', ['--tag' => 'laravelblocker-views', '--force' => (bool) $this->option('force')]);
        }
        $this->call('config:clear');
        $this->call('view:clear');
        $this->info('Laravel Blocker configured for '.$framework.' ('.$theme.').');
        $this->line('Review the database connection and user model in config/laravelblocker.php before running migrations.');
        $this->line('Run php artisan migrate, then php artisan db:seed --class="jeremykenedy\\LaravelBlocker\\Database\\Seeders\\DefaultBlockedTypeTableSeeder" for a new installation.');

        return 0;
    }

    private function backup(Filesystem $files, $path)
    {
        $directory = storage_path('app/laravelblocker-backups');
        if (!$files->isDirectory($directory)) {
            $files->makeDirectory($directory, 0755, true);
        }
        $backup = $directory.'/'.basename($path).'.backup-'.date('YmdHis').'-'.bin2hex(random_bytes(4));
        $copied = $files->isDirectory($path) ? $files->copyDirectory($path, $backup) : $files->copy($path, $backup);
        if (!$copied) {
            throw new \RuntimeException('Could not back up '.$path.'. No replacement was written.');
        }
        $this->line('Backup: '.$backup);
    }
}
