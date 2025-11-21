<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filter Form --}}
        <x-filament::section>
            <x-slot name="heading">
                Filtreler
            </x-slot>
            <x-slot name="description">
                Ay, yıl ve kullanıcı seçerek aylık görünümü filtreleyin
            </x-slot>
            
            {{ $this->form }}
        </x-filament::section>

        {{-- Summary Cards --}}
        @php
            $summary = $this->getSummaryData();
        @endphp
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-6">
            <x-filament::section>
                <div class="text-2xl font-bold text-primary-600">
                    {{ $summary['total_work_hours'] }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Toplam Çalışma Saati
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-2xl font-bold text-success-600">
                    {{ $summary['present_count'] }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Tam Gün
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-2xl font-bold text-warning-600">
                    {{ $summary['half_day_count'] }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Yarım Gün
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-2xl font-bold text-danger-600">
                    {{ $summary['absent_count'] }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Yok
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-2xl font-bold text-info-600">
                    {{ $summary['leave_count'] }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    İzinli
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-2xl font-bold text-gray-600">
                    {{ $summary['total_records'] }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Toplam Kayıt
                </div>
            </x-filament::section>
        </div>

        {{-- Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Aylık Giriş-Çıkış Kayıtları
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>



