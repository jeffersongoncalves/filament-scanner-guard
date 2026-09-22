<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Tables\Actions\Action;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\Concerns\ExtendsBan;

class ExtendBanAction extends Action
{
    use ExtendsBan;
}
