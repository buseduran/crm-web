<?php

namespace App\Filament\Admin\Resources\Opportunities\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OpportunityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('Müşteri')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Başlık')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Açıklama')
                    ->columnSpanFull()
                    ->rows(4),
                TextInput::make('value')
                    ->label('Değer')
                    ->numeric()
                    ->prefix('₺')
                    ->step(0.01),
                Select::make('stage')
                    ->label('Aşama')
                    ->options([
                        'prospecting' => 'Potansiyel Müşteri',
                        'qualification' => 'Nitelendirme',
                        'proposal' => 'Teklif',
                        'negotiation' => 'Müzakereler',
                        'closed-won' => 'Kazanıldı',
                        'closed-lost' => 'Kaybedildi',
                    ])
                    ->required()
                    ->default('prospecting')
                    ->native(false),
                Select::make('priority')
                    ->label('Öncelik')
                    ->options([
                        'low' => 'Düşük',
                        'medium' => 'Orta',
                        'high' => 'Yüksek',
                        'urgent' => 'Acil',
                    ])
                    ->required()
                    ->default('medium')
                    ->native(false),
                DatePicker::make('expected_close_date')
                    ->label('Beklenen Kapanış Tarihi')
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('d.m.Y')
                    ->native(false),
                DatePicker::make('actual_close_date')
                    ->label('Gerçek Kapanış Tarihi')
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('d.m.Y')
                    ->native(false),
                Textarea::make('notes')
                    ->label('Notlar')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }
}
