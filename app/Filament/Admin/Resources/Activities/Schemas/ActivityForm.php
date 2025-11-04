<?php

namespace App\Filament\Admin\Resources\Activities\Schemas;

use App\Models\Customer;
use App\Models\Opportunity;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('activityable_type')
                    ->label('İlişkili Tip')
                    ->helperText('İlişkili tipi seçiniz.')
                    ->options([
                        Customer::class => 'Müşteri',
                        Opportunity::class => 'Fırsat',
                    ])
                    ->required()
                    ->live()
                    ->native(false)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('activityable_id', null)),
                Select::make('activityable_id')
                    ->label('İlişkili Kayıt')
                    ->helperText('İlişkili kayıtı seçiniz.')
                    ->required()
                    ->options(function (callable $get) {
                        $type = $get('activityable_type');
                        
                        if (!$type) {
                            return [];
                        }
                        
                        if ($type === Customer::class) {
                            return Customer::pluck('name', 'id')->toArray();
                        }
                        
                        if ($type === Opportunity::class) {
                            return Opportunity::pluck('title', 'id')->toArray();
                        }
                        
                        return [];
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->visible(fn (callable $get) => filled($get('activityable_type')))
                    ->disabled(fn (callable $get) => !filled($get('activityable_type'))),
                TextInput::make('title')
                    ->label('Başlık')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Açıklama')
                    ->columnSpanFull()
                    ->rows(4),
                Select::make('type')
                    ->label('Tip')
                    ->options([
                        'call' => 'Telefon Araması',
                        'email' => 'E-posta',
                        'meeting' => 'Toplantı',
                        'task' => 'Görev',
                        'note' => 'Not',
                        'other' => 'Diğer',
                    ])
                    ->required()
                    ->default('note'),
                Select::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal Edildi',
                    ])
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('start_date')
                    ->label('Başlangıç Tarihi')
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('d.m.Y H:i')
                    ->native(false),
                DateTimePicker::make('end_date')
                    ->label('Bitiş Tarihi')
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('d.m.Y H:i')
                    ->native(false),
                DateTimePicker::make('scheduled_at')
                    ->label('Planlanan Tarih')
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('d.m.Y H:i')
                    ->native(false),
                DateTimePicker::make('completed_at')
                    ->label('Tamamlanma Tarihi')
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('d.m.Y H:i')
                    ->native(false),
                Textarea::make('outcome')
                    ->label('Sonuç')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }
}
