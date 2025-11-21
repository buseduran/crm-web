<?php

namespace Database\Seeders;

use App\Models\CardReadLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CardReadLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut kullanıcıları al veya yeni kullanıcılar oluştur
        $users = User::all();

        if ($users->isEmpty()) {
            // Eğer kullanıcı yoksa örnek kullanıcılar oluştur
            $users = User::factory(5)->create();
        }

        // İlk 3 kullanıcıya kart string değerleri ata
        $users->take(3)->each(function ($user, $index) {
            $user->update([
                'card_string' => 'CARD-' . str_pad($user->id, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(md5($user->email), 0, 8)),
            ]);
        });

        // Örnek kart okuma kayıtları oluştur
        $cardReadLogs = [];

        // Son 7 gün için kayıtlar oluştur
        for ($day = 0; $day < 7; $day++) {
            $date = Carbon::now()->subDays($day);

            // Her gün için 5-10 arası kayıt
            $recordsPerDay = rand(5, 10);

            for ($i = 0; $i < $recordsPerDay; $i++) {
                $user = $users->random();
                $cardString = $user->card_string ?? 'UNKNOWN-' . strtoupper(substr(md5($user->email), 0, 12));
                
                // %80 başarılı, %20 başarısız
                $status = rand(1, 100) <= 80;
                
                // Eğer başarısız ise, user_id null olabilir
                $userId = $status ? $user->id : (rand(1, 100) <= 30 ? null : $user->id);

                // Rastgele saat (08:00 - 20:00 arası)
                $hour = rand(8, 20);
                $minute = rand(0, 59);
                $second = rand(0, 59);
                
                $readAt = $date->copy()->setTime($hour, $minute, $second);

                $cardReadLogs[] = [
                    'user_id' => $userId,
                    'card_string' => $cardString,
                    'status' => $status,
                    'read_at' => $readAt,
                    'created_at' => $readAt,
                    'updated_at' => $readAt,
                ];
            }
        }

        // Bilinmeyen kart okumaları (user_id null)
        for ($i = 0; $i < 5; $i++) {
            $date = Carbon::now()->subDays(rand(0, 7));
            $hour = rand(8, 20);
            $minute = rand(0, 59);
            $second = rand(0, 59);
            
            $readAt = $date->copy()->setTime($hour, $minute, $second);

            $cardReadLogs[] = [
                'user_id' => null,
                'card_string' => 'UNKNOWN-' . strtoupper(substr(md5(uniqid()), 0, 12)),
                'status' => false,
                'read_at' => $readAt,
                'created_at' => $readAt,
                'updated_at' => $readAt,
            ];
        }

        // Toplu insert
        CardReadLog::insert($cardReadLogs);

        $this->command->info('Kart okuma kayıtları oluşturuldu: ' . count($cardReadLogs) . ' kayıt');
    }
}
