# Changelog

All notable changes to this project will be documented in this file.

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
