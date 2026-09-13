<div class="filament-hidden">

![Filament Scanner Guard](https://raw.githubusercontent.com/jeffersongoncalves/filament-scanner-guard/2.x/art/jeffersongoncalves-filament-scanner-guard.png)

</div>

# Filament Scanner Guard

[![Buy Me A Coffee](https://img.shields.io/badge/Buy%20Me%20A%20Coffee-support-FFDD00?style=flat-square&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/jeffersongoncalves)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-scanner-guard.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-scanner-guard)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-scanner-guard/tests.yml?branch=2.x&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/filament-scanner-guard/actions?query=workflow%3ATests+branch%3A2.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-scanner-guard/pint.yml?branch=2.x&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/filament-scanner-guard/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3A2.x)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-scanner-guard.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-scanner-guard)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-scanner-guard.svg?style=flat-square)](LICENSE.md)

Filament UI for [jeffersongoncalves/laravel-scanner-guard](https://github.com/jeffersongoncalves/laravel-scanner-guard): list, filter and unban vulnerability-scanner IPs directly from your admin panel. This plugin ships a read-only **Resource** over the `scanner_guard_bans` table, an **active/expired filter**, and **unban** / **bulk unban** actions.

## Version Compatibility

| Filament | Laravel | PHP  | Branch | Version |
|----------|---------|------|--------|---------|
| v3       | 12      | 8.3+ | `1.x`  | `^1.0`  |
| v4       | 12 / 13 | 8.3+ | `2.x`  | `^2.0`  |
| v5       | 12 / 13 | 8.3+ | `3.x`  | `^3.0`  |

All tags use plain SemVer **without** the `v` prefix (e.g. `2.0.0`).

> **Note:** every branch requires PHP 8.3+ and Laravel 12+ because the underlying `jeffersongoncalves/laravel-scanner-guard` (via `jeffersongoncalves/laravel-visitor-fingerprint`) only supports Laravel 12/13.

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/filament-scanner-guard:"^2.0"
```

This plugin depends on `jeffersongoncalves/laravel-scanner-guard`, which owns the `scanner_guard_bans` table, config, middleware and export command. Follow its [installation instructions](https://github.com/jeffersongoncalves/laravel-scanner-guard) first (publish config, run the migration, attach the `scanner-guard` middleware to your routes).

## Usage

Register the plugin in your Panel Provider:

```php
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ScannerGuardPlugin::make(),
        ]);
}
```

This registers a **Scanner Guard Bans** resource (list only — rows are written by the `scanner-guard` middleware, not by admins) with:

- Columns: `ip_hash`, `reason` (badge), `matched_value`, `hit_count`, `banned_at`, `expires_at`, `is_active`
- A **Status** filter (active vs expired)
- An **Unban** row action and an **Unban selected** bulk action — both delete the ban row (this is an audit-trail model, not a flag on an arbitrary model)

### Using the actions standalone

If you'd rather wire the table yourself:

```php
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanBulkAction;

$table
    ->recordActions([
        UnbanAction::make(),
    ])
    ->toolbarActions([
        UnbanBulkAction::make(),
    ]);
```

## Development

```bash
# Run static analysis
composer analyse

# Run tests
composer test

# Format code
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jèfferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
