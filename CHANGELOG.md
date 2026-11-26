# Changelog

All notable changes to this project will be documented in this file.

## 3.15.0 - 2026-11-26

### Added

- **Agent rules** resource (core 2.5+): set the threshold, window and ban steps of a scanner-guard-agent rule once, for every server. Hidden without `http.server.enabled` or the core's `create_scanner_guard_agent_rules` migration.
- `->purgeExpiredBans(false)` hides the **Purge expired** action.

### Upgrading

Update `jeffersongoncalves/laravel-scanner-guard` to 2.5, re-publish its migrations and run `php artisan migrate`. Agents 2.5.0+ apply the rules.

## 3.14.0 - 2026-11-25

- **Log Lines** on the ban View page: the log lines a [scanner-guard-agent](https://github.com/jeffersongoncalves/scanner-guard-agent) sent with the ban, its IP replaced by `<ip>`, so you can see what the IP actually tried. Monospace, one line per item, copyable, escaped (the lines come from the attacker).
- Shown with `store_details` on, after the core's `add_evidence` migration ran, and only for bans that carry lines. A lost key hides them instead of breaking the page.
- Requires `jeffersongoncalves/laravel-scanner-guard` ^1.7 or ^2.4 (2.4 adds the migration). On core 1.x nothing changes.
- `table.evidence` in all 19 languages.

## 3.13.0 - 2026-11-25

- With `store_details` on, the readable IP replaces the IP hash:
  
  - **Bans table:** the IP hash column is hidden. The IP column takes over the search, still by IP or by a part of the hash.
  - **View page:** shows the IP and hides the hash. A ban stored before details were on (or whose IP can no longer be decrypted) keeps showing its hash.
  - **Page title and breadcrumb:** show the IP instead of the hash, with the same fallback.
  
- Without `store_details` nothing changes.
  

## 3.12.0 - 2026-11-25

- **Bans by source** chart on the Metrics page: one slice per server or service that forwards bans (the satellite or agent `source`), plus **Local** for this app's own bans. Shown only on a central app that receives forwarded bans (http server enabled and the `add_source` migration ran).
- **Fix:** the **Bans by reason** chart was empty for bans forwarded by agents: it only counted five fixed reasons. It now charts every stored reason (`ssh_bruteforce`, `web_attack`, ...), ordered by total.
- **Fix:** an unknown reason showed the "Scanner path" badge in the table, view page and export. It now shows the reason as stored.
- New label translated in all 19 languages.

## 3.11.0 - 2026-11-25

- **Set up an agent** header action on the agents list: the steps to install and configure a [scanner-guard-agent](https://github.com/jeffersongoncalves/scanner-guard-agent), with copyable commands (this app's URL and your IP filled in).
  
- The same modal can show the signing secret, so nobody has to open the server's `.env`. Off by default:
  
  ```php
  ScannerGuardPlugin::make()->revealAgentSecret(fn (): bool => auth()->user()->is_admin); // your own check
  
  
  
  
  
  ```
  The user types their password (5 attempts a minute). The secret travels in a browser event only, never in the page, the Livewire state or the log, and each reveal is logged with the user and source. Allow only people you would give the `.env` to.
  
- **Never block** type in the blocklist: IPs and ranges no app, satellite or agent blocks or bans. Needs laravel-scanner-guard [2.3.0](https://github.com/jeffersongoncalves/laravel-scanner-guard/releases/tag/2.3.0), hidden with an older core. A blocked range that contains the IP still reaches the denylist sinks whole.
  
- New labels translated in all 19 languages.
  

## 3.8.0 - 2026-11-19

Show the **source** of forwarded bans on a central app of the core's http driver (`scanner-guard.http.server.enabled`, core 2.0 `add_source` migration): Source column and View page entry (null = Local), plus a Source filter listing every satellite seen and Local. Hidden until the migration ran. (#54)

## 3.7.0 - 2026-11-18

Supports jeffersongoncalves/laravel-scanner-guard 2.0 (constraint ^1.7 or ^2.0). The plugin only uses the public ban and unban API of the core, so nothing changes in the panel. See the core UPGRADE.md to move the nginx export to laravel-scanner-guard-nginx.

## 3.5.0 - 2026-11-02

### What's Changed

* refactor: use core 1.7 blocklist storage and unban() (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/44

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/3.4.0...3.5.0

## 3.4.0 - 2026-11-02

### What's Changed

* Auto-assign issues and pull requests by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/36
* build(deps): bump stefanzweifel/git-auto-commit-action from 7.2.0 to 7.2.1 in the actions-deps group by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/38
* feat: manage static blocklist and bans from the panel (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/41

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/3.3.0...3.4.0

## 3.3.0 - 2026-10-28

### What's Changed

* ci: auto-merge dependabot github-actions minor/patch by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/29
* build(deps): bump shivammathur/setup-php from 2.37.2 to 2.40.0 in the actions-deps group by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/31
* feat: support laravel-scanner-guard 1.5 (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/33

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/3.2.0...3.3.0

## 3.2.0 - 2026-09-27

### What's Changed

* ci: standardize dependabot config by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/13
* ci: standardize tests workflow (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/19
* ci: standardize tests workflow (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/22
* fix: keep bans-per-day chart history after purge/unban (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/24

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/3.1.0...3.2.0

## 3.1.0 - 2026-09-22

### What's new (Filament v5)

- **Export:** CSV/XLSX/PDF export as a header action and a bulk action, using `jeffersongoncalves/filament-action-export` `^3.7`. It runs synchronously, so no queue or migrations are needed. `matched_value` is exported in full, and values that start with a spreadsheet formula character are escaped. (#6)
- **Ban details:** a view page with an infolist, plus an **Extend** action (1 hour, 1 day, 1 week or 1 month) and a **Purge expired** header action. (#10)
- **Metrics charts:** bans per day for the last 14 days, bans by reason and top matched values. (#10)
- **Translations:** 17 new locales (ar, az, de, es, fa, fr, hi, it, ja, nl, pl, pt, ru, tr, uk, uz, zh_CN). (#2, #8)

Thanks to @Elvin-Qulizade for the translations, charts and ban-details work.

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/3.0.1...3.1.0

## 3.0.1 - 2026-09-13

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/3.0.0...3.0.1

## 3.0.0 - 2026-09-13

### What's Changed

* build(deps): bump the all-actions group with 2 updates by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/1

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/1

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/commits/3.0.0

## [Unreleased]
