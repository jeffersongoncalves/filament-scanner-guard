<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentEvents\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentEvents\ScannerGuardAgentEventResource;

class ListScannerGuardAgentEvents extends ListRecords
{
    protected static string $resource = ScannerGuardAgentEventResource::class;
}
