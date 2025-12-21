<?php

namespace App\Filament\Admin\Resources\CardReadLogs\Pages;

use App\Filament\Admin\Resources\CardReadLogs\CardReadLogResource;
use Filament\Resources\Pages\ListRecords;

class ListCardReadLogs extends ListRecords
{
    protected static string $resource = CardReadLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
