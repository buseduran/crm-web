<?php

namespace App\Filament\Admin\Resources\Opportunities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OpportunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('customer'))
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Müşteri')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->label('Değer')
                    ->money('TRY', locale: 'tr')
                    ->sortable(),
                TextColumn::make('stage')
                    ->label('Aşama')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'prospecting' => 'Potansiyel Müşteri',
                        'qualification' => 'Nitelendirme',
                        'proposal' => 'Teklif',
                        'negotiation' => 'Müzakereler',
                        'closed-won' => 'Kazanıldı',
                        'closed-lost' => 'Kaybedildi',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'prospecting' => 'gray',
                        'qualification' => 'info',
                        'proposal' => 'warning',
                        'negotiation' => 'primary',
                        'closed-won' => 'success',
                        'closed-lost' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('Öncelik')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'low' => 'Düşük',
                        'medium' => 'Orta',
                        'high' => 'Yüksek',
                        'urgent' => 'Acil',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('expected_close_date')
                    ->label('Beklenen Kapanış')
                    ->date('d.m.Y')
                    ->timezone('Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('actual_close_date')
                    ->label('Gerçek Kapanış')
                    ->date('d.m.Y')
                    ->timezone('Europe/Istanbul')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
