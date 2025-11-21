<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('monthly')
                ->label('Aylık Görünüm')
                ->icon('heroicon-o-calendar')
                ->url(fn () => AttendanceResource::getUrl('monthly')),
            CreateAction::make(),
        ];
    }
}
