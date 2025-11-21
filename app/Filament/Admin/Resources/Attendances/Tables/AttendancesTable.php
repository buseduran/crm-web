<?php

namespace App\Filament\Admin\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tarih')
                    ->date('d.m.Y')
                    ->timezone('Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label('Giriş')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : '-')
                    ->timezone('Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('check_out')
                    ->label('Çıkış')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : '-')
                    ->timezone('Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('work_duration')
                    ->label('Çalışma Süresi')
                    ->formatStateUsing(fn ($state) => $state ? sprintf('%d:%02d', floor($state / 60), $state % 60) : '-')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'present' => 'Tam Gün',
                        'half_day' => 'Yarım Gün',
                        'absent' => 'Yok',
                        'leave' => 'İzinli',
                        'holiday' => 'Tatil',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'present' => 'success',
                        'half_day' => 'warning',
                        'absent' => 'danger',
                        'leave' => 'info',
                        'holiday' => 'gray',
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
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Kullanıcı')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'present' => 'Tam Gün',
                        'half_day' => 'Yarım Gün',
                        'absent' => 'Yok',
                        'leave' => 'İzinli',
                        'holiday' => 'Tatil',
                    ]),
                Filter::make('date')
                    ->label('Tarih Aralığı')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date_from')
                            ->label('Başlangıç Tarihi'),
                        \Filament\Forms\Components\DatePicker::make('date_until')
                            ->label('Bitiş Tarihi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
                Filter::make('month_year')
                    ->label('Ay/Yıl')
                    ->form([
                        \Filament\Forms\Components\Select::make('month')
                            ->label('Ay')
                            ->options([
                                1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                                5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                                9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık',
                            ])
                            ->default(now()->month),
                        \Filament\Forms\Components\Select::make('year')
                            ->label('Yıl')
                            ->options(function () {
                                $years = [];
                                $currentYear = now()->year;
                                for ($i = $currentYear - 2; $i <= $currentYear + 1; $i++) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            })
                            ->default(now()->year),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['month'] ?? null,
                                fn (Builder $query, $month): Builder => $query->whereMonth('date', $month),
                            )
                            ->when(
                                $data['year'] ?? null,
                                fn (Builder $query, $year): Builder => $query->whereYear('date', $year),
                            );
                    }),
            ])
            ->defaultSort('date', 'desc')
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
