<?php

namespace App\Filament\Admin\Resources\Activities\Pages;

use App\Filament\Admin\Resources\Activities\ActivityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateActivity extends CreateRecord
{
    protected static string $resource = ActivityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Polimorfik ilişki için activityable_type ve activityable_id zaten form'da var
        // Burada ekstra bir işlem yapmamıza gerek yok
        
        return $data;
    }
}
