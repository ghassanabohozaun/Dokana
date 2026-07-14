document.addEventListener('alpine:init', () => {
    Alpine.data('casherNotebook', (config) => ({
        // State
        activeTab: 'customers', // customers, withdrawals
        
        customers: [],
        totalCustomers: 0,
        totalDebt: 0,
        todayCollections: 0,
        todayDirectSales: 0,
        todayDebts: 0,
        
        todayWithdrawals: [],
        totalTodayWithdrawals: 0,
        
        search: '',
        filter: 'all',
        withdrawalFilter: 'all', // all, or bank account id
        perPage: 15,
        
        isLoading: false,
        showAccountsSheet: false,
        
        // Financial Summary
        summaryData: null,
        summaryTab: 'today',
        isSummaryLoading: false,
        
        // New Customer form
        newCustomerName: '',
        newCustomerPhone: '',
        newCustomerOpeningBalance: '',
        
        // Edit Customer form
        editCustomerName: '',
        editCustomerPhone: '',
        isSavingCustomer: false,
        
        // Ledger state
        activeCustomer: null,
        ledgerTransactions: [],
        totalLedgerTransactions: 0,
        ledgerPerPage: 15,
        isLedgerLoading: false,
        
        // Transaction form
        txType: 'debt',
        txAmount: '',
        txDescription: '',
        txDate: config.todayDate,
        txBankAccountId: '',
        editingTxId: null,
        isSavingTransaction: false,

        // Withdrawal form
        withdrawalAmount: '',
        withdrawalReason: '',
        withdrawalBankAccountId: '',
        withdrawalDate: config.todayDate,
        isSavingWithdrawal: false,
        
        // APIs
        apiBase: config.apiBase,
        locale: config.locale || 'ar',
        csrf: config.csrf,
        bankBalances: config.bankBalances || {},
        storeAccounts: config.storeAccounts || [],

        // Computed
        get selectedBankBalance() {
            if (!this.withdrawalBankAccountId) return null;
            let balance = Number(this.bankBalances[this.withdrawalBankAccountId]) || 0;
            // If editing and same account, refund the old amount to show the "original" available balance before this withdrawal
            if (this.editingWithdrawalId) {
                const oldW = this.todayWithdrawals.find(w => w.id === this.editingWithdrawalId);
                if (oldW && oldW.store_bank_account_id == this.withdrawalBankAccountId) {
                    balance += Number(oldW.amount) || 0;
                }
            }
            return balance;
        },
        get remainingBalance() {
            if (this.selectedBankBalance === null) return null;
            const amount = Number(this.withdrawalAmount) || 0;
            return this.selectedBankBalance - amount;
        },
        get isWithdrawalExceeding() {
            if (this.remainingBalance === null) return false;
            return this.remainingBalance < 0;
        },

        get filteredWithdrawals() {
            if (this.withdrawalFilter === 'all') {
                return this.todayWithdrawals;
            }
            return this.todayWithdrawals.filter(w => w.store_bank_account_id == this.withdrawalFilter);
        },

        get selectedAccountData() {
            if (this.withdrawalFilter === 'all') return null;
            return this.storeAccounts.find(a => a.id == this.withdrawalFilter);
        },

        get selectedAccountTotalWithdrawals() {
            if (this.withdrawalFilter === 'all') return 0;
            return this.filteredWithdrawals.reduce((sum, w) => sum + Number(w.amount), 0);
        },

        formatDateTime(dateStr) {
            if (!dateStr) return '';
            try {
                const date = new Date(dateStr);
                if (isNaN(date.getTime())) return dateStr.substring(0, 10);
                return date.toLocaleDateString(this.locale === 'ar' ? 'ar-EG' : 'en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'numeric',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                return dateStr.substring(0, 10);
            }
        },

        init() {
            this.fetchCustomers();
            this.fetchWithdrawals();
            
            // Watchers
            let searchTimeout;
            this.$watch('search', () => { 
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.perPage = 15; 
                    this.fetchCustomers(); 
                }, 500);
            });
            this.$watch('filter', () => { this.perPage = 15; this.fetchCustomers(); });
            
            // Drag to scroll for filters
            this.$nextTick(() => {
                const slider = document.querySelector('.hide-scrollbar');
                if(!slider) return;
                
                let isDown = false;
                let startX;
                let scrollLeft;
                let isDragging = false;

                slider.addEventListener('mousedown', (e) => {
                    isDown = true;
                    isDragging = false;
                    startX = e.pageX - slider.offsetLeft;
                    scrollLeft = slider.scrollLeft;
                });
                slider.addEventListener('mouseleave', () => {
                    isDown = false;
                });
                slider.addEventListener('mouseup', () => {
                    isDown = false;
                });
                slider.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    isDragging = true;
                    const x = e.pageX - slider.offsetLeft;
                    const walk = (x - startX) * 2; 
                    slider.scrollLeft = scrollLeft - walk;
                });
                
                // Prevent button click if it was a drag
                const buttons = slider.querySelectorAll('button');
                buttons.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        if(isDragging) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    }, { capture: true });
                });
            });
        },
        
        switchTab(tab) {
            this.activeTab = tab;
            if (tab === 'withdrawals') {
                this.fetchWithdrawals();
            }
        },

        async openFinancialSummary() {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'financialSummaryModal' } }));
            this.isSummaryLoading = true;
            this.summaryTab = 'today';
            try {
                const res = await fetch(`${this.apiBase}/financial-summary`);
                const data = await res.json();
                if (res.ok) {
                    this.summaryData = data.summary;
                }
            } catch (e) {
                console.error(e);
            }
            this.isSummaryLoading = false;
        },
        
        async fetchCustomers() {
            this.isLoading = true;
            try {
                const params = new URLSearchParams({
                    search: this.search,
                    filter: this.filter,
                    per_page: this.perPage
                });
                
                const res = await fetch(`${this.apiBase}/customers?${params}`);
                const data = await res.json();
                
                if (res.ok) {
                    this.customers = data.customers;
                    this.totalCustomers = data.totalCustomers;
                    this.totalDebt = data.totalDebt;
                    this.todayCollections = data.todayCollections;
                    this.todayDirectSales = data.todayDirectSales;
                    this.todayDebts = data.todayDebts;
                }
            } catch (e) {
                console.error(e);
            }
            this.isLoading = false;
        },
        
        setFilter(f) {
            this.filter = f;
        },
        
        loadMoreCustomers() {
            this.perPage += 15;
            this.fetchCustomers();
        },
        
        // Customer
        openAddCustomer() {
            this.newCustomerName = '';
            this.newCustomerPhone = '';
            this.newCustomerOpeningBalance = '';
            this.isSavingCustomer = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'addCustomerModal' } }));
        },
        
        async saveCustomer() {
            if(this.isSavingCustomer) return;
            if(!this.newCustomerName) return;
            this.isSavingCustomer = true;
            try {
                const res = await fetch(`${this.apiBase}/customers`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.newCustomerName,
                        phone: this.newCustomerPhone,
                        opening_balance: this.newCustomerOpeningBalance
                    })
                });
                const data = await res.json();
                if(res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'addCustomerModal' } }));
                    Toast.show(config.translations.success, data.message, 'success');
                    this.fetchCustomers();
                    this.openLedger(data.customer.id);
                } else {
                    Toast.show(config.translations.warning, data.message || 'Error occurred', 'error');
                }
            } catch (e) {
                console.error(e);
            }
            this.isSavingCustomer = false;
        },
        
        openEditCustomerModal() {
            if(!this.activeCustomer) return;
            this.editCustomerName = this.activeCustomer.name;
            this.editCustomerPhone = this.activeCustomer.phone || '';
            this.isSavingCustomer = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'editCustomerModal' } }));
        },
        
        async submitEditCustomer() {
            if(this.isSavingCustomer) return;
            if(!this.editCustomerName || !this.activeCustomer) return;
            this.isSavingCustomer = true;
            try {
                const res = await fetch(`${this.apiBase}/customers/${this.activeCustomer.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.editCustomerName,
                        phone: this.editCustomerPhone
                    })
                });
                const data = await res.json();
                if(res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'editCustomerModal' } }));
                    Toast.show(config.translations.success, data.message, 'success');
                    this.activeCustomer.name = data.customer.name;
                    this.activeCustomer.phone = data.customer.phone;
                    const index = this.customers.findIndex(c => c.id === this.activeCustomer.id);
                    if(index !== -1) {
                        this.customers[index].name = data.customer.name;
                        this.customers[index].phone = data.customer.phone;
                    }
                } else {
                    Toast.show(config.translations.warning, data.message || 'Error occurred', 'error');
                }
            } catch (e) {
                console.error(e);
            }
            this.isSavingCustomer = false;
        },
        
        // Ledger
        async openLedger(customerId) {
            this.ledgerPerPage = 15;
            await this.fetchLedger(customerId);
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'ledgerModal' } }));
        },
        
        async fetchLedger(customerId) {
            this.isLedgerLoading = true;
            try {
                const res = await fetch(`${this.apiBase}/customers/${customerId}/transactions?per_page=${this.ledgerPerPage}`);
                const data = await res.json();
                if(res.ok) {
                    this.activeCustomer = data.customer;
                    this.ledgerTransactions = data.transactions;
                    this.totalLedgerTransactions = data.totalLedgerTransactions;
                }
            } catch (e) {
                console.error(e);
            }
            this.isLedgerLoading = false;
        },
        
        loadMoreLedger() {
            this.ledgerPerPage += 15;
            if(this.activeCustomer) {
                this.fetchLedger(this.activeCustomer.id);
            }
        },
        
        // Transactions
        openTxModal(type) {
            if(!this.activeCustomer) return;
            this.txType = type;
            this.txAmount = '';
            this.txDescription = '';
            this.txDate = config.todayDate;
            this.txBankAccountId = '';
            this.editingTxId = null;
            this.isSavingTransaction = false;
            
            if (type === 'payment' || type === 'direct_sale') {
                const selectEl = document.querySelector('select[x-model="txBankAccountId"]');
                if (selectEl) {
                    const defaultOpt = selectEl.querySelector('option[selected]');
                    this.txBankAccountId = defaultOpt ? defaultOpt.value : '';
                } else {
                    this.txBankAccountId = '';
                }
            } else {
                this.txBankAccountId = '';
            }
            
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'transactionModal' } }));
        },
        
        editTransaction(tx) {
            this.txType = tx.type;
            if (tx.is_direct_sale) {
                this.txType = 'direct_sale';
            }
            this.txAmount = tx.amount;
            this.txDescription = tx.description || '';
            this.txDate = tx.transaction_date ? tx.transaction_date.substring(0, 10) : config.todayDate;
            this.txBankAccountId = tx.store_bank_account_id || '';
            this.editingTxId = tx.id;
            this.isSavingTransaction = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'transactionModal' } }));
        },
        
        async saveTransaction() {
            if(this.isSavingTransaction) return;
            if(!this.txAmount || !this.txDate || !this.activeCustomer) return;
            
            const url = this.editingTxId 
                ? `${this.apiBase}/transactions/${this.editingTxId}`
                : `${this.apiBase}/customers/${this.activeCustomer.id}/transactions`;
                
            const method = this.editingTxId ? 'PUT' : 'POST';
            
            this.isSavingTransaction = true;
            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        type: (this.txType === 'direct_sale') ? 'payment' : this.txType,
                        amount: this.txAmount,
                        transaction_date: this.txDate,
                        description: this.txDescription,
                        store_bank_account_id: (this.txType === 'payment' || this.txType === 'direct_sale') ? this.txBankAccountId : null,
                        is_direct_sale: (this.txType === 'direct_sale')
                    })
                });
                const data = await res.json();
                if(res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'transactionModal' } }));
                    Toast.show(config.translations.success, data.message, 'success');
                    this.fetchCustomers(); // Update balances
                    this.fetchLedger(this.activeCustomer.id); // Update ledger
                } else {
                    Toast.show(config.translations.warning, data.message || 'Error occurred', 'error');
                }
            } catch(e) {
                console.error(e);
            }
            this.isSavingTransaction = false;
        },
        
        async deleteTransaction(txId) {
            const result = await Swal.fire({
                title: config.translations.areYouSure,
                text: config.translations.confirmDeleteTx,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: config.translations.yesDelete,
                cancelButtonText: config.translations.cancel,
                customClass: {
                    popup: 'rounded-[2rem] p-4 w-[320px] max-w-[90vw] dark:bg-darkCard',
                    title: 'text-lg font-bold text-gray-900 dark:text-white pt-2',
                    htmlContainer: 'text-sm font-medium text-gray-500 dark:text-gray-400 m-0 mt-2',
                    actions: 'mt-5 w-full flex gap-3 px-2',
                    confirmButton: 'flex-1 btn-gradient-primary !bg-gradient-to-r !from-red-500 !to-rose-600 text-white font-bold rounded-xl py-3 shadow-lg shadow-red-500/30 border-0 m-0 text-sm',
                    cancelButton: 'flex-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold rounded-xl py-3 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all border-0 m-0 text-sm'
                },
                buttonsStyling: false
            });

            if(!result.isConfirmed) return;
            
            try {
                const res = await fetch(`${this.apiBase}/transactions/${txId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if(res.ok) {
                    Toast.show(config.translations.success, data.message, 'success');
                    this.fetchCustomers();
                    this.fetchLedger(this.activeCustomer.id);
                } else {
                    Toast.show(config.translations.warning, data.message || 'Error occurred', 'error');
                }
            } catch(e) {
                console.error(e);
            }
        },

        // Withdrawals API
        async fetchWithdrawals() {
            try {
                const res = await fetch(`${this.apiBase}/withdrawals`);
                const data = await res.json();
                
                if (res.ok) {
                    this.todayWithdrawals = data.withdrawals;
                    this.totalTodayWithdrawals = data.totalAmount;
                    if (data.bankBalances) {
                        this.bankBalances = data.bankBalances;
                    }
                }
            } catch (e) {
                console.error(e);
            }
        },

        openWithdrawalModal() {
            this.withdrawalAmount = '';
            this.withdrawalReason = '';
            this.withdrawalDate = config.todayDate;
            this.isEditingWithdrawal = false;
            this.editingWithdrawalId = null;
            this.isSavingWithdrawal = false;
            
            const selectEl = document.querySelector('select[x-model="withdrawalBankAccountId"]');
            if (selectEl) {
                const defaultOpt = selectEl.querySelector('option[selected]');
                this.withdrawalBankAccountId = defaultOpt ? defaultOpt.value : '';
            } else {
                this.withdrawalBankAccountId = '';
            }
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'withdrawalModal' } }));
        },

        openEditWithdrawal(withdrawal) {
            this.isEditingWithdrawal = true;
            this.editingWithdrawalId = withdrawal.id;
            this.withdrawalAmount = withdrawal.amount;
            this.withdrawalReason = withdrawal.reason;
            this.withdrawalBankAccountId = withdrawal.store_bank_account_id;
            this.withdrawalDate = withdrawal.withdrawal_date ? withdrawal.withdrawal_date.substring(0, 10) : config.todayDate;
            this.isSavingWithdrawal = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'withdrawalModal' } }));
        },

        async submitWithdrawal() {
            if(this.isSavingWithdrawal) return;
            if (!this.withdrawalAmount || !this.withdrawalReason || !this.withdrawalBankAccountId || !this.withdrawalDate) return;
            this.isSavingWithdrawal = true;

            const url = this.isEditingWithdrawal 
                ? `${this.apiBase}/withdrawals/${this.editingWithdrawalId}` 
                : `${this.apiBase}/withdrawals`;
                
            const method = this.isEditingWithdrawal ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf
                    },
                    body: JSON.stringify({
                        amount: this.withdrawalAmount,
                        reason: this.withdrawalReason,
                        store_bank_account_id: this.withdrawalBankAccountId,
                        withdrawal_date: this.withdrawalDate
                    })
                });

                const data = await res.json();
                
                if (res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'withdrawalModal' } }));
                    Toast.show(config.translations.success, data.message, 'success');
                    this.fetchWithdrawals();
                } else {
                    Toast.show(config.translations.warning, data.message || 'Error occurred', 'error');
                }
            } catch (e) {
                console.error(e);
            }
            this.isSavingWithdrawal = false;
        },

        async deleteWithdrawal(id) {
            Swal.fire({
                title: config.translations.areYouSure || 'هل أنت متأكد؟',
                text: config.translations.confirmDeleteWithdrawal || 'هل أنت متأكد من حذف هذا السحب؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: config.translations.yesDelete || 'نعم، احذف',
                cancelButtonText: config.translations.cancel || 'إلغاء',
                customClass: {
                    popup: 'rounded-[2rem] p-4 w-[320px] max-w-[90vw] dark:bg-darkCard',
                    title: 'text-lg font-bold text-gray-900 dark:text-white pt-2',
                    htmlContainer: 'text-sm font-medium text-gray-500 dark:text-gray-400 m-0 mt-2',
                    actions: 'mt-5 w-full flex gap-3 px-2',
                    confirmButton: 'flex-1 btn-gradient-primary !bg-gradient-to-r !from-red-500 !to-rose-600 text-white font-bold rounded-xl py-3 shadow-lg shadow-red-500/30 border-0 m-0 text-sm',
                    cancelButton: 'flex-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold rounded-xl py-3 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all border-0 m-0 text-sm'
                },
                buttonsStyling: false
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch(`${this.apiBase}/withdrawals/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf
                            }
                        });
                        const data = await res.json();

                        if(res.ok) {
                            Toast.show(config.translations.success, data.message, 'success');
                            this.fetchWithdrawals();
                        } else {
                            Toast.show(config.translations.warning, data.message || 'Error', 'error');
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }
            });
        }
    }));
});
