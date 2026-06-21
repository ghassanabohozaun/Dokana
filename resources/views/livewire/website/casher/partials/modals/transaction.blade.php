    <!-- Add Transaction Modal -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'transactionModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'transactionModal') show = false"
         style="display: none;"
         class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 dark:bg-black/85" x-on:click="show = false"></div>
        <div x-show="show" x-transition.translate.y.bottom class="relative bg-white dark:bg-darkCard w-full max-w-md rounded-t-[2rem] sm:rounded-3xl p-6 shadow-2xl border border-white/10 z-10">
            <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-6 sm:hidden"></div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="ph-fill {{ $txType === 'debt' ? 'ph-minus-circle text-red-500' : 'ph-plus-circle text-emerald-500' }} text-2xl"></i>
                    {{ $editingTxId ? ($txType === 'debt' ? __('notebook.edit_debt') : __('notebook.edit_payment')) : ($txType === 'debt' ? __('notebook.add_new_debt') : __('notebook.add_new_payment')) }}
                </h2>
                <button x-on:click="show = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <form wire:submit.prevent="addTransaction" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.amount_currency') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input wire:model="txAmount" type="number" required min="0.01" step="0.01" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl pe-12 ps-4 py-4 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-3xl font-black transition-all text-gray-900 dark:text-white text-start" placeholder="0.00">
                        <span class="absolute end-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-lg">₪</span>
                    </div>
                    @error('txAmount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.transaction_date') }} <span class="text-red-500">*</span></label>
                    <div class="relative" 
                         wire:ignore
                         x-data="{
                             init() {
                                 var $input = $(this.$refs.input);
                                 
                                 // Watch parent's 'show' variable to initialize when modal opens
                                 this.$watch('show', value => {
                                     if (value) {
                                         $input.datepicker({
                                             format: 'yyyy-mm-dd',
                                             autoclose: true,
                                             todayHighlight: true,
                                             language: '{{ app()->getLocale() }}',
                                             rtl: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }},
                                             orientation: 'bottom auto',
                                             container: '#transactionModal',
                                             templates: {
                                                 leftArrow: '{{ app()->getLocale() == "ar" ? "»" : "«" }}',
                                                 rightArrow: '{{ app()->getLocale() == "ar" ? "«" : "»" }}'
                                             }
                                         }).on('changeDate', (e) => {
                                             var dateVal = e.format('yyyy-mm-dd');
                                             this.$wire.set('txDate', dateVal);
                                         });

                                         // Initial sync
                                         var initialVal = this.$wire.get('txDate');
                                         if (initialVal) {
                                             $input.val(initialVal);
                                             $input.datepicker('update', initialVal);
                                         }
                                     } else {
                                         if ($input.data('datepicker')) {
                                             $input.datepicker('destroy');
                                         }
                                     }
                                 });

                                 // Watch for Livewire changes to update datepicker
                                 this.$watch('$wire.txDate', value => {
                                     $input.val(value);
                                     if ($input.data('datepicker')) {
                                         $input.datepicker('update', value);
                                     }
                                 });
                             }
                         }">
                        <!-- Icon -->
                        <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-primary text-xl {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }}">
                            <i class="ph-bold ph-calendar"></i>
                        </div>
                        <!-- Input -->
                        <input x-ref="input"
                               type="text" 
                               required 
                               readonly
                               inputmode="none"
                               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-gray-900 dark:text-white font-medium cursor-pointer {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }}"
                               placeholder="YYYY-MM-DD"
                               autocomplete="off">
                    </div>
                    @error('txDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.notes_optional') }}</label>
                    <textarea wire:model="txDescription" rows="4" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white resize-none" placeholder="{{ __('notebook.example_notes') }}"></textarea>
                    @error('txDescription') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full text-white font-bold rounded-xl py-4 mt-4 transition-all active:scale-[0.98] shadow-lg flex items-center justify-center gap-2 {{ $txType === 'debt' ? 'bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 shadow-red-500/30 ring-red-500/50' : 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-emerald-500/30 ring-emerald-500/50' }} focus:ring-4 focus:outline-none overflow-hidden relative group">
                    <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                    <span wire:loading.remove wire:target="addTransaction" class="relative">{{ $editingTxId ? __('notebook.update') : __('notebook.register') }}</span>
                    <span wire:loading wire:target="addTransaction" class="relative"><i class="ph-bold ph-spinner animate-spin"></i> {{ $editingTxId ? __('notebook.updating') : __('notebook.registering') }}</span>
                </button>
            </form>
        </div>
    </div>
