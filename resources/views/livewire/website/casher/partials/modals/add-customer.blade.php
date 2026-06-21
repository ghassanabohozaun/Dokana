    <!-- Add Customer Modal -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'addCustomerModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'addCustomerModal') show = false"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 dark:bg-black/85" x-on:click="show = false"></div>
        <div x-show="show" x-transition.translate.y.bottom class="relative bg-white dark:bg-darkCard w-full max-w-md rounded-t-[2rem] sm:rounded-3xl p-6 shadow-2xl border border-white/10 z-10">
            <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-6 sm:hidden"></div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="ph-fill ph-user-plus text-primary text-2xl"></i>
                    {{ __('notebook.add_new_customer') }}
                </h2>
                <button x-on:click="show = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <form wire:submit.prevent="addCustomer" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.name') }} <span class="text-red-500">*</span></label>
                    <input wire:model="newCustomerName" type="text" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400">
                    @error('newCustomerName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.phone_optional') }}</label>
                    <input wire:model="newCustomerPhone" type="tel" maxlength="10" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400" dir="ltr" placeholder="05...">
                    @error('newCustomerPhone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full bg-primary text-white font-bold rounded-xl py-3.5 mt-4 hover:bg-emerald-600 transition-colors active:scale-[0.98] shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="addCustomer">{{ __('notebook.save_customer_data') }}</span>
                    <span wire:loading wire:target="addCustomer"><i class="ph-bold ph-spinner animate-spin"></i> {{ __('notebook.saving') }}</span>
                </button>
            </form>
        </div>
    </div>
