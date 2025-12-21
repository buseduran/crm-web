<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiReportService
{
    public function __construct(
        protected DashboardDataService $dashboardDataService
    ) {}

    /**
     * Generate AI-powered report summary.
     */
    public function generateReport(bool $forceRefresh = false): ?string
    {
        $cacheKey = 'ai_report_summary';
        $cacheTtl = config('ai.gemini.cache_ttl', 3600);

        // Return cached result if available and not forcing refresh
        if (! $forceRefresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $data = $this->dashboardDataService->collectData();
            $formattedData = $this->dashboardDataService->formatForPrompt($data);

            $prompt = $this->buildPrompt($formattedData);

            $response = $this->callGeminiApi($prompt);

            if ($response === null) {
                return null;
            }

            // Cache the successful response
            Cache::put($cacheKey, $response, $cacheTtl);

            return $response;
        } catch (\Exception $e) {
            Log::error('AI Report generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Build the prompt for Gemini API.
     */
    protected function buildPrompt(string $data): string
    {
        return "Sen bir CRM sistemi analiz uzmanısın. Aşağıdaki dashboard verilerini analiz edip, Türkçe olarak profesyonel ve anlaşılır bir özet rapor hazırla. 

ÖNEMLİ KURALLAR:
- Veri olmayan kategoriler için 'veri yok' demek yerine, mevcut verileri vurgula
- Her zaman pozitif ve yapıcı bir ton kullan
- Sıfır değerler varsa bunları 'henüz kayıt yok' veya 'başlangıç aşamasında' gibi ifadelerle belirt
- Mevcut verileri öne çıkar ve bunların önemini vurgula
- Raporu MUTLAKA tamamla, yarıda bırakma

Rapor şu özelliklere sahip olmalı:
1. Genel bir giriş paragrafı ile durum özeti (mevcut verileri vurgulayarak)
2. Önemli trendler ve değişimler vurgulanmalı (varsa)
3. Dikkat çekilmesi gereken noktalar belirtilmeli
4. Sonuç ve öneriler paragrafı
5. Toplam 4-5 paragraf olmalı
6. Profesyonel bir iş raporu tonunda yazılmalı
7. Mevcut verileri analiz edip anlamlı yorumlar yap
8. Raporu mutlaka tamamla ve sonlandır

Veriler:
{$data}

Lütfen analiz raporunu TAM OLARAK hazırla ve sonlandır:";
    }

    /**
     * Call Google Gemini API.
     */
    protected function callGeminiApi(string $prompt): ?string
    {
        $apiKey = config('ai.gemini.api_key');
        $model = config('ai.gemini.model', 'gemini-1.5-flash');
        $baseUrl = config('ai.gemini.base_url');
        $timeout = config('ai.gemini.timeout', 30);

        if (empty($apiKey)) {
            Log::warning('Gemini API key is not configured', [
                'config_key' => 'ai.gemini.api_key',
                'env_key' => 'GEMINI_API_KEY',
            ]);

            return null;
        }

        $url = "{$baseUrl}/models/{$model}:generateContent";

        try {
            $response = Http::timeout($timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post("{$url}?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt,
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 3072,
                    ],
                ]);

            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();

                Log::error('Gemini API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'error_message' => $errorMessage,
                ]);

                return null;
            }

            $responseData = $response->json();

            if (! isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                Log::error('Unexpected Gemini API response structure', [
                    'response' => $responseData,
                ]);

                return null;
            }

            return $responseData['candidates'][0]['content']['parts'][0]['text'];
        } catch (RequestException $e) {
            Log::error('Gemini API request exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Unexpected error calling Gemini API', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Clear the cached report.
     */
    public function clearCache(): void
    {
        Cache::forget('ai_report_summary');
    }
}
