@php
    $row = 'position: relative; margin-top: .375rem;';
    $code = 'overflow-x: auto; white-space: pre-wrap; overflow-wrap: anywhere; margin: 0; padding: .625rem 5.5rem .625rem .75rem; border-radius: .5rem; border: 1px solid rgba(128, 128, 128, .35); background: rgba(128, 128, 128, .12); font-family: ui-monospace, monospace; font-size: .8125rem;';
    $button = 'position: absolute; top: .375rem; inset-inline-end: .375rem; padding: .125rem .5rem; border-radius: .375rem; border: 1px solid rgba(128, 128, 128, .5); font-size: .75rem;';
    $copy = __('filament-scanner-guard::default.agents.setup.copy');
    $copied = __('filament-scanner-guard::default.agents.setup.copied');
@endphp

{{-- wire:ignore: the steps are static, and a re-render must not drop the secret shown. --}}
<div
    wire:ignore
    x-data="{
        secret: '',
        copied: null,
        async copy(id, text) {
            await window.navigator.clipboard.writeText(text)
            this.copied = id
            setTimeout(() => (this.copied = null), 2000)
        },
    }"
    x-on:{{ \JeffersonGoncalves\Filament\ScannerGuard\Actions\SetupAgentAction::SECRET_EVENT }}.window="secret = $event.detail.secret"
>
    <ol style="display: grid; gap: 1rem; font-size: .875rem;">
        @foreach ($steps as $i => $step)
            <li>
                <strong>{{ $i + 1 }}. {{ $step['label'] }}</strong>

                <div style="{{ $row }}" dir="ltr">
                    <pre style="{{ $code }}" x-ref="step{{ $i }}">{{ $step['code'] }}</pre>
                    <button type="button" style="{{ $button }}" x-on:click="copy({{ $i }}, $refs.step{{ $i }}.textContent)" x-text="copied === {{ $i }} ? @js($copied) : @js($copy)">{{ $copy }}</button>
                </div>

                @if ($step['secret'])
                    <div style="{{ $row }}" dir="ltr" x-show="secret" x-cloak>
                        <pre style="{{ $code }}" x-text="secret"></pre>
                        <button type="button" style="{{ $button }}" x-on:click="copy('secret', secret)" x-text="copied === 'secret' ? @js($copied) : @js($copy)">{{ $copy }}</button>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>
</div>
