<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Exports\ScannerGuardBanExporter;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

it('targets the ban model', function () {
    expect(ScannerGuardBanExporter::getModel())->toBe(ScannerGuardBan::class);
});

it('defines all ban columns', function () {
    $names = collect(ScannerGuardBanExporter::getColumns())
        ->map(fn ($column) => $column->getName())
        ->all();

    expect($names)->toBe([
        'ip_hash',
        'reason',
        'matched_value',
        'hit_count',
        'banned_at',
        'expires_at',
        'is_active',
    ]);
});

it('offers both csv and xlsx formats by default', function () {
    $exporter = new ScannerGuardBanExporter(
        export: new \Filament\Actions\Exports\Models\Export,
        columnMap: [],
        options: [],
    );

    expect($exporter->getFormats())->toHaveCount(2);
});
