<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions\Concerns;

use Carbon\CarbonInterval;
use Filament\Forms\Components\Select;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

/**
 * Filament v3 keeps table and page actions in separate class trees, so the
 * extend behaviour lives here and is shared by ExtendBanAction (table row)
 * and ExtendBanPageAction (view page header).
 */
trait ExtendsBan
{
    public static function getDefaultName(): ?string
    {
        return 'extend';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-scanner-guard::default.extend.label'))
            ->icon('heroicon-o-clock')
            ->color('warning')
            ->visible(fn (ScannerGuardBan $record): bool => $record->is_active)
            ->form([
                Select::make('duration')
                    ->label(__('filament-scanner-guard::default.extend.form.duration'))
                    ->options(static::durationOptions())
                    ->required()
                    ->native(false),
            ])
            ->fillForm(fn (ScannerGuardBan $record): array => ['duration' => static::suggestedDuration($record)])
            ->action(function (array $data, ScannerGuardBan $record): void {
                $base = $record->expires_at->isFuture() ? $record->expires_at : now();

                $record->update([
                    'expires_at' => $base->copy()->addSeconds((int) $data['duration']),
                ]);
            })
            ->successNotificationTitle(__('filament-scanner-guard::default.extend.success'));
    }

    /**
     * Fixed presets plus every escalation step, so the suggested step is always selectable.
     *
     * @return array<int, string>
     */
    public static function durationOptions(): array
    {
        $options = [
            3600 => __('filament-scanner-guard::default.extend.options.hour'),
            86400 => __('filament-scanner-guard::default.extend.options.day'),
            604800 => __('filament-scanner-guard::default.extend.options.week'),
            2592000 => __('filament-scanner-guard::default.extend.options.month'),
        ];

        foreach (ScannerGuardPlugin::escalation() as $seconds) {
            $options[$seconds] ??= CarbonInterval::seconds($seconds)->cascade()->forHumans();
        }

        ksort($options);

        return $options;
    }

    /**
     * The duration the IP's next offense would get (escalation on), or one day.
     */
    public static function suggestedDuration(ScannerGuardBan $record): int
    {
        $ladder = ScannerGuardPlugin::escalation();

        if ($ladder === []) {
            return 86400;
        }

        // offense is 1-based, so it is already the 0-based index of the next step.
        return $ladder[min($record->offense ?? 0, count($ladder) - 1)];
    }
}
