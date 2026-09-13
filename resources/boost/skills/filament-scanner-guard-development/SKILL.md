---
name: filament-scanner-guard-development
description: Build and work with Filament Scanner Guard features — the ban-list resource, filters and unban actions.
---

# Filament Scanner Guard Development

## When to use this skill

Use this skill when:
- Registering the Scanner Guard Bans resource in a panel
- Reusing the unban actions on a custom table
- Customizing the resource's columns or filter

## Configuration

### Basic Setup

```php
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;

ScannerGuardPlugin::make();
```

The plugin registers a single resource, `ScannerGuardBanResource`, backed by `JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan` (from `jeffersongoncalves/laravel-scanner-guard`).

## Actions

### UnbanAction / UnbanBulkAction

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

Both actions **delete** the `ScannerGuardBan` record — there is no `unban()` method on the model (it's a permanent audit trail, not a flag on an arbitrary Eloquent model like `cybercog/laravel-ban`'s `Bannable`).

## Model

`ScannerGuardBan` has two reason constants: `REASON_SCANNER_PATH` and `REASON_ASN`, an `is_active` accessor (`expires_at->isFuture()`), and an `active()` scope (`expires_at > now()`). The table name is configurable via `config('scanner-guard.table')`.

## Troubleshooting

### Plugin not registered

**Cause**: Plugin not added to PanelProvider.

**Solution**: Add `ScannerGuardPlugin::make()` to the panel's `->plugins([])` array.

### "no such table: scanner_guard_bans"

**Cause**: `jeffersongoncalves/laravel-scanner-guard`'s migration hasn't been published/run.

**Solution**: Run `php artisan vendor:publish --tag=laravel-scanner-guard-migrations` then `php artisan migrate`.
