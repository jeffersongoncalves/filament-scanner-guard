<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables\ScannerGuardBansTable;

it('exports the full matched value and neutralises spreadsheet formulas', function () {
    $format = ScannerGuardBansTable::exportFormatStates()['matched_value'];
    $long = str_repeat('a', 60).'.php';

    expect($format(createBan(['matched_value' => $long])))->toBe($long)
        ->and($format(createBan(['matched_value' => '=cmd|"/c calc"!A1'])))->toBe('\'=cmd|"/c calc"!A1')
        ->and($format(createBan(['matched_value' => '@SUM(A1)'])))->toBe("'@SUM(A1)");
});

it('exports the active flag as a translated label', function () {
    $format = ScannerGuardBansTable::exportFormatStates()['is_active'];

    expect($format(createBan()))->toBe('Active')
        ->and($format(createBan(['expires_at' => now()->subDay()])))->toBe('Expired');
});
