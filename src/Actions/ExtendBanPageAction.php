<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Actions\Action;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\Concerns\ExtendsBan;

class ExtendBanPageAction extends Action
{
    use ExtendsBan;
}
