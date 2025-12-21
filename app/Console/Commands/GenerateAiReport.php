<?php

namespace App\Console\Commands;

use App\Services\AiReportService;
use Illuminate\Console\Command;

class GenerateAiReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:generate-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI-powered dashboard report and cache it';

    /**
     * Execute the console command.
     */
    public function handle(AiReportService $aiReportService): int
    {
        $this->info('AI raporu oluşturuluyor...');

        try {
            $report = $aiReportService->generateReport(true);

            if ($report === null) {
                $this->error('AI raporu oluşturulamadı.');

                return Command::FAILURE;
            }

            $this->info('AI raporu başarıyla oluşturuldu ve cache\'e kaydedildi.');
            $this->line('Rapor uzunluğu: '.strlen($report).' karakter');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Hata: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
