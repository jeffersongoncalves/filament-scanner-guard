@php
    // Inline styles: the panel's compiled CSS has no classes for a plugin-only layout.
    $bar = 'rgb(var(--primary-500))';
    $grid = 'display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr));';
    $label = 'text-sm font-medium text-gray-500 dark:text-gray-400';
    $value = 'text-3xl font-semibold tracking-tight text-gray-950 dark:text-white';
@endphp

<x-filament-panels::page.simple>
    <div wire:poll.60s style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="{{ $grid }}">
            @foreach ([
                'servers_online' => $online . ' / ' . count($servers),
                'blocked_now' => number_format($blocked),
                'attacks_24h' => number_format($attacks),
                'bans_24h' => number_format($bans),
            ] as $key => $stat)
                <x-filament::section>
                    <div class="{{ $label }}">{{ __('filament-scanner-guard::default.status.' . $key) }}</div>
                    <div class="{{ $value }}">{{ $stat }}</div>
                </x-filament::section>
            @endforeach
        </div>

        @if ($servers === [])
            <p class="{{ $label }}">{{ __('filament-scanner-guard::default.status.no_servers') }}</p>
        @else
            <div style="{{ $grid }}">
                @foreach ($servers as $server)
                    <x-filament::section>
                        <x-slot name="heading">{{ $server['source'] }}</x-slot>
                        <x-slot name="headerEnd">
                            <x-filament::badge :color="$server['online'] ? 'success' : 'danger'">
                                {{ __('filament-scanner-guard::default.agents.' . ($server['online'] ? 'online' : 'offline')) }}
                            </x-filament::badge>
                        </x-slot>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <div class="{{ $label }}">
                                {{ __('filament-scanner-guard::default.agents.version') }}: {{ $server['version'] ?? '—' }}
                                · {{ __('filament-scanner-guard::default.agents.last_seen_at') }}: {{ $server['last_seen'] }}
                            </div>

                            <div style="display: flex; gap: 1.5rem;">
                                <div>
                                    <div class="{{ $label }}">{{ __('filament-scanner-guard::default.agents.active_bans') }}</div>
                                    <div class="text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($server['active_bans']) }}</div>
                                </div>
                                <div>
                                    <div class="{{ $label }}">{{ __('filament-scanner-guard::default.status.attacks_24h') }}</div>
                                    <div class="text-xl font-semibold text-gray-950 dark:text-white">{{ number_format($server['attacks']) }}</div>
                                </div>
                            </div>

                            @php($peak = max(1, ...$server['hourly']))
                            <div style="display: flex; align-items: flex-end; gap: 2px; height: 2.5rem;">
                                @foreach ($server['hourly'] as $count)
                                    <div title="{{ $count }}" style="flex: 1; min-height: 1px; height: {{ round($count / $peak * 100) }}%; background: {{ $bar }}; border-radius: 1px;"></div>
                                @endforeach
                            </div>

                            @if ($server['top_rules'] !== [])
                                <div>
                                    <div class="{{ $label }}">{{ __('filament-scanner-guard::default.status.top_rules') }}</div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; margin-top: 0.25rem;">
                                        @foreach ($server['top_rules'] as $rule => $matches)
                                            <x-filament::badge color="gray">{{ $rule }}: {{ number_format($matches) }}</x-filament::badge>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </x-filament::section>
                @endforeach
            </div>
        @endif

        <x-filament::section :heading="__('filament-scanner-guard::default.status.bans_by_reason')">
            @forelse ($bansByReason as $reason => $total)
                <div style="display: flex; justify-content: space-between;" class="text-sm text-gray-950 dark:text-white">
                    <span>{{ $reason }}</span>
                    <span>{{ number_format($total) }}</span>
                </div>
            @empty
                <p class="{{ $label }}">{{ __('filament-scanner-guard::default.status.no_bans') }}</p>
            @endforelse
        </x-filament::section>
    </div>
</x-filament-panels::page.simple>
