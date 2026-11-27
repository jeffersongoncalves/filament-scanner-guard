<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentDiagnosis;

/**
 * Asks the chosen servers' agents whether anything on them blocks an IP
 * (core 2.8+, scanner-guard-agent 2.8+). Each one answers within a pull.
 */
class DiagnoseIpAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'diagnoseIp';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-scanner-guard::default.diagnoses.request.label'))
            ->icon('heroicon-o-magnifying-glass')
            ->modalDescription(__('filament-scanner-guard::default.diagnoses.request.description'))
            ->visible(fn (): bool => ScannerGuardPlugin::receivesAgentDiagnoses())
            ->form([
                TextInput::make('ip')
                    ->label(__('filament-scanner-guard::default.diagnoses.ip'))
                    ->required()
                    ->ip(),
                Select::make('sources')
                    ->label(__('filament-scanner-guard::default.diagnoses.request.sources'))
                    ->multiple()
                    ->required()
                    ->options(fn (): array => ScannerGuardAgent::query()->orderBy('source')->pluck('source', 'source')->all())
                    ->default(fn (): array => ScannerGuardAgent::query()->scopes('online')->orderBy('source')->pluck('source')->all()),
                TextInput::make('capture')
                    ->label(__('filament-scanner-guard::default.diagnoses.request.capture'))
                    ->helperText(__('filament-scanner-guard::default.diagnoses.request.capture_help'))
                    ->integer()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(ScannerGuardAgentDiagnosis::MAX_CAPTURE),
            ])
            ->action(function (array $data): void {
                $sent = ScannerGuard::requestDiagnosis((string) $data['ip'], array_values((array) $data['sources']), (int) ($data['capture'] ?? 0));

                Notification::make()
                    ->title(__('filament-scanner-guard::default.diagnoses.request.sent', ['count' => $sent->count()]))
                    ->success()
                    ->send();
            });
    }
}
