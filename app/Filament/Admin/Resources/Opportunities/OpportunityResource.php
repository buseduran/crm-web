<?php

namespace App\Filament\Admin\Resources\Opportunities;

use App\Filament\Admin\Resources\Opportunities\Pages\CreateOpportunity;
use App\Filament\Admin\Resources\Opportunities\Pages\EditOpportunity;
use App\Filament\Admin\Resources\Opportunities\Pages\ListOpportunities;
use App\Filament\Admin\Resources\Opportunities\Schemas\OpportunityForm;
use App\Filament\Admin\Resources\Opportunities\Tables\OpportunitiesTable;
use App\Models\Opportunity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OpportunityResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;
    protected static ?string $navigationLabel = 'Fırsatlar';  
    protected static ?string $pluralNavigationLabel = 'Fırsatlar';
    protected static ?string $label = 'Fırsat';
    protected static ?string $pluralLabel = 'Fırsatlar';
    protected static string|UnitEnum|null $navigationGroup = 'CRM';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return OpportunityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OpportunitiesTable::configure($table);
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
            'index' => ListOpportunities::route('/'),
            'create' => CreateOpportunity::route('/create'),
            'edit' => EditOpportunity::route('/{record}/edit'),
        ];
    }
}
