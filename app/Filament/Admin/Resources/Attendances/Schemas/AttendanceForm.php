<?php

namespace App\Filament\Admin\Resources\Attendances\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Kullanıcı')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                DatePicker::make('date')
                    ->label('Tarih')
                    ->required()
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('d.m.Y')
                    ->native(false)
                    ->default(now()),
                TimePicker::make('check_in')
                    ->label('Giriş Saati')
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('H:i')
                    ->seconds(false)
                    ->native(false),
                TimePicker::make('check_out')
                    ->label('Çıkış Saati')
                    ->timezone('Europe/Istanbul')
                    ->displayFormat('H:i')
                    ->seconds(false)
                    ->native(false),
                Select::make('status')
                    ->label('Durum')
                    ->options([
                        'present' => 'Tam Gün',
                        'half_day' => 'Yarım Gün',
                        'absent' => 'Yok',
                        'leave' => 'İzinli',
                        'holiday' => 'Tatil',
                    ])
                    ->required()
                    ->default('present')
                    ->native(false),
                Textarea::make('notes')
                    ->label('Notlar')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }
}
