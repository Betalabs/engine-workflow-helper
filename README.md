# engine-workflow-helper

Package providing helpers for Engine Workflow creation (order, product, price group, access token and structure workflows, app dispatchers and workflow/step/condition creators).

## Requirements

| | Version |
|---|---|
| PHP | 8.2 to 8.5 |
| Laravel (`illuminate/support`, `illuminate/http`) | 10, 11 or 12 |
| betalabs/engine-phpsdk | 3.x |

Projects on PHP below 8.2, Laravel 9 or older, or engine-phpsdk 2.x must stay on version `2.x`.

## Installation

```bash
$ composer require betalabs/engine-workflow-helper
```

The package relies on the Laravel container, facades (`Log`, real-time facades) and `Illuminate\Support\Collection`, so it must be used inside a Laravel application.

## Upgrading to 3.x

- Requires PHP 8.2+, `betalabs/engine-phpsdk` 3.x and Laravel 10, 11 or 12 (now declared in `composer.json`; previously the Laravel dependency was implicit).
- No public API change: workflow classes, constants and the values sent to Engine are the same as in 2.x.

## Testing

```bash
$ composer update
$ vendor/bin/phpunit
```
