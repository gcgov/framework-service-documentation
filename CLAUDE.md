# CLAUDE.md — gcgov/framework-service-documentation

A **framework-service plugin** for `gcgov/framework`. Read the framework's own `CLAUDE.md` first for the
plugin/router/lifecycle model — this file only covers what this plugin adds. See `README.md` for prose.

## Purpose
Adds a single route, **`GET /documentation.yaml`**, that generates an **OpenAPI (Swagger) YAML** spec on demand
by scanning the app + framework source for OpenAPI annotations/attributes (via `zircote/swagger-php`). Point
Swagger UI at it for live, integrated API docs.

Namespace / PSR-4: `gcgov\framework\services\documentation\` → `src/`. Composer type: `framework-service`.
Depends on `zircote/swagger-php` (`^6.1`) and `doctrine/annotations`.

## Install & register
```php
// \app\app::registerFrameworkServiceNamespaces()
return [ '\gcgov\framework\services\documentation' ];
```
`composer require gcgov/framework-service-documentation`.

## Route added (`src/router.php`, prefixed with `environment.getBasePath()`)
| Method | Path | Controller method | Auth |
|--------|------|-------------------|------|
| GET | `/documentation.yaml` | `documentation::yaml` | no |

## How generation works (`src/controllers/documentation.php`)
- **Scan directories**: the app dir (`config::getAppDir()`) plus the framework's
  `vendor/gcgov/framework/src/{controllers,exceptions,models,services}` (each included only if it exists).
- **Excludes**: `vendor/` (so only first-party + framework source is scanned).
- Runs `new OpenApi\Generator()->generate( new OpenApi\SourceFinder($scan, $exclude, '*.php') )`, sets
  `Content-Type: text/x-yaml`, echoes `->toYaml()`, then **`die()`s**.
  > This deliberate `die()` is the one sanctioned exception to the framework's "always return a
  > `controllerResponse`, never exit" rule — it streams raw YAML directly.

## Writing docs that appear in the output
Annotate controllers/models with swagger-php annotations or attributes:
- Class/route annotations like `@OA\Tag`, `@OA\Get`, `@OA\Post`, `@OA\Parameter`, `@OA\RequestBody`,
  `@OA\Response`, `@OA\JsonContent(ref="#/components/schemas/{model}")`.
- Model schemas via `@OA\Schema` / `@OA\Property` on model classes (the user-crud plugin's `user` controller
  and the oauth-server's `stdAuthResponse` are good live examples).

The richer your `@OA\*` coverage across `\app` and the framework, the more complete `/documentation.yaml` is.

## When editing this plugin
- Lowercase class/file names. If you change the scan set, keep excluding `vendor/` and guard each path with a
  `file_exists()` check (framework internals differ between path-repo and Packagist installs).
- `composer ci` (phpstan + phpunit) before pushing.
