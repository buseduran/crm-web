<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ $this->getHeading() }}
        </x-slot>

        <x-slot name="description">
            {{ $this->getDescription() }}
        </x-slot>

        <x-slot name="headerActions">
            <x-filament::button
                wire:click="refresh"
                wire:loading.attr="disabled"
                size="sm"
                icon="heroicon-o-arrow-path"
                tooltip="Rapor günde 1 kez otomatik olarak güncellenir"
            >
                <span wire:loading.remove wire:target="refresh">Yenile</span>
                <span wire:loading wire:target="refresh">Yükleniyor...</span>
            </x-filament::button>
        </x-slot>

        <div style="max-height: 24rem; overflow-y: auto;" wire:init="loadReport">
            <div class="space-y-4">
                @if($this->error)
                    <div class="rounded-lg bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800 p-4">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-danger-600 dark:text-danger-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-danger-800 dark:text-danger-200">
                                    {{ $this->error }}
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($this->report)
                    <div class="prose prose-sm max-w-none dark:prose-invert">
                        <div class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! $this->getFormattedReport() !!}
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            <x-filament::loading-indicator class="mx-auto h-6 w-6 text-primary-500" />
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Rapor yükleniyor...
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

