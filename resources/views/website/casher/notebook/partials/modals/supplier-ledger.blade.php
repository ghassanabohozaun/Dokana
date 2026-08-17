<!-- Supplier Ledger Overlay -->
<div x-data="{ show: false, ledgerTab: 'invoices' }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'supplierLedgerModal') { show = true; ledgerTab = 'invoices'; }"
     x-on:close-modal.window="if ($event.detail.id === 'supplierLedgerModal') show = false"
     style="display: none;"
     class="overlay-panel overlay-panel-detail flex justify-center"
     x-transition:enter="transform transition ease-out duration-200"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transform transition ease-in duration-150"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     x-cloak>
     
    <div class="w-full md:max-w-3xl min-h-screen flex flex-col bg-gray-50 dark:bg-[#0b1121] shadow-2xl relative">
        <template x-if="activeSupplier">
            <div class="flex flex-col h-screen">
                <!-- Header -->
                <div class="p-5 border-b dark:border-gray-800 flex justify-between items-center bg-white dark:bg-darkCard z-10 shrink-0 sticky top-0">
                    <div class="flex items-center gap-3">
                        <button x-on:click="show = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300 mr-2 rtl:ml-2 rtl:mr-0 rtl:-scale-x-100">
                            <i class="ph-bold ph-arrow-left text-xl"></i>
                        </button>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl shrink-0 border border-amber-200/40">
                            <span x-text="activeSupplier.name ? activeSupplier.name.substring(0, 1) : '-'"></span>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                                <span x-text="activeSupplier.name"></span>

                                <button @click="openEditSupplierModal()" class="text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 dark:text-amber-400 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 transition-colors w-7 h-7 flex items-center justify-center rounded-full shadow-sm border border-amber-100 dark:border-amber-800/50 shrink-0">
                                    <i class="ph-bold ph-pencil-simple text-sm"></i>
                                </button>
                            </h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium flex flex-wrap items-center gap-x-2.5 gap-y-1">
                                <span class="flex items-center gap-1"><i class="ph-fill ph-phone text-xs text-amber-500"></i> <span x-text="activeSupplier.mobile || '-'"></span></span>
                                
                                <template x-if="activeSupplier.bank_name && activeSupplier.bank_name !== '-'">
                                    <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded-md font-semibold text-[11px]">
                                        <i class="ph-fill ph-bank text-xs"></i>
                                        <span x-text="activeSupplier.bank_name + (activeSupplier.account_number && activeSupplier.account_number !== '-' ? ' (' + activeSupplier.account_number + ')' : '')"></span>
                                    </span>
                                </template>

                                <template x-if="activeSupplier.address">
                                    <span class="flex items-center gap-1 text-gray-400">• <i class="ph-fill ph-map-pin text-xs"></i> <span x-text="activeSupplier.address"></span></span>
                                </template>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Financial Summary Bar & Action Buttons -->
                <div class="p-4 bg-white dark:bg-darkCard shadow-sm z-10 relative shrink-0 border-b dark:border-gray-800">
                    <!-- Top stats cards -->
                    <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                        <div class="bg-gray-50 dark:bg-gray-800/50 p-2.5 rounded-xl border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 block">{{ __('notebook.total_purchases') }}</span>
                            <span class="text-sm sm:text-base font-black text-gray-800 dark:text-gray-100" dir="ltr" x-text="Number(supplierLedgerSummary?.totalPurchases || 0).toFixed(1)"></span>
                        </div>
                        <div class="bg-emerald-50/60 dark:bg-emerald-900/20 p-2.5 rounded-xl border border-emerald-100/80 dark:border-emerald-800/30">
                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 block">{{ __('notebook.total_paid_to_supplier') }}</span>
                            <span class="text-sm sm:text-base font-black text-emerald-600 dark:text-emerald-400" dir="ltr" x-text="Number(supplierLedgerSummary?.totalPaid || 0).toFixed(1)"></span>
                        </div>
                        <div class="bg-amber-50/60 dark:bg-amber-900/20 p-2.5 rounded-xl border border-amber-100/80 dark:border-amber-800/30">
                            <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 block">{{ __('notebook.remaining_to_supplier') }}</span>
                            <span class="text-sm sm:text-base font-black text-amber-600 dark:text-amber-400" dir="ltr" x-text="Number(supplierLedgerSummary?.balanceDue || 0).toFixed(1)"></span>
                        </div>
                    </div>

                    <!-- Action Button: Single New Invoice Button matching customer ledger style -->
                    <div>
                        <button @click="openAddSupplierInvoiceModal()"
                            class="w-full flex items-center justify-center gap-2 bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 py-2.5 px-4 rounded-xl font-bold transition-all border border-blue-100 dark:border-blue-900/30 text-sm hover:bg-blue-100 dark:hover:bg-blue-900/40 active:scale-95 group">
                            <i class="ph-bold ph-plus text-base"></i>
                            <span>{{ __('notebook.new_invoice') }}</span>
                        </button>
                    </div>

                    <!-- Tabs Header -->
                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <button @click="ledgerTab = 'invoices'"
                                class="flex-1 py-2 text-xs font-bold rounded-lg transition-all"
                                :class="ledgerTab === 'invoices' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'">
                            {{ __('notebook.supplier_invoices') }} (<span x-text="supplierLedgerInvoices.length"></span>)
                        </button>
                        <button @click="ledgerTab = 'payments'"
                                class="flex-1 py-2 text-xs font-bold rounded-lg transition-all"
                                :class="ledgerTab === 'payments' ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'">
                            {{ __('notebook.supplier_payments') }} (<span x-text="supplierLedgerPayments.length"></span>)
                        </button>
                    </div>
                </div>

                <!-- Ledger Body -->
                <div class="flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-[#0b1121] custom-scrollbar relative">
                    <!-- Loading Indicator -->
                    <div x-show="isSupplierLedgerLoading" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center backdrop-blur-sm" x-cloak>
                        <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-amber-500"></i>
                    </div>

                    <!-- Invoices Tab Content -->
                    <div x-show="ledgerTab === 'invoices'">
                        <!-- Empty Invoices -->
                        <template x-if="supplierLedgerInvoices.length === 0 && !isSupplierLedgerLoading">
                            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                                <i class="ph-duotone ph-receipt text-5xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                <p class="font-bold text-gray-500 dark:text-gray-400 text-sm">{{ __('notebook.no_registered_transactions') }}</p>
                                <button @click="openAddSupplierInvoiceModal()" class="mt-3 px-4 py-2 bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 rounded-lg text-xs font-bold">
                                    {{ __('notebook.add_invoice') }}
                                </button>
                            </div>
                        </template>

                        <!-- Invoices List -->
                        <div class="space-y-3" x-show="supplierLedgerInvoices.length > 0">
                            <template x-for="inv in supplierLedgerInvoices" :key="inv.id">
                                <div class="p-4 rounded-2xl bg-white dark:bg-darkCard border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="inv.invoice_number"></span>
                                                <!-- Status Badge -->
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                                      :class="inv.status === 'paid' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : (inv.status === 'partially_paid' ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400')"
                                                      x-text="inv.status === 'paid' ? '{{ __('notebook.status_paid') }}' : (inv.status === 'partially_paid' ? '{{ __('notebook.status_partially_paid') }}' : '{{ __('notebook.status_unpaid') }}')">
                                                </span>
                                            </div>
                                            <span class="text-[11px] text-gray-400 mt-1 block" x-text="formatDateTime(inv.invoice_date)"></span>
                                        </div>

                                        <div class="text-left">
                                            <span class="font-black text-base text-gray-900 dark:text-white block" x-text="Number(inv.total_amount).toFixed(1) + ' {{ __('notebook.currency') }}'"></span>
                                            <template x-if="inv.status !== 'paid'">
                                                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-bold block" x-text="'{{ __('notebook.remaining') }}: ' + Number(inv.remaining_amount).toFixed(1)"></span>
                                            </template>
                                        </div>
                                    </div>

                                    <template x-if="inv.notes">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/40 p-2 rounded-lg" x-text="inv.notes"></p>
                                    </template>

                                    <!-- Bottom actions -->
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-50 dark:border-gray-800/60 text-xs">
                                        <span class="text-[11px] text-gray-400" x-text="inv.cashier_name ? '{{ __('notebook.added_by') }}: ' + inv.cashier_name : ''"></span>
                                        
                                        <div class="flex items-center gap-2">
                                            <!-- Payout for this invoice button -->
                                            <template x-if="inv.status !== 'paid'">
                                                <button @click="openAddSupplierPaymentModal(inv.id)" class="px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 font-bold text-xs hover:bg-amber-100 dark:hover:bg-amber-900/50 border border-amber-200/60 dark:border-amber-800/40 flex items-center gap-1.5 transition-all active:scale-95">
                                                    <i class="ph-bold ph-hand-coins text-sm"></i>
                                                    <span>{{ __('notebook.payout_to_supplier') }}</span>
                                                </button>
                                            </template>

                                            <!-- Delete button (only if paid_amount == 0) -->
                                            <template x-if="Number(inv.paid_amount || 0) == 0">
                                                <button @click="confirmDeleteSupplierInvoice(inv.id)" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-red-50 dark:bg-gray-800 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-600 flex items-center justify-center transition-colors" title="{{ __('notebook.delete') }}">
                                                    <i class="ph-bold ph-trash text-xs"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Payments Tab Content -->
                    <div x-show="ledgerTab === 'payments'">
                        <!-- Empty Payments -->
                        <template x-if="supplierLedgerPayments.length === 0 && !isSupplierLedgerLoading">
                            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                                <i class="ph-duotone ph-hand-coins text-5xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                <p class="font-bold text-gray-500 dark:text-gray-400 text-sm">{{ __('notebook.no_registered_transactions') }}</p>
                                <button @click="openAddSupplierPaymentModal()" class="mt-3 px-4 py-2 bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 rounded-lg text-xs font-bold">
                                    {{ __('notebook.payout_to_supplier') }}
                                </button>
                            </div>
                        </template>

                        <!-- Payments List -->
                        <div class="space-y-3" x-show="supplierLedgerPayments.length > 0">
                            <template x-for="p in supplierLedgerPayments" :key="p.id">
                                <div class="p-4 rounded-2xl bg-white dark:bg-darkCard border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-black text-emerald-600 dark:text-emerald-400 text-base" x-text="'- ' + Number(p.amount).toFixed(1) + ' {{ __('notebook.currency') }}'"></span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                                    {{ __('notebook.payment') }}
                                                </span>
                                            </div>
                                            <span class="text-[11px] text-gray-400 mt-1 block" x-text="formatDateTime(p.payment_date)"></span>
                                        </div>

                                        <div class="text-left">
                                            <template x-if="p.invoice">
                                                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-md" x-text="'فاتورة: ' + p.invoice.invoice_number"></span>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-300">
                                        <i class="ph-fill ph-bank text-amber-500"></i>
                                        <span x-text="p.bank_account_name || '-'"></span>
                                    </div>

                                    <template x-if="p.notes">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/40 p-2 rounded-lg" x-text="p.notes"></p>
                                    </template>

                                    <!-- Bottom actions -->
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-50 dark:border-gray-800/60 text-xs">
                                        <span class="text-[11px] text-gray-400" x-text="p.cashier_name ? '{{ __('notebook.added_by') }}: ' + p.cashier_name : ''"></span>
                                        
                                        <button @click="confirmDeleteSupplierPayment(p.id)" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-red-50 dark:bg-gray-800 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-600 flex items-center justify-center transition-colors" title="{{ __('notebook.delete') }}">
                                            <i class="ph-bold ph-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
