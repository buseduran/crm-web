<?php

namespace App\Filament\Admin\Resources\CardReadLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CardReadLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable()
                    ->default('-'),
                TextColumn::make('card_string')
                    ->label('Kart String')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn ($state) => $state ? 'Başarılı' : 'Başarısız')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('read_at')
                    ->label('Okuma Zamanı')
                    ->dateTime('d.m.Y H:i:s')
                    ->timezone('Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d.m.Y H:i:s')
                    ->timezone('Europe/Istanbul')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        '1' => 'Başarılı',
                        '0' => 'Başarısız',
                    ]),
                Filter::make('read_at')
                    ->label('Okuma Zamanı')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('read_from')
                            ->label('Başlangıç Tarihi'),
                        \Filament\Forms\Components\DatePicker::make('read_until')
                            ->label('Bitiş Tarihi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['read_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('read_at', '>=', $date),
                            )
                            ->when(
                                $data['read_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('read_at', '<=', $date),
                            );
                    }),
                SelectFilter::make('user_id')
                    ->label('Kullanıcı')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('read_at', 'desc')
            ->recordActions([
                // Read-only resource - no actions
            ])
            ->toolbarActions([
                // Read-only resource - no bulk actions
            ]);
    }
}
