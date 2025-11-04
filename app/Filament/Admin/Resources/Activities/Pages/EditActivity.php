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
        // Load activityable relationship
        $this->record->load('activityable');
        
        // Ensure activityable_type and activityable_id are set correctly
        if ($this->record->activityable_type) {
            $data['activityable_type'] = $this->record->activityable_type;
            $data['activityable_id'] = $this->record->activityable_id;
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Polimorfik ilişki için activityable_type ve activityable_id zaten form'da var
        // Burada ekstra bir işlem yapmamıza gerek yok
        
        return $data;
    }
}
