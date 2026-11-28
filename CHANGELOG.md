# Changelog

All notable changes to this project will be documented in this file.

## 2.18.0 - 2026-11-27

- **OS and IP addresses on the Agents page:** two toggleable columns, filled by scanner-guard-agent 2.9.0+ (interface IPs, internal ones included). Requires laravel-scanner-guard 2.9.0 and its `add_host_to_scanner_guard_agents_table` migration; empty before. The public status page still never shows an IP. (#91)

## 2.17.0 - 2026-11-27

- **Optional nginx rules page:** turns `cms`, `rfi`, `encoded` or `empty-agent` on for every server running scanner-guard-agent 2.8.0+, with exceptions per server (e.g. one running WordPress). Only users who may edit a rule can toggle it.
- **Diagnoses page and Diagnose an IP action** (also on the Agents page): asks the chosen servers whether anything on them blocks an IP, with an optional packet capture of up to 120 seconds, and lists each server's status and verdict side by side, refreshed every 10 seconds. The full report opens in the view modal.

Requires laravel-scanner-guard 2.8.0 and its two new migrations; with an older core both pages stay hidden.

## 2.16.0 - 2026-11-26

- **Agent events page:** lists the events of report rules (e.g. every accepted SSH login) with server, rule, user, method, key and IP (with `store_details`), badges for new and ignored, filters, and the log line in the view modal.
- **Public status page** (off by default): `->publicStatusPage()` serves `/{panel}/scanner-guard-status` without login: servers online, blocked IPs, attacks and bans in the last 24 hours, one card per server with an hourly chart and its top rules. Counts only, cached for 60 seconds.

Requires laravel-scanner-guard 2.6.0 and its two new migrations.

## 2.15.0 - 2026-11-26

### Added

- **Agent rules** resource (core 2.5+): set the threshold, window and ban steps of a scanner-guard-agent rule once, for every server. Hidden without `http.server.enabled` or the core's `create_scanner_guard_agent_rules` migration.
- `->purgeExpiredBans(false)` hides the **Purge expired** action.

### Upgrading

Update `jeffersongoncalves/laravel-scanner-guard` to 2.5, re-publish its migrations and run `php artisan migrate`. Agents 2.5.0+ apply the rules.

## 2.14.0 - 2026-11-25

Same as 3.14.0, for Filament 4.

- **Log Lines** on the ban View page: the log lines a [scanner-guard-agent](https://github.com/jeffersongoncalves/scanner-guard-agent) sent with the ban, its IP replaced by `<ip>`. Escaped, monospace, copyable.
- Shown with `store_details` on, after the core's `add_evidence` migration ran, and only for bans that carry lines.
- Requires `jeffersongoncalves/laravel-scanner-guard` ^1.7 or ^2.4. On core 1.x nothing changes.
- `table.evidence` in all 19 languages.

## 2.13.0 - 2026-11-25

- With `store_details` on, the readable IP replaces the IP hash:
  
  - **Bans table:** the IP hash column is hidden. The IP column takes over the search, still by IP or by a part of the hash.
  - **View page:** shows the IP and hides the hash. A ban stored before details were on (or whose IP can no longer be decrypted) keeps showing its hash.
  - **Page title and breadcrumb:** show the IP instead of the hash, with the same fallback.
  
- Without `store_details` nothing changes.
  

## 2.12.0 - 2026-11-25

- **Bans by source** chart on the Metrics page: one slice per server or service that forwards bans (the satellite or agent `source`), plus **Local** for this app's own bans. Shown only on a central app that receives forwarded bans (http server enabled and the `add_source` migration ran).
- **Fix:** the **Bans by reason** chart was empty for bans forwarded by agents: it only counted five fixed reasons. It now charts every stored reason (`ssh_bruteforce`, `web_attack`, ...), ordered by total.
- **Fix:** an unknown reason showed the "Scanner path" badge in the table, view page and export. It now shows the reason as stored.
- New label translated in all 19 languages.

## 2.11.0 - 2026-11-25

- **Set up an agent** header action on the agents list: the steps to install and configure a [scanner-guard-agent](https://github.com/jeffersongoncalves/scanner-guard-agent), with copyable commands (this app's URL and your IP filled in).
  
- The same modal can show the signing secret, so nobody has to open the server's `.env`. Off by default:
  
  ```php
  ScannerGuardPlugin::make()->revealAgentSecret(fn (): bool => auth()->user()->is_admin); // your own check
  
  
  
  
  
  
  
  
  ```
  The user types their password (5 attempts a minute). The secret travels in a browser event only, never in the page, the Livewire state or the log, and each reveal is logged with the user and source. Allow only people you would give the `.env` to.
  
- **Never block** type in the blocklist: IPs and ranges no app, satellite or agent blocks or bans. Needs laravel-scanner-guard [2.3.0](https://github.com/jeffersongoncalves/laravel-scanner-guard/releases/tag/2.3.0), hidden with an older core. A blocked range that contains the IP still reaches the denylist sinks whole.
  
- New labels translated in all 19 languages.
  

## 2.8.0 - 2026-11-19

Show the **source** of forwarded bans on a central app of the core's http driver (`scanner-guard.http.server.enabled`, core 2.0 `add_source` migration): Source column and View page entry (null = Local), plus a Source filter listing every satellite seen and Local. Hidden until the migration ran. (#55)

## 2.7.0 - 2026-11-18

Supports jeffersongoncalves/laravel-scanner-guard 2.0 (constraint ^1.7 or ^2.0). The plugin only uses the public ban and unban API of the core, so nothing changes in the panel. See the core UPGRADE.md to move the nginx export to laravel-scanner-guard-nginx.

## 2.5.0 - 2026-11-02

### What's Changed

* refactor: use core 1.7 blocklist storage and unban() (2.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/45

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/2.4.0...2.5.0

## 2.4.0 - 2026-11-02

### What's Changed

* build(deps): bump stefanzweifel/git-auto-commit-action from 7.2.0 to 7.2.1 in the actions-deps group by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/37
* feat: manage static blocklist and bans from the panel (2.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/42

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/2.3.0...2.4.0

## 2.3.0 - 2026-10-28

### What's Changed

* ci: auto-merge dependabot github-actions minor/patch by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/28
* build(deps): bump shivammathur/setup-php from 2.37.2 to 2.40.0 in the actions-deps group by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/30
* feat: support laravel-scanner-guard 1.5 (2.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/34

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/2.2.0...2.3.0

## 2.2.0 - 2026-09-27

### What's Changed

* build(deps): bump the actions-deps group with 2 updates by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/15
* ci: standardize tests workflow (2.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/18
* ci: standardize tests workflow (2.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/21
* fix: keep bans-per-day chart history after purge/unban (2.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/25

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/2.1.0...2.2.0

## 2.1.0 - 2026-09-22

### What's new (Filament v4)

- **Export:** CSV/XLSX/PDF export as a header action and a bulk action, using `jeffersongoncalves/filament-action-export` `^2.7`. It runs synchronously, so no queue or migrations are needed. `matched_value` is exported in full, and values that start with a spreadsheet formula character are escaped. (#7)
- **Ban details:** a view page with an infolist, plus an **Extend** action (1 hour, 1 day, 1 week or 1 month) and a **Purge expired** header action. (#11)
- **Metrics charts:** bans per day for the last 14 days, bans by reason and top matched values. (#11)
- **Translations:** 17 new locales (ar, az, de, es, fa, fr, hi, it, ja, nl, pl, pt, ru, tr, uk, uz, zh_CN). (#11)

Thanks to @Elvin-Qulizade for the translations, charts and ban-details work.

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/2.0.1...2.1.0

## 2.0.1 - 2026-09-13

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/2.0.0...2.0.1

## 2.0.0 - 2026-09-13

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/commits/2.0.0

## [Unreleased]
