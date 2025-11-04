<?php

namespace App\Filament\Admin\Resources\Activities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('activityable'))
            ->columns([
                TextColumn::make('activityable_type')
                    ->label('İlişkili Tip')
                    ->formatStateUsing(fn ($state) => match($state) {
                        \App\Models\Customer::class => 'Müşteri',
                        \App\Models\Opportunity::class => 'Fırsat',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        \App\Models\Customer::class => 'info',
                        \App\Models\Opportunity::class => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('activityable.name')
                    ->label('Müşteri')
                    ->visible(fn ($record) => $record && $record->activityable_type === \App\Models\Customer::class)
                    ->getStateUsing(fn ($record) => $record?->activityable?->name ?? '-')
                    ->searchable(),
                TextColumn::make('activityable.title')
                    ->label('Fırsat')
                    ->visible(fn ($record) => $record && $record->activityable_type === \App\Models\Opportunity::class)
                    ->getStateUsing(fn ($record) => $record?->activityable?->title ?? '-')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tip')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'call' => 'Telefon Araması',
                        'email' => 'E-posta',
                        'meeting' => 'Toplantı',
                        'task' => 'Görev',
                        'note' => 'Not',
                        'other' => 'Diğer',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'call' => 'info',
                        'email' => 'warning',
                        'meeting' => 'success',
                        'task' => 'primary',
                        'note' => 'gray',
                        'other' => 'gray',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'Beklemede',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal Edildi',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->dateTime('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Bitiş')
                    ->dateTime('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('scheduled_at')
                    ->label('Planlanan')
                    ->dateTime('d.m.Y H:i')
                    ->timezone('Europe/Istanbul')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completed_at')
                    ->label('Tamamlanma')
                    ->dateTime('d.m.Y H:i')
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
