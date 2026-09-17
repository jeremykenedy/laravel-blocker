# Testing

Install development dependencies, then run:

```sh
composer install
composer test
BLOCKER_PLAIN_CONTROLLER=1 composer test
composer lint
composer install --working-dir=tools
tools/vendor/bin/pint --test
composer validate --strict --no-check-lock
composer audit --locked
```

PHPUnit uses Orchestra Testbench with real routes, request validation, Eloquent models, migrations, seeders, and SQLite foreign keys. It tests CRUD, soft deletion and recovery, permanent and bulk deletion, search boundaries, escaped output, all frontend variants, middleware response modes, authentication and roles, configured database tables/connections, command backups, and presentation defaults. The second invocation uses the plain application controller found in newer Laravel skeletons.

The compatibility workflow runs Laravel 5.8 through 13 with matching PHP versions, including PHP 7.3 and PHP 8.5. Historical jobs allow Composer to resolve unsupported framework versions solely to test compatibility. The separate quality job audits current dependencies without this allowance. Compatibility tests are not a claim that unsupported Laravel or PHP versions receive security fixes.

## Browser suite

```sh
npm ci --prefix tests/browser
npm run build --prefix tests/browser
cd tests/browser
npx playwright install chromium
npm test
```

The harness serves a local Testbench application on `127.0.0.1:19746`. It uses a disposable SQLite database and real CSRF/session handling. It is development tooling and must not be exposed as an application endpoint.

Playwright tests Bootstrap 3/4 rendering and AJAX search, plus Bootstrap 5/Tailwind creation, editing, deletion confirmation, restoration, search, persistent themes, mobile overflow, and axe accessibility rules. Assets are local npm fixtures; tests do not depend on frontend CDNs. The browser workflow uploads traces and screenshots on failure.

The Bootstrap 3 test fixture is intentionally an old release to exercise the supported legacy option. Its upstream npm security advisories remain applicable to applications using that version. It is not a shipped runtime asset or a recommendation for a new application. Do not replace the fixture with Bootstrap 5, which would remove the intended compatibility coverage.

## Limits

Tests exercise package behavior in representative host applications. They cannot cover every application's published overrides or custom middleware. Middleware tests replace external location responses with deterministic fixtures; live GeoPlugin availability is not a CI requirement. Review your host application in staging before release.
