<?php

namespace App\Filament\Admin\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Adı Soyadı / Şirket')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('company')
                    ->label('Şirket')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('city')
                    ->label('Şehir')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('country')
                    ->label('Ülke')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('postal_code')
                    ->label('Posta Kodu')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'prospect' => 'Potansiyel Müşteri',
                        'lead' => 'Aday Müşteri',
                        'customer' => 'Müşteri',
                        'inactive' => 'Pasif',
                        'lost' => 'Kaybedildi',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'prospect' => 'gray',
                        'lead' => 'info',
                        'customer' => 'success',
                        'inactive' => 'warning',
                        'lost' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
