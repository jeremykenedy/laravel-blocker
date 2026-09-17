<picture>
  <source media="(prefers-color-scheme: dark)" srcset="art/banner-dark.svg">
  <source media="(prefers-color-scheme: light)" srcset="art/banner-light.svg">
  <img alt="Laravel Blocker: access management for Laravel" src="art/banner-light.svg">
</picture>

# Laravel Blocker

[![Tests](https://github.com/jeremykenedy/laravel-blocker/actions/workflows/tests.yml/badge.svg)](https://github.com/jeremykenedy/laravel-blocker/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/jeremykenedy/laravel-blocker/v/stable.svg)](https://packagist.org/packages/jeremykenedy/laravel-blocker)
[![Total Downloads](https://poser.pugx.org/jeremykenedy/laravel-blocker/d/total.svg)](https://packagist.org/packages/jeremykenedy/laravel-blocker)
[![License](https://poser.pugx.org/jeremykenedy/laravel-blocker/license)](LICENSE)

Block IP addresses, email addresses, domains, users, cities, states, countries, continents, and regions. Manage entries through a Blade interface with search, soft deletion, restoration, and permanent deletion.

Bootstrap 4 remains the default. Bootstrap 3, Bootstrap 5, and Tailwind CSS are available. Composer updates do not switch frameworks, publish files, change your configuration, or run migrations.

- [Installation](#installation)
- [Frontend options](#frontend-options)
- [Middleware and authorization](#middleware-and-authorization)
- [Configuration](#configuration)
- [Optional packages](#optional-packages)
- [Updating](docs/upgrading.md)
- [Testing](docs/testing.md)
- [Changelog](CHANGELOG.md)

## Requirements

The package retains its PHP `^7.3|^8.0` requirement and existing runtime dependency ranges. The compatibility suite covers Laravel 5.8 through 13 using the matching PHP and Testbench versions. Historical compatibility does not extend Laravel's own security support period. Laravel 5.7 and earlier applications should keep their existing release, such as `v1.0.6`.

[spatie/laravel-html](https://github.com/spatie/laravel-html) and [eklundkristoffer/seedster](https://github.com/eklundkristoffer/seedster) remain runtime dependencies for existing forms and seed registration. None of the optional packages below is required.

## Installation

```sh
composer require jeremykenedy/laravel-blocker
php artisan blocker:install
```

The installer asks which CSS framework to use. It writes presentation settings to `config/laravelblocker-ui.php` and publishes the package configuration only when it is missing. Views run directly from the package unless you choose to publish them.

For an unattended installation that retains Bootstrap 4:

```sh
php artisan blocker:install --no-interaction
```

Before migrating, configure your database connection and user model. The defaults remain `mysql` and `App\User` for existing applications. A typical newer application uses:

```dotenv
LARAVEL_BLOCKER_DATABASE_CONNECTION=mysql
LARAVEL_BLOCKER_USER_MODEL="App\Models\User"
```

The `users` table must exist on the blocker connection before the package migration runs; the package's existing foreign keys reference that table. Migrations load automatically and do not need publishing.

```sh
php artisan migrate
php artisan db:seed --class='jeremykenedy\LaravelBlocker\Database\Seeders\DefaultBlockedTypeTableSeeder'
```

Optionally seed the existing sample blocked domains:

```sh
php artisan db:seed --class='jeremykenedy\LaravelBlocker\Database\Seeders\DefaultBlockedItemsTableSeeder'
```

These include `example.com`, `test.com`, and `mailinator.com`. Do not run the item seeder unless you want those domains blocked. Existing Seedster registration and configuration flags remain available; normal Composer updates never execute seeders.

## Frontend options

| Selection | Views | Assets supplied by your layout |
| --- | --- | --- |
| `bootstrap4` (default) | Existing Blade views | Bootstrap 4 and jQuery |
| `bootstrap3` | Existing Blade views | Bootstrap 3 and jQuery |
| `bootstrap5` | Modern Blade views | Bootstrap 5 CSS |
| `tailwind` | Modern Blade views | Your compiled Tailwind CSS |

```sh
php artisan blocker:install --framework=bootstrap5 --theme=system
php artisan blocker:update --framework=tailwind --theme=dark
php artisan blocker:update --framework=bootstrap4 --theme=light
```

Modern views use native forms and JavaScript, including confirmation dialogs for destructive actions. They provide search, pagination, inline validation feedback, and light, dark, and system appearance choices. The appearance selector stores the visitor's choice in local storage and affects only the Blocker interface. If storage is unavailable, the selector still works for the current page.

The legacy views support `--theme=dark` and `--theme=system` through scoped styles. Their existing jQuery, DataTables, tooltip, and CDN switches remain unchanged. DataTables applies only to legacy views; modern views use the configured server pagination.

All views extend `layouts.app` by default and use its `content` section. Customize `laravelBlockerBladeExtended` and the title placement in the package config. For legacy layouts, use distinct CSS and script sections to avoid loading jQuery or scripts twice:

```dotenv
LARAVEL_BLOCKER_BLADE_PLACEMENT_CSS=blocker_css
LARAVEL_BLOCKER_BLADE_PLACEMENT_JS=blocker_js
LARAVEL_BLOCKER_JQUERY_CDN_ENABLED=false
```

```blade
<head>
    {{-- Load your application's CSS here. --}}
    @yield('blocker_css')
</head>
<body>
    @yield('content')
    {{-- Load jQuery and Bootstrap before this section for legacy views. --}}
    @yield('blocker_js')
</body>
```

For Tailwind 4, add the package views to your application's CSS sources. Adjust the relative path for your CSS file:

```css
@source "../../vendor/jeremykenedy/laravel-blocker/src/resources/views/modern";
@source "../views/vendor/laravelblocker/modern";
```

For Tailwind 3, include those paths in `content` in `tailwind.config.js`. The package supplies scoped colors and layout styling; it does not modify the host application's theme or build pipeline.

## Middleware and authorization

```php
Route::middleware(['web', 'checkblocked'])->group(function () {
    Route::get('/account', [AccountController::class, 'show']);
});
```

The management routes keep their existing paths and `laravelblocker::` names. Visit `/blocker` for active items and `/blocker-deleted` for deleted items. Authentication is enabled by default. Restrict management to administrators using your application's role middleware:

```dotenv
LARAVEL_BLOCKER_AUTH_ENABLED=true
LARAVEL_BLOCKER_ROLES_ENABLED=true
LARAVEL_BLOCKER_ROLES_MIDDLWARE=role:admin
```

The historical spelling `rolesMiddlware` is retained. The package does not choose a roles implementation. Without roles enabled, any authenticated user can manage blocked entries, as in existing releases.

Blocking checks the request IP, available location details, and the authenticated user's email and domain. It also checks email and domain when posting to the `register` route URI. Deleted rules are ignored; restored and newly created rules take effect on subsequent requests, including long-running workers.

Location lookup retains the existing GeoPlugin URL, with a configurable two-second timeout. GeoPlugin now requires a paid plan; its [HTTPS endpoint](https://www.geoplugin.com/webservices/ssl) uses an account key. Set `LARAVEL_BLOCKER_GEOLOCATION_URL` to your working JSON endpoint, including its key if needed, and `LARAVEL_BLOCKER_GEOLOCATION_TIMEOUT` to adjust the timeout. The package appends the request IP to that URL and expects the existing `geoplugin_*` fields. It does not switch providers or purchase service automatically. An unavailable or invalid response supplies no location data, so IP and email checks continue. Configure trusted proxies in your application so Laravel resolves client IP addresses correctly.

Choose the blocked response with `blockerDefaultAction`: `abort`, `view`, or `redirect`. Registration blocks redirect back with an error. See the [configuration file](src/config/laravelblocker.php) for the existing response settings.

## Configuration

All existing configuration keys, environment variables, route names, model namespaces, facade binding, and publish tags remain available. The new settings are:

| Setting | Environment variable | Default |
| --- | --- | --- |
| `frontend` | `LARAVEL_BLOCKER_FRONTEND` | `legacy` |
| `theme` | `LARAVEL_BLOCKER_THEME` | `light` |

`frontend` accepts `legacy`, `bootstrap5`, or `tailwind`. The legacy setting continues to use `blockerBootstapVersion`, including its historical spelling, to select Bootstrap 3 or 4.

The setup commands save `frontend`, `theme`, and `blockerBootstapVersion` in `config/laravelblocker-ui.php`. That file takes precedence over the corresponding main config/environment settings. Remove it to return to environment-managed presentation settings. Commands clear configuration and compiled view caches; rebuild your config cache as part of deployment if needed.

Existing publish commands still work:

```sh
php artisan vendor:publish --tag=laravelblocker-config
php artisan vendor:publish --tag=laravelblocker-views
php artisan vendor:publish --tag=laravelblocker-lang
php artisan vendor:publish --tag=laravelblocker-migrations
php artisan vendor:publish --tag=laravelblocker-seeders
```

## Optional packages

[Laravel UI Kit](https://github.com/jeremykenedy/laravel-ui-kit) can be set up explicitly alongside Blocker's Blade views:

```sh
composer require jeremykenedy/laravel-ui-kit
php artisan blocker:install --framework=bootstrap5 --ui-kit
```

The `--ui-kit` option calls the installed package's `ui-kit:install` command with the selected CSS framework and Blade frontend. It does not run Composer or install dependencies on your behalf. UI Kit's own installation checks still apply. Bootstrap 3 is not supported by UI Kit. Blocker's views work without UI Kit and are not replaced with UI Kit components.

[Laravel Toast](https://github.com/jeremykenedy/laravel-toast), [Laravel Darkmode Toggle](https://github.com/jeremykenedy/laravel-darkmode-toggle), [Laravel IP Capture](https://github.com/jeremykenedy/laravel-ip-capture), and [Laravel Seedster](https://github.com/jeremykenedy/laravel-seedster) may be installed and configured independently in the host application. None is installed, enabled, or invoked automatically. The built-in themes and flash messages need no additional package. The optional Laravel Seedster package does not replace the existing `eklundkristoffer/seedster` dependency.

## License and contributors

[MIT](LICENSE), copyright 2020-2026 Jeremy Kenedy.

Maintained by [Jeremy Kenedy](https://github.com/jeremykenedy). Thanks to [all contributors](https://github.com/jeremykenedy/laravel-blocker/graphs/contributors).
