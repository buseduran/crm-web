<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyCustomersChartWidget extends ChartWidget
{
    protected static ?int $sort = 5;

    public function getHeading(): ?string
    {
        return 'Aylık Yeni Müşteri Trendi';
    }

    protected function getData(): array
    {
        $months = [];
        $data = [];

        // Son 12 ayın verilerini al
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->locale('tr')->translatedFormat('F Y');

            $count = Customer::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $months[] = $monthLabel;
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Yeni Müşteri Sayısı',
                    'data' => $data,
                    'backgroundColor' => 'rgba(147, 51, 234, 0.1)',
                    'borderColor' => 'rgb(147, 51, 234)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'aspectRatio' => 2.0,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }

    public function getExtraAttributes(): array
    {
        return [
            'class' => 'h-full',
            'style' => 'min-height: 400px;',
        ];
    }
}

