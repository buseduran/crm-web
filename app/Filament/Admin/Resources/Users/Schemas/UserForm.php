<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(label: 'Adı Soyadı')
                    ->required(),
                TextInput::make('email')
                    ->label('E-posta Adresi')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->visibleOn('create'),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->visibleOn('edit'),
                TextInput::make('card_string')
                    ->label('Kart String')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('roles')
                    ->label('Roller')
                    ->multiple()
                    ->options(Role::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
            ]);
    }
}
