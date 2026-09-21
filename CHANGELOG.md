# Changelog

## Unreleased

### Added

- Optional Bootstrap 5 and Tailwind Blade interfaces with responsive layouts and light, dark, and system themes.
- `blocker:install` and `blocker:update`, explicit framework selection, optional view publishing, and backups before view replacement.
- Optional delegation to an installed Laravel UI Kit setup command.
- Laravel compatibility, browser, accessibility, formatting, and dependency audit checks.
- Theme-aware README artwork and upgrade/testing documentation.

### Fixed

- Configuration loads before controller binding and routes load once after registration.
- Authentication and role middleware work with newer application controller skeletons.
- Seeder namespaces resolve on case-sensitive filesystems.
- Validation honors configured tables and database connections and rejects missing types and users.
- Registration blocks return their redirect response; rule changes are observed across requests.
- Active/deleted searches stay isolated; blocked-type relationships use the existing foreign key.
- Legacy views and search escape item content, and labels identify their inputs.
- Location requests support a configurable endpoint and timeout, with invalid responses handled safely. The existing endpoint remains the default.

### Compatibility

- Retained PHP and runtime dependency ranges, Bootstrap 4 default, Bootstrap 3 option, existing view namespaces, route names, publish tags, and config keys.
- Modern scripts and styles are served as separate package assets.
- Existing published views and configuration are not overwritten by Composer updates.
- No schema changes or automatic seeding.
- License year updated to 2026.
