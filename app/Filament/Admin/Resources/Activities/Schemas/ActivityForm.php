<?php

namespace App\Filament\Admin\Resources\Activities\Schemas;

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
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('opportunity_id')
                    ->relationship('opportunity', 'title'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('type')
                    ->required()
                    ->default('note'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('scheduled_at'),
                DateTimePicker::make('completed_at'),
                Textarea::make('outcome')
                    ->columnSpanFull(),
            ]);
    }
}
