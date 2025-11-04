<?php

namespace App\Filament\Admin\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Adı Soyadı / Şirket Adı')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('E-posta Adresi')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefon')
                    ->tel()
                    ->maxLength(50),
                TextInput::make('company')
                    ->label('Şirket')
                    ->maxLength(255),
                Textarea::make('address')
                    ->label('Adres')
                    ->columnSpanFull()
                    ->rows(3),
                TextInput::make('city')
                    ->label('Şehir')
                    ->maxLength(100),
                TextInput::make('country')
                    ->label('Ülke')
                    ->maxLength(100),
                TextInput::make('postal_code')
                    ->label('Posta Kodu')
                    ->maxLength(20),
                Select::make('status')
                    ->label('Durum')
                    ->options([
                        'prospect' => 'Potansiyel Müşteri',
                        'lead' => 'Aday Müşteri',
                        'customer' => 'Müşteri',
                        'inactive' => 'Pasif',
                        'lost' => 'Kaybedildi',
                    ])
                    ->required()
                    ->default('prospect')
                    ->native(false),
                Textarea::make('notes')
                    ->label('Notlar')
                    ->columnSpanFull()
                    ->rows(4),
            ]);
    }
}
