# Updating an existing installation

Commit or back up your application's published views and configuration before changing them. Test the update against your application's authentication, role middleware, user model, database connection, and parent layout.

```sh
composer update jeremykenedy/laravel-blocker --with-dependencies
```

This leaves the existing frontend selection and published files alone. No new database schema or data migration is introduced. Bootstrap 4 remains the package default; existing Bootstrap 3 configuration is honored.

## Switching frameworks

```sh
php artisan blocker:update --framework=bootstrap5 --theme=system
```

The command writes only the presentation profile and any missing main config file. Modern views live under `laravelblocker::modern`; existing views stay under `laravelblocker::laravelblocker`. Previously published modern views continue to override their package counterparts. Switching back restores use of your existing legacy overrides:

```sh
php artisan blocker:update --framework=bootstrap4
```

Use `--views` to publish missing views. Existing files are preserved. To replace them explicitly:

```sh
php artisan blocker:update --views --force
```

Before replacement, the command copies the existing view directory to a sibling named `laravelblocker.backup-<timestamp>-<random suffix>`. Existing presentation profiles are also backed up before rewriting. If a backup fails, replacement stops. `--force` applies only to views and does not overwrite the main configuration, translations, migrations, or seeders. Without `--views`, it does not replace views.

Commands do not migrate, seed, rewrite `.env`, or change application assets. They clear config and view caches. Review the profile and rebuild the application's caches during deployment.

## Fixes to review in published overrides

Package updates cannot patch copies already published into your application. Compare your overrides with the package versions, particularly:

- Escaped item values, notes, and modal attributes prevent stored HTML from executing.
- Legacy form labels now point to the correct input IDs.
- Legacy search escapes returned text before rendering it.
- Dark styles are scoped to the Blocker wrapper.

The legacy search endpoints retain their existing response shape: a JSON array containing one JSON-encoded results string. Modern search uses GET parameters on the existing listing routes. Bulk restore and permanent deletion still affect all deleted items, even when a page or search displays only a subset.

The validation fixes reject nonexistent types and user IDs instead of reaching foreign-key errors. Duplicate values, including soft-deleted values, remain reserved by the existing unique constraint. Existing email validation semantics are retained.

The package continues extending `App\Http\Controllers\Controller`. Both traditional Laravel controllers and modern application controllers without a `middleware()` method are supported; route middleware protects the management endpoints in either case.
