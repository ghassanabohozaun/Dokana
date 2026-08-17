<!-- Add Supplier Payment Overlay -->
<div x-data="{ show: false, openBankSelect: false, openInvoiceSelect: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'addSupplierPaymentModal') { show = true; openBankSelect = false; openInvoiceSelect = false; }"
     x-on:close-modal.window="if ($event.detail.id === 'addSupplierPaymentModal') { show = false; openBankSelect = false; openInvoiceSelect = false; }"
     style="display: none;"
     class="overlay-panel flex justify-center"
     x-transition:enter="transform transition ease-out duration-200"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transform transition ease-in duration-150"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     x-cloak>
     
    <div class="w-full md:max-w-3xl min-h-screen flex flex-col bg-gray-50 dark:bg-[#0b1121] shadow-2xl relative">
        <div class="flex flex-col h-screen">
            <!-- Header -->
            <div class="p-5 border-b dark:border-gray-800 flex items-center bg-white dark:bg-darkCard z-10 shrink-0 sticky top-0">
                <button x-on:click="show = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300 mr-2 rtl:ml-2 rtl:mr-0 rtl:-scale-x-100">
                    <i class="ph-bold ph-arrow-left text-xl"></i>
                </button>
                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl shrink-0 mr-3 rtl:mr-0 rtl:ml-3 bg-amber-500/10 text-amber-600 dark:text-amber-400">
                    <i class="ph-bold ph-hand-coins text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-lg text-gray-900 dark:text-white">
                        <span>{{ __('notebook.payout_to_supplier') }}</span>
                    </h2>
                    <template x-if="activeSupplier">
                        <p class="text-xs text-gray-400 font-medium" x-text="'المورد: ' + activeSupplier.name"></p>
                    </template>
                </div>
            </div>
            
            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-white dark:bg-[#0b1121] custom-scrollbar relative">
                <form @submit.prevent="saveSupplierPayment()" novalidate class="space-y-4">
                    
                    <!-- Bank Account Dropdown -->
                    <div class="relative">
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">
                            {{ __('notebook.bank_account') }} <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="relative">
                            <button type="button" 
                                    @click="openBankSelect = !openBankSelect; openInvoiceSelect = false" 
                                    @click.away="openBankSelect = false" 
                                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none text-gray-900 dark:text-white font-medium flex items-center justify-between transition-all hover:border-amber-500/50 px-4">
                                <div class="flex items-center gap-2">
                                    <i class="ph-fill ph-bank text-amber-500 text-lg"></i>
                                    <span x-text="supplierPaymentBankAccountId ? (casherConfig.storeAccounts.find(a => a.id == supplierPaymentBankAccountId)?.name || '{{ __('general.select_from_list') }}') : '{{ __('general.select_from_list') }}'"></span>
                                </div>
                                <i class="ph-bold ph-caret-down text-gray-400 transition-transform" :class="openBankSelect ? 'rotate-180 text-amber-500' : ''"></i>
                            </button>

                            <!-- Custom Dropdown Menu -->
                            <div x-show="openBankSelect" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 py-2 max-h-60 overflow-y-auto custom-scrollbar" x-cloak>
                                @foreach($storeBankAccounts as $account)
                                    @php
                                        $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                        $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                    @endphp
                                    <button type="button" 
                                            @click="supplierPaymentBankAccountId = '{{ $account->id }}'; openBankSelect = false;" 
                                            class="w-full text-start px-5 py-3 hover:bg-amber-50/50 dark:hover:bg-gray-700/50 transition-colors flex items-center justify-between group border-b border-gray-50 dark:border-gray-700/30 last:border-0"
                                            :class="supplierPaymentBankAccountId == '{{ $account->id }}' ? 'bg-amber-50/80 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 font-bold' : 'text-gray-700 dark:text-gray-300'">
                                        <div class="flex items-center gap-2">
                                            <i class="ph-bold ph-credit-card text-lg text-amber-500"></i>
                                            <span>{{ $accountName }}</span>
                                        </div>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300"
                                              x-text="'رصيد: ' + Number(bankBalances['{{ $account->id }}'] || 0).toFixed(1)">
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Available Balance Alert -->
                        <template x-if="supplierPaymentBankAccountId">
                            <div class="mt-2 text-xs font-bold flex items-center justify-between text-gray-500 dark:text-gray-400 px-1">
                                <span>{{ __('notebook.available_balance') }}</span>
                                <span class="text-amber-600 dark:text-amber-400 font-black" x-text="Number(bankBalances[supplierPaymentBankAccountId] || 0).toFixed(1) + ' {{ __('notebook.currency') }}'"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Link to Due Invoice (Required) -->
                    <div class="relative">
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">
                            {{ __('notebook.select_invoice') }} <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- If no unpaid invoices -->
                        <template x-if="supplierUnpaidInvoices.length === 0">
                            <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 text-amber-700 dark:text-amber-300 text-xs font-bold flex items-center gap-2">
                                <i class="ph-fill ph-check-circle text-base shrink-0"></i>
                                <span>{{ __('notebook.all_invoices_settled') }}</span>
                            </div>
                        </template>

                        <!-- If unpaid invoices exist -->
                        <template x-if="supplierUnpaidInvoices.length > 0">
                            <div class="relative">
                                <button type="button" 
                                        @click="openInvoiceSelect = !openInvoiceSelect; openBankSelect = false" 
                                        @click.away="openInvoiceSelect = false" 
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none text-gray-900 dark:text-white font-medium flex items-center justify-between transition-all hover:border-amber-500/50 px-4">
                                    <div class="flex items-center gap-2">
                                        <i class="ph-fill ph-receipt text-blue-500 text-lg"></i>
                                        <span x-text="supplierPaymentInvoiceId ? ('فاتورة: ' + (supplierUnpaidInvoices.find(inv => inv.id == supplierPaymentInvoiceId)?.invoice_number || '') + ' (متبقي: ' + Number(supplierUnpaidInvoices.find(inv => inv.id == supplierPaymentInvoiceId)?.remaining_amount || 0).toFixed(1) + ' ₪)') : '{{ __('notebook.select_invoice_required') }}'"></span>
                                    </div>
                                    <i class="ph-bold ph-caret-down text-gray-400 transition-transform" :class="openInvoiceSelect ? 'rotate-180 text-amber-500' : ''"></i>
                                </button>

                                <!-- Invoice Dropdown Menu -->
                                <div x-show="openInvoiceSelect" 
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 py-2 max-h-60 overflow-y-auto custom-scrollbar" x-cloak>

                                    <template x-for="inv in supplierUnpaidInvoices" :key="inv.id">
                                        <button type="button" 
                                                @click="supplierPaymentInvoiceId = inv.id; supplierPaymentAmount = Number(inv.remaining_amount).toFixed(1); openInvoiceSelect = false;" 
                                                class="w-full text-start px-5 py-3 hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors flex items-center justify-between border-b border-gray-50 dark:border-gray-700/30 last:border-0"
                                                :class="supplierPaymentInvoiceId == inv.id ? 'bg-blue-50/80 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-700 dark:text-gray-300'">
                                            <div class="flex items-center gap-2">
                                                <i class="ph-bold ph-receipt text-blue-500"></i>
                                                <span x-text="'فاتورة ' + inv.invoice_number"></span>
                                            </div>
                                            <span class="text-xs font-bold text-amber-600 dark:text-amber-400"
                                                  x-text="'متبقي: ' + Number(inv.remaining_amount).toFixed(1) + ' ₪'">
                                            </span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Selected Invoice Details Card -->
                        <template x-if="selectedSupplierInvoice">
                            <div class="mt-2.5 p-3 rounded-xl bg-blue-50/60 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/40 text-xs flex items-center justify-between font-bold text-blue-800 dark:text-blue-300">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">إجمالي الفاتورة: </span>
                                    <span dir="ltr" x-text="Number(selectedSupplierInvoice.total_amount).toFixed(1) + ' ₪'"></span>
                                </div>
                                <div>
                                    <span class="text-amber-700 dark:text-amber-400">المتبقي للدفع: </span>
                                    <span dir="ltr" class="font-black text-amber-600 dark:text-amber-400" x-text="Number(selectedSupplierInvoice.remaining_amount).toFixed(1) + ' ₪'"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Payment Amount -->
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.payout_amount') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input x-model="supplierPaymentAmount" 
                                   @input="supplierPaymentAmount = sanitizeAmountInput(supplierPaymentAmount)" 
                                   type="text" 
                                   inputmode="decimal" 
                                   required 
                                   placeholder="0.00" 
                                   class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-bold text-lg text-gray-900 dark:text-white placeholder-gray-400">
                            <span class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} text-xs font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                        </div>

                        <!-- Amount Exceeds Invoice Remaining Warning -->
                        <template x-if="selectedInvoiceRemaining !== null && Number(supplierPaymentAmount) > selectedInvoiceRemaining">
                            <div class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1 bg-red-50 dark:bg-red-900/20 p-2.5 rounded-lg border border-red-100 dark:border-red-900/30">
                                <i class="ph-fill ph-warning-circle text-base shrink-0"></i>
                                <span x-text="'عذراً، المبلغ المدخل يتجاوز المتبقي من الفاتورة (' + Number(selectedInvoiceRemaining).toFixed(1) + ' ₪)'"></span>
                            </div>
                        </template>

                        <!-- Insufficient Bank Balance Warning -->
                        <template x-if="supplierPaymentBankAccountId && Number(supplierPaymentAmount) > Number(bankBalances[supplierPaymentBankAccountId] || 0)">
                            <div class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1 bg-red-50 dark:bg-red-900/20 p-2.5 rounded-lg border border-red-100 dark:border-red-900/30">
                                <i class="ph-fill ph-warning-circle text-base shrink-0"></i>
                                <span>{{ __('notebook.amount_exceeds_balance') }}</span>
                            </div>
                        </template>
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.payout_date') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-amber-500 text-xl {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }}">
                                <i class="ph-bold ph-calendar"></i>
                            </div>
                            <input type="text" 
                                   required 
                                   x-model="supplierPaymentDate"
                                   x-init="
                                       flatpickr($el, {
                                           dateFormat: 'Y-m-d',
                                           locale: '{{ app()->getLocale() == 'ar' ? 'ar' : 'en' }}',
                                           disableMobile: true,
                                           onChange: function(selectedDates, dateStr, instance) {
                                               supplierPaymentDate = dateStr;
                                               $el.dispatchEvent(new Event('input'));
                                           }
                                       });
                                   "
                                   class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none text-gray-900 dark:text-white font-medium cursor-pointer {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }}">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.notes_optional') }}</label>
                        <textarea x-model="supplierPaymentNotes" rows="2" placeholder="{{ __('notebook.example_notes') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400 resize-none"></textarea>
                    </div>
                    
                    <button type="submit" class="hidden"></button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="p-4 md:p-6 border-t dark:border-gray-800 bg-white dark:bg-darkCard shrink-0 sticky bottom-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button type="button" 
                        @click="saveSupplierPayment()"
                        x-bind:disabled="isSavingSupplierPayment || !supplierPaymentInvoiceId || !supplierPaymentAmount || Number(supplierPaymentAmount) <= 0 || (selectedInvoiceRemaining !== null && Number(supplierPaymentAmount) > selectedInvoiceRemaining) || (supplierPaymentBankAccountId && Number(supplierPaymentAmount) > Number(bankBalances[supplierPaymentBankAccountId] || 0))"
                        class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold rounded-xl py-4 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg shadow-amber-500/25 focus:ring-4 focus:outline-none ring-amber-500/30 active:scale-95">
                    <i x-show="isSavingSupplierPayment" class="ph-bold ph-spinner-gap animate-spin text-xl relative z-10" x-cloak></i>
                    <span x-show="!isSavingSupplierPayment">{{ __('notebook.payout_to_supplier') }}</span>
                    <span x-show="isSavingSupplierPayment">{{ __('notebook.registering') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
