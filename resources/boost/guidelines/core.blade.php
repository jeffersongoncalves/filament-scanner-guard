## Filament Scanner Guard

Filament UI for `jeffersongoncalves/laravel-scanner-guard`: list, filter and unban vulnerability-scanner IPs from your admin panel.

### Installation

@verbatim
<code-snippet name="Install the plugin" lang="bash">
composer require jeffersongoncalves/filament-scanner-guard
</code-snippet>
@endverbatim

Requires `jeffersongoncalves/laravel-scanner-guard` to already be installed and migrated — it owns the `scanner_guard_bans` table, config and middleware.

### Registering in the Panel

@verbatim
<code-snippet name="Register in PanelProvider" lang="php">
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ScannerGuardPlugin::make(),
        ]);
}
</code-snippet>
@endverbatim

### What it ships

- **ScannerGuardBanResource** — read-only list of `scanner_guard_bans` rows (no create/edit — rows are written by the `scanner-guard` middleware).
- Columns: `ip_hash`, `reason` (badge), `matched_value`, `hit_count`, `banned_at`, `expires_at`, `is_active`.
- A **Status** ternary filter (active vs expired, based on `expires_at`).
- **UnbanAction** / **UnbanBulkAction** — delete the ban row(s). There's no "unban" flag to flip: this is an audit-trail model, so unbanning means deleting the row.

### Best Practices

- Do not try to create/edit ban rows through this resource — it's intentionally list-only.
- If you need the actions on a custom table, import `JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction` / `UnbanBulkAction` directly instead of registering the whole plugin.
