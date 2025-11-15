<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Opportunity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalCustomersWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeOpportunitiesCount = Opportunity::whereMonth('expected_close_date', now()->month)
            ->whereYear('expected_close_date', now()->year)
            ->whereNotIn('stage', ['closed-won', 'closed-lost'])
            ->count();

        return [
            Stat::make('Toplam Müşteri', Customer::count())
                ->description('Sistemdeki toplam müşteri sayısı')
                ->descriptionIcon('heroicon-o-building-office-2')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
            Stat::make('Bu Ay Kapanacak Fırsatlar', $activeOpportunitiesCount)
                ->description('Bu ay içinde kapanması beklenen aktif fırsatlar')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('warning')
                ->chart([2, 3, 4, 3, 5, 4, 6]),
            Stat::make('Toplam Etkinlik', Activity::count())
                ->description('Sistemde kayıtlı toplam etkinlik sayısı')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('info')
                ->chart([5, 4, 3, 5, 6, 7, 8]),
        ];
    }
}

