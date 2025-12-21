<?php

namespace App\Filament\Admin\Resources\Activities\Pages;

use App\Filament\Admin\Resources\Activities\ActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->load('activityable');

        if ($this->record->activityable_type) {
            $data['activityable_type'] = $this->record->activityable_type;
            $data['activityable_id'] = $this->record->activityable_id;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
