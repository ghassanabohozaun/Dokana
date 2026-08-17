    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: false }" x-show="show"
        x-on:open-modal.window="if ($event.detail.id === 'deleteConfirmModal') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'deleteConfirmModal') show = false"
        style="display: none; z-index: 120;" class="fixed inset-0 flex items-center justify-center p-4 sm:p-0" x-cloak>

        <!-- Backdrop -->
        <div x-show="show" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
            x-on:click="if (!isDeletingItem) show = false"></div>

        <!-- Modal Panel -->
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
            class="relative bg-white dark:bg-darkCard rounded-3xl shadow-2xl overflow-hidden w-full max-w-sm sm:max-w-md border border-gray-100 dark:border-gray-800">

            <!-- Decorative Top Gradient -->
            <div class="h-1.5 w-full bg-gradient-to-r from-red-500 to-rose-600"></div>

            <div class="p-6 sm:p-8 text-center">
                <!-- Icon Container -->
                <div
                    class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 dark:bg-red-900/20 mb-6 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                    <div
                        class="h-14 w-14 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center relative">
                        <i class="ph-fill ph-trash text-3xl text-red-600 dark:text-red-500"></i>
                        <!-- Ping animation -->
                        <div class="absolute inset-0 rounded-full border border-red-500 animate-ping opacity-20"></div>
                    </div>
                </div>

                <!-- Text Content -->
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2" id="modal-title" x-text="deleteModalTitle || '{{ __('notebook.are_you_sure') ?? 'هل أنت متأكد؟' }}'">
                    {{ __('notebook.are_you_sure') ?? 'هل أنت متأكد؟' }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-8 leading-relaxed" x-text="deleteModalMessage || '{{ __('notebook.confirm_delete_tx') ?? 'هل أنت متأكد من حذف هذه الحركة؟ لا يمكن التراجع عن هذا الإجراء.' }}'">
                    {{ __('notebook.confirm_delete_tx') ?? 'هل أنت متأكد من حذف هذه الحركة؟ لا يمكن التراجع عن هذا الإجراء.' }}
                </p>

                <!-- Action Buttons: "نعم، احذف" FIRST (on the right in RTL), "إلغاء" SECOND (on the left in RTL) -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" @click="executeDelete()" :disabled="isDeletingItem"
                        class="flex-1 w-full inline-flex items-center justify-center gap-2 rounded-xl border-0 bg-gradient-to-r from-red-500 to-rose-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-red-500/30 hover:shadow-red-500/50 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-red-500/50 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                        <i x-show="isDeletingItem" class="ph-bold ph-spinner-gap animate-spin text-lg" x-cloak></i>
                        <span>{{ __('notebook.yes_delete') ?? 'نعم، احذف' }}</span>
                    </button>
                    <button type="button" @click="show = false" :disabled="isDeletingItem"
                        class="flex-1 w-full inline-flex justify-center rounded-xl border-0 bg-gray-100 dark:bg-gray-800 px-4 py-3.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 transition-all disabled:opacity-60">
                        {{ __('notebook.cancel') ?? 'إلغاء' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
