---
name: AI Smart Reporting Integration
overview: Google Gemini API kullanarak dashboard verilerini analiz edip doğal dilde özet üreten bir AI widget'ı ekleyeceğiz. Ücretsiz Gemini API tier'ını kullanacağız.
todos:
  - id: config_setup
    content: Google Gemini API için config dosyası ve environment variable ekleme
    status: completed
  - id: dashboard_service
    content: Dashboard verilerini toplayan DashboardDataService sınıfını oluşturma
    status: completed
  - id: ai_service
    content: Gemini API entegrasyonu yapan AiReportService sınıfını oluşturma (cache mekanizması ile)
    status: completed
  - id: filament_widget
    content: AI özetini gösteren Filament widget'ını oluşturma
    status: completed
  - id: widget_registration
    content: Widget'ı AdminPanelProvider'da kaydetme
    status: completed
---

# AI Akıllı Rapor Özetleme Ente

grasyonu

## Genel Bakış

Dashboard verilerini (müşteriler, fırsatlar, etkinlikler, devam takibi) toplayıp Google Gemini API'ye göndererek doğal dilde özet rapor üreten bir Filament widget'ı ekleyeceğiz.

## Mimari

```javascript
Dashboard Data Collector → AI Service → Gemini API → Cached Response → Widget Display
```



## Uygulama Adımları

### 1. Google Gemini API Yapılandırması

- `config/ai.php` dosyası oluşturulacak (API key, model, cache süresi ayarları)
- `.env` dosyasına `GEMINI_API_KEY` eklenecek
- Google Gemini API ücretsiz tier kullanılacak (günlük limit: 15 RPM, 1500 RPD)

### 2. Dashboard Veri Toplama Servisi

- `app/Services/DashboardDataService.php` oluşturulacak
- Müşteri, fırsat, etkinlik ve devam verilerini toplayacak
- Haftalık/aylık karşılaştırmalar yapacak
- JSON formatında yapılandırılmış veri döndürecek

### 3. AI Servis Sınıfı

- `app/Services/AiReportService.php` oluşturulacak
- Google Gemini API ile HTTP entegrasyonu (Laravel HTTP client kullanılacak)
- Prompt engineering ile Türkçe rapor üretimi
- Hata yönetimi ve fallback mekanizması
- Cache mekanizması (1 saatlik cache, API çağrılarını azaltmak için)

### 4. Filament Widget

- `app/Filament/Admin/Widgets/AiReportWidget.php` oluşturulacak
- Filament'in `Widget` sınıfından extend edilecek
- AI özetini güzel bir card içinde gösterecek
- Loading state ve error handling
- "Yenile" butonu ile cache'i bypass edebilme

### 5. Prompt Tasarımı

AI'a gönderilecek prompt şu verileri içerecek:

- Toplam müşteri sayısı ve değişim oranı
- Aktif fırsatlar ve aşama dağılımı
- Bu ay kapanacak fırsatlar
- Etkinlik istatistikleri
- Devam takip verileri (varsa)

Prompt Türkçe olacak ve profesyonel bir rapor formatında özet istenecek.

## Dosya Yapısı

```javascript
app/
  Services/
    DashboardDataService.php (yeni)
    AiReportService.php (yeni)
  Filament/
    Admin/
      Widgets/
        AiReportWidget.php (yeni)
config/
  ai.php (yeni)
```



## Teknik Detaylar

- **API Client**: Laravel HTTP Client (Guzzle wrapper)
- **Cache**: Laravel Cache (1 saat TTL)
- **Error Handling**: Try-catch ile API hatalarını yakalama, kullanıcıya anlamlı mesaj gösterme
- **Rate Limiting**: Cache sayesinde API limitlerini aşmama
- **Model**: Gemini 1.5 Flash (hızlı ve ücretsiz tier'da mevcut)

## Güvenlik

- API key `.env` dosyasında saklanacak
- Config dosyasında `env()` kullanılmayacak, `config()` kullanılacak
- Widget sadece yetkili kullanıcılara gösterilecek (Filament'in built-in yetkilendirmesi)

## Test Senaryoları

- Widget'ın doğru verileri topladığını test etme
- API çağrısının başarılı olduğunu test etme