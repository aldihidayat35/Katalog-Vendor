<div
    x-data="{
        show: false,
        action: '',
        title: '',
        open(detail) {
            this.action = detail.action || '';
            this.title = detail.title || 'data ini';
            this.show = true;
        },
        close() {
            this.show = false;
        }
    }"
    x-on:confirm-delete.window="open($event.detail)"
    x-on:keydown.escape.window="close()"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
    style="display: none;"
>
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="fixed inset-0 bg-charcoal/60 backdrop-blur-sm transition-opacity"
    ></div>

    <!-- Modal Content -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-stone-200/80 z-10 text-center"
    >
        <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>

        <h3 class="text-xl font-bold font-serif text-charcoal-900 mb-2">Konfirmasi Hapus</h3>
        <p class="text-sm text-stone-600 mb-6">
            Apakah Anda yakin ingin menghapus <strong x-text="title" class="text-charcoal-900"></strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <form :action="action" method="POST" class="flex items-center justify-center gap-3">
            @csrf
            @method('DELETE')
            
            <button
                type="button"
                @click="close()"
                class="px-5 py-2.5 rounded-xl border border-stone-300 text-sm font-semibold text-stone-700 hover:bg-stone-50 transition-colors"
            >
                Batal
            </button>
            <button
                type="submit"
                class="px-5 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 shadow-sm transition-colors"
            >
                Ya, Hapus
            </button>
        </form>
    </div>
</div>
