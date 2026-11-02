<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\Filament\ScannerGuard\Support\Denylist;

/**
 * A static blocklist entry managed from the panel. Merged into the core's
 * `ip_blocklist` / `user_agent_blocklist.patterns` config by Denylist.
 *
 * @property int $id
 * @property string $type
 * @property string $value
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ScannerGuardBlocklistEntry extends Model
{
    public const TYPE_IP = 'ip';

    public const TYPE_USER_AGENT = 'user_agent';

    protected $table = 'scanner_guard_blocklist';

    protected $fillable = [
        'type',
        'value',
        'note',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Denylist::refresh());
        static::deleted(fn () => Denylist::refresh());
    }
}
