<div x-data="{ copied: false }">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                API Token
            </label>
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 mb-3">
                <code x-ref="tokenText" class="block text-sm font-mono text-gray-900 dark:text-gray-100 break-all select-all leading-relaxed">{{ $token }}</code>
            </div>
        </div>
        
        <div class="flex gap-2">
            <button
                type="button"
                @click="
                    const text = $refs.tokenText.textContent.trim();
                    navigator.clipboard.writeText(text).then(() => {
                        copied = true;
                        setTimeout(() => { copied = false; }, 2500);
                    }).catch(err => {
                        console.error('Kopyalama hatası:', err);
                        try {
                            const range = document.createRange();
                            range.selectNode($refs.tokenText);
                            window.getSelection().removeAllRanges();
                            window.getSelection().addRange(range);
                            document.execCommand('copy');
                            window.getSelection().removeAllRanges();
                            copied = true;
                            setTimeout(() => { copied = false; }, 2500);
                        } catch (e) {
                            alert('Token kopyalanamadı. Lütfen manuel olarak seçip kopyalayın.');
                        }
                    });
                "
                :class="copied ? 'bg-success-600 hover:bg-success-700 focus:ring-success-500' : 'bg-primary-600 hover:bg-primary-700 focus:ring-primary-500'"
                class="flex-1 inline-flex items-center justify-center px-4 py-3 text-white text-sm font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-sm"
            >
                <svg x-show="!copied" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <svg x-show="copied" x-cloak class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span x-text="copied ? 'Kopyalandı!' : 'Kopyala'">Kopyala</span>
            </button>
        </div>
    </div>
</div>




