document.addEventListener('alpine:init', () => {
    Alpine.data('casherNotebook', (config) => ({
        // State
        customers: [],
        totalCustomers: 0,
        totalDebt: 0,
        todayCollections: 0,
        
        search: '',
        filter: 'all',
        perPage: 15,
        
        isLoading: false,
        
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
        editingTxId: null,
        
        // APIs
        apiBase: config.apiBase,
        csrf: config.csrf,

        init() {
            this.fetchCustomers();
            
            // Watchers
            this.$watch('search', () => { this.perPage = 15; this.fetchCustomers(); });
            this.$watch('filter', () => { this.perPage = 15; this.fetchCustomers(); });
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
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'transactionModal' } }));
        },
        
        editTransaction(tx) {
            this.editingTxId = tx.id;
            this.txType = tx.type;
            this.txAmount = tx.amount;
            this.txDescription = tx.description;
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
                        description: this.txDescription
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
        }
    }));
});
