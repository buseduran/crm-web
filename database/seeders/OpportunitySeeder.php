<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Opportunity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = collect([
            [
                'name' => 'Acme Teknoloji',
                'email' => 'iletisim@acmeteknoloji.test',
                'phone' => '+90 212 555 0101',
                'company' => 'Acme Teknoloji',
                'city' => 'İstanbul',
                'country' => 'Türkiye',
                'status' => 'active',
                'notes' => 'Bulut çözümleriyle ilgileniyorlar.',
            ],
            [
                'name' => 'Nova Lojistik',
                'email' => 'info@novaloji.test',
                'phone' => '+90 216 555 0202',
                'company' => 'Nova Lojistik',
                'city' => 'İzmir',
                'country' => 'Türkiye',
                'status' => 'prospect',
                'notes' => 'Yeni CRM arayışında.',
            ],
            [
                'name' => 'Mavi Deniz A.Ş.',
                'email' => 'satis@mavideniz.test',
                'phone' => '+90 232 555 0303',
                'company' => 'Mavi Deniz A.Ş.',
                'city' => 'Bursa',
                'country' => 'Türkiye',
                'status' => 'active',
                'notes' => 'Referans üzerinden ulaşıldı.',
            ],
        ])->map(fn (array $data) => Customer::firstOrCreate(
            ['email' => $data['email']],
            $data
        ));

        $opportunities = [
            [
                'title' => 'CRM Geçiş Projesi',
                'description' => 'Mevcut sistemi Filament tabanlı CRM ile değiştirme.',
                'value' => 185000,
                'stage' => 'proposal',
                'priority' => 'high',
                'expected_close_date' => Carbon::now()->addWeeks(4),
                'notes' => 'Teklif gönderildi, teknik sunum planlandı.',
            ],
            [
                'title' => 'Satış Otomasyon Paketi',
                'description' => 'Satış pipeline ve teklif otomasyonu kurulumu.',
                'value' => 98000,
                'stage' => 'negotiation',
                'priority' => 'urgent',
                'expected_close_date' => Carbon::now()->addWeeks(2),
                'notes' => 'Fiyat indirimi talep edildi.',
            ],
            [
                'title' => 'Müşteri Sadakat Modülü',
                'description' => 'Puan ve kampanya yönetimi entegrasyonu.',
                'value' => 64000,
                'stage' => 'prospecting',
                'priority' => 'medium',
                'expected_close_date' => Carbon::now()->addWeeks(6),
                'notes' => 'Demo versiyonuna olumlu geri bildirim geldi.',
            ],
        ];

        foreach ($customers as $index => $customer) {
            Opportunity::create(array_merge(
                ['customer_id' => $customer->id],
                $opportunities[$index]
            ));
        }
    }
}


