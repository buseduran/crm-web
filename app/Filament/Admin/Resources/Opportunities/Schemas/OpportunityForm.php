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
                    ->relationship('customer', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('value')
                    ->numeric(),
                TextInput::make('stage')
                    ->required()
                    ->default('prospecting'),
                TextInput::make('priority')
                    ->required()
                    ->default('medium'),
                DatePicker::make('expected_close_date'),
                DatePicker::make('actual_close_date'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
