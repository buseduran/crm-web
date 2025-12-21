<?php

namespace App\Filament\Admin\Widgets;

use App\Services\AiReportService;
use Filament\Widgets\Widget;

class AiReportWidget extends Widget
{
    protected static ?int $sort = 999;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.ai-report-widget';

    public ?string $report = null;

    public bool $isLoading = false;

    public ?string $error = null;

    protected AiReportService $aiReportService;

    protected static ?string $pollingInterval = null;

    public function boot(): void
    {
        $this->aiReportService = app(AiReportService::class);
    }

    public function mount(): void
    {
        // Mount'u boş bırak, veriyi lazy olarak yükle
        // Bu sayede diğer widget'lar bloklanmaz
        // Veri view render edilirken yüklenecek
    }

    public function loadReport(bool $forceRefresh = false): void
    {
        // Cache'den hızlıca oku, blocking işlem yapma
        $this->isLoading = false;
        $this->error = null;

        try {
            // Widget sadece cache'den okur, API çağrısı yapmaz
            // API çağrısı scheduled job ile günde 1 kez yapılır
            // Cache::get() çok hızlıdır, blocking değildir
            $cacheKey = 'ai_report_summary';
            $this->report = \Illuminate\Support\Facades\Cache::get($cacheKey);

            if ($this->report === null) {
                $this->error = 'AI raporu henüz oluşturulmamış. Lütfen daha sonra tekrar deneyin veya yönetici ile iletişime geçin.';
            }
        } catch (\Exception $e) {
            $this->error = 'Rapor yüklenirken bir hata oluştu: '.$e->getMessage();
        }
    }

    public function refresh(): void
    {
        // Sadece cache'den yeniden yükle, API çağrısı yapma
        // API çağrısı scheduled job ile günde 1 kez yapılır
        $this->loadReport(false);
    }

    public function getHeading(): ?string
    {
        return '✨ Dashboard Analiz Özeti';
    }

    public function getDescription(): ?string
    {
        return 'Dashboard verilerinin detaylı analiz raporu';
    }

    /**
     * Convert markdown to HTML for display.
     */
    public function getFormattedReport(): ?string
    {
        if ($this->report === null) {
            return null;
        }

        $text = $this->report;

        // Convert **bold** to <strong>
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);

        // Convert *italic* to <em>
        $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);

        // Convert line breaks to <br>
        $text = nl2br($text);

        // Convert ## headings to <h2>
        $text = preg_replace('/^## (.+)$/m', '<h2 class="text-xl font-bold mt-4 mb-2">$1</h2>', $text);

        // Convert ### headings to <h3>
        $text = preg_replace('/^### (.+)$/m', '<h3 class="text-lg font-semibold mt-3 mb-2">$1</h3>', $text);

        return $text;
    }
}
