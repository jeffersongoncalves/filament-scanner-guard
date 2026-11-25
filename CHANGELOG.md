# Changelog

All notable changes to this project will be documented in this file.

## 1.12.0 - 2026-11-25

- **Bans by source** chart on the Metrics page: one slice per server or service that forwards bans (the satellite or agent `source`), plus **Local** for this app's own bans. Shown only on a central app that receives forwarded bans (http server enabled and the `add_source` migration ran).
- **Fix:** the **Bans by reason** chart was empty for bans forwarded by agents: it only counted five fixed reasons. It now charts every stored reason (`ssh_bruteforce`, `web_attack`, ...), ordered by total.
- **Fix:** an unknown reason showed the "Scanner path" badge in the table, view page and export. It now shows the reason as stored.
- New label translated in all 19 languages.

## 1.11.0 - 2026-11-25

- **Set up an agent** header action on the agents list: the steps to install and configure a [scanner-guard-agent](https://github.com/jeffersongoncalves/scanner-guard-agent), with copyable commands (this app's URL and your IP filled in).
  
- The same modal can show the signing secret, so nobody has to open the server's `.env`. Off by default:
  
  ```php
  ScannerGuardPlugin::make()->revealAgentSecret(fn (): bool => auth()->user()->is_admin); // your own check
  
  
  ```
  The user types their password (5 attempts a minute). The secret travels in a browser event only, never in the page, the Livewire state or the log, and each reveal is logged with the user and source. Allow only people you would give the `.env` to.
  
- **Never block** type in the blocklist: IPs and ranges no app, satellite or agent blocks or bans. Needs laravel-scanner-guard [2.3.0](https://github.com/jeffersongoncalves/laravel-scanner-guard/releases/tag/2.3.0), hidden with an older core. A blocked range that contains the IP still reaches the denylist sinks whole.
  
- New labels translated in all 19 languages.
  

## 1.8.0 - 2026-11-19

Show the **source** of forwarded bans on a central app of the core's http driver (`scanner-guard.http.server.enabled`, core 2.0 `add_source` migration): Source column and View page entry (null = Local), plus a Source filter listing every satellite seen and Local. Hidden until the migration ran. (#56)

## 1.7.0 - 2026-11-18

Supports jeffersongoncalves/laravel-scanner-guard 2.0 (constraint ^1.7 or ^2.0). The plugin only uses the public ban and unban API of the core, so nothing changes in the panel. See the core UPGRADE.md to move the nginx export to laravel-scanner-guard-nginx.

## 1.5.0 - 2026-11-02

### What's Changed

* refactor: use core 1.7 blocklist storage and unban() (1.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/46

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/1.4.0...1.5.0

## 1.4.0 - 2026-11-02

### What's Changed

* build(deps): bump stefanzweifel/git-auto-commit-action from 7.2.0 to 7.2.1 in the actions-deps group by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/39
* feat: manage static blocklist and bans from the panel (1.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/43

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/1.3.0...1.4.0

## 1.3.0 - 2026-10-28

### What's Changed

* ci: auto-merge dependabot github-actions minor/patch by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/27
* build(deps): bump shivammathur/setup-php from 2.37.2 to 2.40.0 in the actions-deps group by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/32
* feat: support laravel-scanner-guard 1.5 (1.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/35

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/1.2.0...1.3.0

## 1.2.0 - 2026-09-27

### What's Changed

* build(deps): bump the actions-deps group with 2 updates by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/14
* build(deps-dev): update orchestra/testbench requirement from ^10.0 to ^10.0 || ^11.0 in the composer-deps group by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/16
* ci: standardize tests workflow (1.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/17
* ci: standardize tests workflow (1.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/20
* fix: keep bans-per-day chart history after purge/unban (1.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-scanner-guard/pull/26

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/1.1.0...1.2.0

## 1.1.0 - 2026-09-22

### What's new (Filament v3)

- **Export:** CSV/XLSX/PDF export as a header action and a bulk action, using `jeffersongoncalves/filament-action-export` `^1.6.1`. It runs synchronously, so no queue or migrations are needed. `matched_value` is exported in full, and values that start with a spreadsheet formula character are escaped. (#9)
- **Ban details:** a view page with an infolist, plus an **Extend** action (1 hour, 1 day, 1 week or 1 month) and a **Purge expired** header action. (#12)
- **Metrics charts:** bans per day for the last 14 days, bans by reason and top matched values. (#12)
- **Translations:** 17 new locales (ar, az, de, es, fa, fr, hi, it, ja, nl, pl, pt, ru, tr, uk, uz, zh_CN). (#12)

### Fixes

- The Metrics page crashed on Filament v3 because it had no view. (#12)

Thanks to @Elvin-Qulizade for the translations, charts and ban-details work.

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/1.0.1...1.1.0

## 1.0.1 - 2026-09-13

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/compare/1.0.0...1.0.1

## 1.0.0 - 2026-09-13

**Full Changelog**: https://github.com/jeffersongoncalves/filament-scanner-guard/commits/1.0.0

## [Unreleased]
