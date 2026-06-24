document.addEventListener('alpine:init', () => {
    Alpine.data('casherNotebook', (config) => ({
        // State
        activeTab: 'customers', // customers, withdrawals
        
        customers: [],
        totalCustomers: 0,
        totalDebt: 0,
        todayCollections: 0,
        
        todayWithdrawals: [],
        totalTodayWithdrawals: 0,
        
        search: '',
        filter: 'all',
        withdrawalFilter: 'all', // all, or bank account id
        perPage: 15,
        
        isLoading: false,
        showAccountsSheet: false,
        
        // New Customer form
        newCustomerName: '',
        newCustomerPhone: '',
        
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

        // Withdrawal form
        withdrawalAmount: '',
        withdrawalReason: '',
        withdrawalBankAccountId: '',
        withdrawalDate: config.todayDate,
        
        // APIs
        apiBase: config.apiBase,
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
        },
        
        switchTab(tab) {
            this.activeTab = tab;
            if (tab === 'withdrawals') {
                this.fetchWithdrawals();
            }
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
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'addCustomerModal' } }));
        },
        
        async saveCustomer() {
            if(!this.newCustomerName) return;
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
                        phone: this.newCustomerPhone
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
            this.txType = type;
            this.editingTxId = null;
            this.txAmount = '';
            this.txDescription = '';
            this.txDate = config.todayDate;
            
            if (type === 'payment') {
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
            this.editingTxId = tx.id;
            this.txType = tx.type;
            this.txAmount = tx.amount;
            this.txDescription = tx.description;
            this.txBankAccountId = tx.store_bank_account_id || '';
            this.txDate = tx.transaction_date ? tx.transaction_date.split('T')[0] : config.todayDate;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'transactionModal' } }));
        },
        
        async saveTransaction() {
            if(!this.txAmount || !this.txDate) return;
            
            const url = this.editingTxId 
                ? `${this.apiBase}/transactions/${this.editingTxId}`
                : `${this.apiBase}/customers/${this.activeCustomer.id}/transactions`;
                
            const method = this.editingTxId ? 'PUT' : 'POST';
            
            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        type: this.txType,
                        amount: this.txAmount,
                        transaction_date: this.txDate,
                        description: this.txDescription,
                        store_bank_account_id: this.txType === 'payment' ? this.txBankAccountId : null
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
            this.withdrawalDate = withdrawal.withdrawal_date.split(' ')[0] || config.todayDate;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'withdrawalModal' } }));
        },

        async submitWithdrawal() {
            if (!this.withdrawalAmount || !this.withdrawalReason || !this.withdrawalBankAccountId || !this.withdrawalDate) return;
            this.isLoading = true;

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
                this.isLoading = false;

                if(res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'withdrawalModal' } }));
                    Toast.show(config.translations.success, data.message, 'success');
                    this.fetchWithdrawals();
                } else {
                    Toast.show(config.translations.warning, data.message || 'Error occurred', 'error');
                }
            } catch (e) {
                this.isLoading = false;
                console.error(e);
            }
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
