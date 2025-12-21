<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createApiToken')
                ->label('API Token Oluştur')
                ->icon('heroicon-o-key')
                ->color('success')
                ->form([
                    TextInput::make('token_name')
                        ->label('Token Adı')
                        ->default('crm-api')
                        ->required()
                        ->maxLength(255),
                ])
                ->action(function (array $data) {
                    $token = $this->record->createToken($data['token_name']);
                    $plainTextToken = $token->plainTextToken;

                    \Log::info('API Token oluşturuldu', [
                        'user_id' => $this->record->id,
                        'user_email' => $this->record->email,
                        'token_name' => $data['token_name'],
                        'token_id' => $token->accessToken->id,
                    ]);

                    $uniqueId = uniqid();

                    Notification::make()
                        ->title('API Token Oluşturuldu: '.$data['token_name'])
                        ->success()
                        ->icon('heroicon-o-key')
                        ->body('Token: '.$plainTextToken)
                        ->actions([
                            Action::make('copyToken')
                                ->label('Kopyala')
                                ->icon('heroicon-o-clipboard')
                                ->color('success')
                                ->dispatch(''),
                        ])
                        ->persistent()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('API Token Oluştur')
                ->modalDescription('Bu kullanıcı için yeni bir API token oluşturulacak. Token oluşturulduktan sonra ekranda gösterilecektir.')
                ->modalSubmitActionLabel('Token Oluştur'),

            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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
        $data['roles'] = $this->record->roles->pluck('id')->toArray();

        return $data;
    }
}
