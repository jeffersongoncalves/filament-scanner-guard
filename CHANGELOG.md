# Changelog

All notable changes to this project will be documented in this file.

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
