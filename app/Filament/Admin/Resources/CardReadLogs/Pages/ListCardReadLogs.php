<?php

namespace App\Filament\Admin\Resources\CardReadLogs\Pages;

use App\Filament\Admin\Resources\CardReadLogs\CardReadLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCardReadLogs extends ListRecords
{
    protected static string $resource = CardReadLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Read-only resource - no create action
        ];
    }
}
