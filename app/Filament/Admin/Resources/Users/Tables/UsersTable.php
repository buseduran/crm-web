<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(label: 'Adı Soyadı')
                    ->searchable(),
                TextColumn::make('email')
                    ->label(label: 'E-posta Adresi')
                    ->searchable(),
                TextColumn::make('card_string')
                    ->label(label: 'Kart String')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('roles.name')
                    ->label(label: 'Roller')
                    ->badge()
                    ->separator(',')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(label: 'Oluşturulma Tarihi')
                    ->dateTime('d.m.Y H:i:s')->timezone('Europe/Istanbul')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(label: 'Güncellenme Tarihi')
                    ->dateTime('d.m.Y H:i:s')->timezone('Europe/Istanbul')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
