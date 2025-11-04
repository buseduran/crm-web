<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove roles from data array as it will be handled separately
        unset($data['roles']);

        return $data;
    }

    protected function afterSave(): void
    {
        $roleIds = $this->form->getState()['roles'] ?? [];
        $this->record->syncRoles($roleIds);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing roles for the user
        $data['roles'] = $this->record->roles->pluck('id')->toArray();

        return $data;
    }
}
