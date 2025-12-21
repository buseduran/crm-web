<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Opportunity;
use Carbon\Carbon;

class DashboardDataService
{
    /**
     * Collect all dashboard data for AI report generation.
     */
    public function collectData(): array
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        $lastWeek = $now->copy()->subWeek();

        return [
            'customers' => $this->getCustomerData($now, $lastMonth),
            'opportunities' => $this->getOpportunityData($now, $lastMonth),
            'activities' => $this->getActivityData($now, $lastWeek),
            'attendance' => $this->getAttendanceData($now, $lastWeek),
            'period' => [
                'current_month' => $now->format('F Y'),
                'last_month' => $lastMonth->format('F Y'),
                'current_week' => $now->format('W'),
                'last_week' => $lastWeek->format('W'),
            ],
        ];
    }

    /**
     * Get customer statistics.
     */
    protected function getCustomerData(Carbon $now, Carbon $lastMonth): array
    {
        $totalCustomers = Customer::count();
        $lastMonthCustomers = Customer::where('created_at', '<=', $lastMonth->endOfMonth())->count();
        $thisMonthCustomers = Customer::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $changeRate = $lastMonthCustomers > 0
            ? (($totalCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100
            : 0;

        $activeCustomers = Customer::where('status', 'active')->count();
        $prospectCustomers = Customer::where('status', 'prospect')->count();

        return [
            'total' => $totalCustomers,
            'change_rate' => round($changeRate, 2),
            'this_month_new' => $thisMonthCustomers,
            'active' => $activeCustomers,
            'prospect' => $prospectCustomers,
        ];
    }

    /**
     * Get opportunity statistics.
     */
    protected function getOpportunityData(Carbon $now, Carbon $lastMonth): array
    {
        $totalOpportunities = Opportunity::count();
        $activeOpportunities = Opportunity::whereNotIn('stage', ['closed-won', 'closed-lost'])->count();
        $closedWon = Opportunity::where('stage', 'closed-won')->count();
        $closedLost = Opportunity::where('stage', 'closed-lost')->count();

        $thisMonthClosing = Opportunity::whereMonth('expected_close_date', $now->month)
            ->whereYear('expected_close_date', $now->year)
            ->whereNotIn('stage', ['closed-won', 'closed-lost'])
            ->count();

        $totalValue = Opportunity::whereNotIn('stage', ['closed-won', 'closed-lost'])
            ->sum('value');

        $wonValue = Opportunity::where('stage', 'closed-won')
            ->sum('value');

        $stageDistribution = Opportunity::selectRaw('stage, COUNT(*) as count')
            ->groupBy('stage')
            ->pluck('count', 'stage')
            ->toArray();

        $priorityDistribution = Opportunity::whereNotIn('stage', ['closed-won', 'closed-lost'])
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        return [
            'total' => $totalOpportunities,
            'active' => $activeOpportunities,
            'closed_won' => $closedWon,
            'closed_lost' => $closedLost,
            'this_month_closing' => $thisMonthClosing,
            'total_value' => (float) $totalValue,
            'won_value' => (float) $wonValue,
            'stage_distribution' => $stageDistribution,
            'priority_distribution' => $priorityDistribution,
        ];
    }

    /**
     * Get activity statistics.
     */
    protected function getActivityData(Carbon $now, Carbon $lastWeek): array
    {
        $totalActivities = Activity::count();
        $thisWeekActivities = Activity::whereBetween('created_at', [
            $now->copy()->startOfWeek(),
            $now->copy()->endOfWeek(),
        ])->count();

        $lastWeekActivities = Activity::whereBetween('created_at', [
            $lastWeek->copy()->startOfWeek(),
            $lastWeek->copy()->endOfWeek(),
        ])->count();

        $completedActivities = Activity::where('status', 'completed')->count();
        $pendingActivities = Activity::where('status', 'pending')->count();

        $typeDistribution = Activity::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return [
            'total' => $totalActivities,
            'this_week' => $thisWeekActivities,
            'last_week' => $lastWeekActivities,
            'week_change' => $thisWeekActivities - $lastWeekActivities,
            'completed' => $completedActivities,
            'pending' => $pendingActivities,
            'type_distribution' => $typeDistribution,
        ];
    }

    /**
     * Get attendance statistics.
     */
    protected function getAttendanceData(Carbon $now, Carbon $lastWeek): array
    {
        $thisWeekAttendances = Attendance::whereBetween('date', [
            $now->copy()->startOfWeek()->format('Y-m-d'),
            $now->copy()->endOfWeek()->format('Y-m-d'),
        ])->count();

        $lastWeekAttendances = Attendance::whereBetween('date', [
            $lastWeek->copy()->startOfWeek()->format('Y-m-d'),
            $lastWeek->copy()->endOfWeek()->format('Y-m-d'),
        ])->count();

        $presentCount = Attendance::whereBetween('date', [
            $now->copy()->startOfWeek()->format('Y-m-d'),
            $now->copy()->endOfWeek()->format('Y-m-d'),
        ])->where('status', 'present')->count();

        $absentCount = Attendance::whereBetween('date', [
            $now->copy()->startOfWeek()->format('Y-m-d'),
            $now->copy()->endOfWeek()->format('Y-m-d'),
        ])->where('status', 'absent')->count();

        return [
            'this_week_total' => $thisWeekAttendances,
            'last_week_total' => $lastWeekAttendances,
            'week_change' => $thisWeekAttendances - $lastWeekAttendances,
            'present' => $presentCount,
            'absent' => $absentCount,
        ];
    }

    /**
     * Format data as a structured text for AI prompt.
     */
    public function formatForPrompt(array $data): string
    {
        $text = "CRM Sistemi Dashboard Verileri:\n\n";

        // Customers
        $text .= "MÜŞTERİ İSTATİSTİKLERİ:\n";
        $text .= "- Toplam Müşteri: {$data['customers']['total']}\n";
        $text .= "- Bu Ay Yeni Müşteri: {$data['customers']['this_month_new']}\n";
        $text .= "- Aktif Müşteri: {$data['customers']['active']}\n";
        $text .= "- Potansiyel Müşteri: {$data['customers']['prospect']}\n";
        $text .= "- Aylık Değişim Oranı: %{$data['customers']['change_rate']}\n\n";

        // Opportunities
        $text .= "FIRSAT İSTATİSTİKLERİ:\n";
        $text .= "- Toplam Fırsat: {$data['opportunities']['total']}\n";
        $text .= "- Aktif Fırsat: {$data['opportunities']['active']}\n";
        $text .= "- Kazanılan Fırsat: {$data['opportunities']['closed_won']}\n";
        $text .= "- Kaybedilen Fırsat: {$data['opportunities']['closed_lost']}\n";
        $text .= "- Bu Ay Kapanacak Fırsat: {$data['opportunities']['this_month_closing']}\n";
        $text .= '- Toplam Fırsat Değeri: '.number_format($data['opportunities']['total_value'], 2)." TL\n";
        $text .= '- Kazanılan Fırsat Değeri: '.number_format($data['opportunities']['won_value'], 2)." TL\n";

        if (! empty($data['opportunities']['stage_distribution'])) {
            $text .= '- Aşama Dağılımı: ';
            $stages = [
                'prospecting' => 'Potansiyel',
                'qualification' => 'Nitelendirme',
                'proposal' => 'Teklif',
                'negotiation' => 'Müzakereler',
                'closed-won' => 'Kazanıldı',
                'closed-lost' => 'Kaybedildi',
            ];
            $stageTexts = [];
            foreach ($data['opportunities']['stage_distribution'] as $stage => $count) {
                $stageLabel = $stages[$stage] ?? $stage;
                $stageTexts[] = "{$stageLabel}: {$count}";
            }
            $text .= implode(', ', $stageTexts)."\n";
        }
        $text .= "\n";

        // Activities
        $text .= "ETKİNLİK İSTATİSTİKLERİ:\n";
        $text .= "- Toplam Etkinlik: {$data['activities']['total']}\n";
        $text .= "- Bu Hafta Etkinlik: {$data['activities']['this_week']}\n";
        $text .= "- Geçen Hafta Etkinlik: {$data['activities']['last_week']}\n";
        $text .= "- Haftalık Değişim: {$data['activities']['week_change']}\n";
        $text .= "- Tamamlanan: {$data['activities']['completed']}\n";
        $text .= "- Bekleyen: {$data['activities']['pending']}\n";
        $text .= "\n";

        // Attendance
        if ($data['attendance']['this_week_total'] > 0) {
            $text .= "DEVAM TAKİP İSTATİSTİKLERİ:\n";
            $text .= "- Bu Hafta Toplam Devam: {$data['attendance']['this_week_total']}\n";
            $text .= "- Geçen Hafta Toplam Devam: {$data['attendance']['last_week_total']}\n";
            $text .= "- Haftalık Değişim: {$data['attendance']['week_change']}\n";
            $text .= "- Mevcut: {$data['attendance']['present']}\n";
            $text .= "- Yok: {$data['attendance']['absent']}\n";
            $text .= "\n";
        }

        return $text;
    }
}
