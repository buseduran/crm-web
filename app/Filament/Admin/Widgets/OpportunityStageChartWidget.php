<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Opportunity;
use Filament\Widgets\ChartWidget;

class OpportunityStageChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    public function getHeading(): ?string
    {
        return 'Fırsatların Aşamalara Göre Dağılımı';
    }

    protected function getData(): array
    {
        $stages = [
            'prospecting' => 'Potansiyel Müşteri',
            'qualification' => 'Nitelendirme',
            'proposal' => 'Teklif',
            'negotiation' => 'Müzakereler',
            'closed-won' => 'Kazanıldı',
            'closed-lost' => 'Kaybedildi',
        ];

        $data = [];
        $labels = [];

        foreach ($stages as $key => $label) {
            $count = Opportunity::where('stage', $key)->count();
            $data[] = $count;
            $labels[] = $label;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Fırsat Sayısı',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',   // blue - prospecting
                        'rgba(16, 185, 129, 0.8)',   // green - qualification
                        'rgba(245, 158, 11, 0.8)',    // yellow - proposal
                        'rgba(239, 68, 68, 0.8)',     // red - negotiation
                        'rgba(34, 197, 94, 0.8)',     // green - closed-won
                        'rgba(107, 114, 128, 0.8)',  // gray - closed-lost
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(34, 197, 94)',
                        'rgb(107, 114, 128)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'aspectRatio' => 2.0,
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

