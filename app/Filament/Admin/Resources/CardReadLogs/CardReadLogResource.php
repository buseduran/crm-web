<?php

namespace App\Filament\Admin\Resources\CardReadLogs;

use App\Filament\Admin\Resources\CardReadLogs\Pages\ListCardReadLogs;
use App\Filament\Admin\Resources\CardReadLogs\Tables\CardReadLogsTable;
use App\Models\CardReadLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CardReadLogResource extends Resource
{
    protected static ?string $model = CardReadLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    protected static ?string $navigationLabel = 'Kart Okuma Kayıtları';
    protected static ?string $pluralNavigationLabel = 'Kart Okuma Kayıtları';
    protected static ?string $label = 'Kart Okuma Kaydı';
    protected static ?string $pluralLabel = 'Kart Okuma Kayıtları';
    protected static string|UnitEnum|null $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return CardReadLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCardReadLogs::route('/'),
        ];
    }
}
