<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class MonthlyAttendance extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.monthly-attendance';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'selectedMonth' => now()->month,
            'selectedYear' => now()->year,
            'selectedUserId' => null,
        ]);
    }

    protected function getSelectedMonth(): ?int
    {
        return $this->form->getState()['selectedMonth'] ?? now()->month;
    }

    protected function getSelectedYear(): ?int
    {
        return $this->form->getState()['selectedYear'] ?? now()->year;
    }

    protected function getSelectedUserId(): ?int
    {
        return $this->form->getState()['selectedUserId'] ?? null;
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
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
                    ->timezone('Europe/Istanbul'),
                TextColumn::make('check_out')
                    ->label('Çıkış')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : '-')
                    ->timezone('Europe/Istanbul'),
                TextColumn::make('work_duration')
                    ->label('Çalışma Süresi')
                    ->formatStateUsing(fn ($state) => $state ? sprintf('%d:%02d', floor($state / 60), $state % 60) : '-'),
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
                    }),
            ])
            ->defaultSort('date', 'desc');
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Attendance::query()->with('user');

        $month = $this->getSelectedMonth();
        $year = $this->getSelectedYear();
        $userId = $this->getSelectedUserId();

        if ($month) {
            $query->whereMonth('date', $month);
        }

        if ($year) {
            $query->whereYear('date', $year);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query;
    }

    public function getSummaryData(): array
    {
        $query = $this->getTableQuery();
        $attendances = $query->get();

        $totalWorkMinutes = $attendances->sum('work_duration') ?? 0;
        $totalWorkHours = floor($totalWorkMinutes / 60);
        $totalWorkMinutesRemainder = $totalWorkMinutes % 60;

        $presentCount = $attendances->where('status', 'present')->count();
        $halfDayCount = $attendances->where('status', 'half_day')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $leaveCount = $attendances->where('status', 'leave')->count();

        return [
            'total_work_hours' => sprintf('%d:%02d', $totalWorkHours, $totalWorkMinutesRemainder),
            'present_count' => $presentCount,
            'half_day_count' => $halfDayCount,
            'absent_count' => $absentCount,
            'leave_count' => $leaveCount,
            'total_records' => $attendances->count(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('selectedMonth')
                    ->label('Ay')
                    ->options([
                        1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                        5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                        9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık',
                    ])
                    ->default(now()->month)
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetTable()),
                Select::make('selectedYear')
                    ->label('Yıl')
                    ->options(function () {
                        $years = [];
                        $currentYear = now()->year;
                        for ($i = $currentYear - 2; $i <= $currentYear + 1; $i++) {
                            $years[$i] = $i;
                        }
                        return $years;
                    })
                    ->default(now()->year)
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetTable()),
                Select::make('selectedUserId')
                    ->label('Kullanıcı')
                    ->options(\App\Models\User::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->placeholder('Tüm Kullanıcılar')
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetTable()),
            ])
            ->statePath('data');
    }
}

