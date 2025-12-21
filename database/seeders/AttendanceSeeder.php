<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut kullanıcıları al veya yeni kullanıcılar oluştur
        $users = User::all();

        if ($users->isEmpty()) {
            $users = User::factory(5)->create();
        }

        // Eğer yeterli kullanıcı yoksa ekle
        if ($users->count() < 3) {
            $additionalUsers = User::factory(3 - $users->count())->create();
            $users = $users->merge($additionalUsers);
        }

        $createdCount = 0;
        $maxAttempts = 100; // Maksimum deneme sayısı
        $attempts = 0;

        // Son 15 gün için çeşitli kayıtlar oluştur
        $baseDate = Carbon::now()->subDays(15);

        while ($createdCount < 15 && $attempts < $maxAttempts) {
            $attempts++;

            $user = $users->random();
            $dayOffset = rand(0, 14);
            $date = $baseDate->copy()->addDays($dayOffset);

            // Aynı kullanıcı ve tarih kombinasyonunu kontrol et
            $existing = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date->format('Y-m-d'))
                ->exists();

            if ($existing) {
                continue; // Bu kombinasyon zaten varsa tekrar dene
            }

            // Durumları çeşitlendir
            $statuses = ['present', 'present', 'present', 'half_day', 'half_day', 'absent', 'leave', 'holiday'];
            $status = $statuses[array_rand($statuses)];

            $checkIn = null;
            $checkOut = null;
            $notes = null;

            switch ($status) {
                case 'present':
                    // Tam gün: 08:30 - 17:30 arası rastgele
                    $checkInHour = rand(8, 9);
                    $checkInMinute = rand(0, 59);
                    $checkOutHour = rand(17, 18);
                    $checkOutMinute = rand(0, 59);
                    $checkIn = sprintf('%02d:%02d:00', $checkInHour, $checkInMinute);
                    $checkOut = sprintf('%02d:%02d:00', $checkOutHour, $checkOutMinute);
                    break;

                case 'half_day':
                    // Yarım gün: 09:00 - 13:00 arası
                    $checkInHour = rand(8, 9);
                    $checkInMinute = rand(0, 30);
                    $checkOutHour = rand(12, 13);
                    $checkOutMinute = rand(0, 30);
                    $checkIn = sprintf('%02d:%02d:00', $checkInHour, $checkInMinute);
                    $checkOut = sprintf('%02d:%02d:00', $checkOutHour, $checkOutMinute);
                    $notes = 'Yarım gün çalışma';
                    break;

                case 'absent':
                    // Yok: check_in ve check_out yok
                    $notes = 'Hastalık';
                    break;

                case 'leave':
                    // İzinli: check_in ve check_out yok
                    $notes = 'Yıllık izin';
                    break;

                case 'holiday':
                    // Tatil: check_in ve check_out yok
                    $notes = 'Resmi tatil';
                    break;
            }

            // work_duration'ı manuel hesapla
            $workDuration = null;
            if ($checkIn && $checkOut) {
                $checkInTime = Carbon::createFromTimeString($checkIn);
                $checkOutTime = Carbon::createFromTimeString($checkOut);

                if ($checkOutTime->lt($checkInTime)) {
                    $checkOutTime->addDay();
                }

                $workDuration = $checkInTime->diffInMinutes($checkOutTime);
            }

            // Kaydı oluştur
            try {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'work_duration' => $workDuration,
                    'status' => $status,
                    'notes' => $notes,
                    'created_at' => $date->copy()->setTime(8, 0, 0),
                    'updated_at' => $date->copy()->setTime(8, 0, 0),
                ]);

                $createdCount++;
            } catch (\Illuminate\Database\QueryException $e) {
                // Unique constraint hatası olursa devam et
                if (str_contains($e->getMessage(), 'unique constraint')) {
                    continue;
                }
                throw $e;
            }
        }

        $this->command->info('Giriş-çıkış kayıtları oluşturuldu: '.$createdCount.' kayıt');
    }
}


