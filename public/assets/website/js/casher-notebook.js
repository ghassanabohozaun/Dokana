function casherNotebook(passedConfig = {}) {
    const config = (typeof passedConfig === 'object' && passedConfig !== null) ? passedConfig : (window.casherConfig || {});
    config.translations = config.translations || {};
    return {
        // State
        activeTab: 'customers', // customers, suppliers, withdrawals
        
        customers: [],
        totalCustomers: 0,
        totalDebt: 0,
        todayCollections: 0,
        todayDirectSales: 0,
        todayDebts: 0,
        
        // Suppliers State (Overall Totals & Financial Position)
        suppliers: [],
        totalSuppliers: 0,
        totalActiveSuppliers: 0,
        suppliersWithDueCount: 0,
        totalPurchases: 0,
        totalInvoicesCount: 0,
        totalPaid: 0,
        totalPaymentsCount: 0,
        totalPendingDues: 0,
        pendingInvoicesCount: 0,
        totalSupplierDue: 0,
        supplierSearch: '',
        supplierFilter: 'all',
        isSuppliersLoading: false,
        loadingSupplierId: null,

        // Supplier Modals (Invoices & Payments Registers)
        allSupplierInvoicesList: [],
        invoicesModalFilter: 'all',
        invoicesModalSearch: '',
        isAllSupplierInvoicesLoading: false,
        isAllSupplierInvoicesCardLoading: false,

        allSupplierPaymentsList: [],
        paymentsModalSearch: '',
        isAllSupplierPaymentsLoading: false,
        isAllSupplierPaymentsCardLoading: false,

        // New Supplier Form
        newSupplierName: '',
        newSupplierPhone: '',
        newSupplierBankName: '',
        newSupplierAccountNumber: '',
        newSupplierAddress: '',
        isSavingSupplier: false,

        // Edit Supplier Form
        editSupplierId: null,
        editSupplierName: '',
        editSupplierPhone: '',
        editSupplierBankName: '',
        editSupplierAccountNumber: '',
        editSupplierAddress: '',

        // Supplier Ledger & Details
        activeSupplier: null,
        supplierLedgerInvoices: [],
        supplierLedgerPayments: [],
        supplierUnpaidInvoices: [],
        supplierLedgerSummary: null,
        isSupplierLedgerLoading: false,

        // Supplier Invoice Form
        newSupplierInvoiceNumber: '',
        newSupplierInvoiceAmount: '',
        newSupplierInvoiceDate: config.todayDate || new Date().toISOString().substring(0, 10),
        newSupplierInvoiceNotes: '',
        isSavingSupplierInvoice: false,

        // Supplier Payment Form
        supplierPaymentAmount: '',
        supplierPaymentBankAccountId: '',
        supplierPaymentInvoiceId: '',
        supplierPaymentDate: config.todayDate || new Date().toISOString().substring(0, 10),
        supplierPaymentNotes: '',
        isSavingSupplierPayment: false,

        todayWithdrawals: [],
        totalTodayWithdrawals: 0,
                
                search: '',
                filter: 'all',
                isListening: false,
                isAIListening: false,
                withdrawalFilter: 'all', // all, or bank account id
                perPage: 15,
                
                isLoading: false,
                showAccountsSheet: false,
                
                // Financial Summary
                summaryData: null,
                summaryTab: 'today',
                summaryCustomDate: config.todayDate || new Date().toISOString().substring(0, 10),
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
                ledgerPerPage: 20,
                isLedgerLoading: false,
                
                // Transaction form
                txType: 'debt',
                txAmount: '',
                txDescription: '',
                txDate: config.todayDate || new Date().toISOString().substring(0, 10),
                txBankAccountId: '',
                editingTxId: null,
                isSavingTransaction: false,

                // Universal Delete Confirmation Modal State
                deleteType: 'transaction',
                deleteId: null,
                deleteModalTitle: '',
                deleteModalMessage: '',
                isDeletingItem: false,

                // Withdrawal form
                withdrawalAmount: '',
                withdrawalReason: '',
                withdrawalBankAccountId: '',
                withdrawalDate: config.todayDate || new Date().toISOString().substring(0, 10),
                isSavingWithdrawal: false,
                isEditingWithdrawal: false,
                editingWithdrawalId: null,
                
                // Today's Collections Modal
                todayCollectionsList: [],
                totalTodayCollectionsCount: 0,
                collectionsPerPage: 20,
                isCollectionsLoading: false,
                
                // Today's Debts Modal
                todayDebtsList: [],
                totalTodayDebtsCount: 0,
                debtsPerPage: 20,
                isDebtsLoading: false,

                // Today's Direct Sales Modal
                todayDirectSalesList: [],
                totalTodayDirectSalesCount: 0,
                todayDirectSalesTotalCount: 0,
                directSalesPerPage: 20,
                isDirectSalesLoading: false,
                
                // Loading States for UX
                loadingCustomerId: null,
                isCollectionsCardLoading: false,
                isDebtsCardLoading: false,
                isDirectSalesCardLoading: false,
                
                // APIs
                apiBase: config.apiBase || '',
                locale: config.locale || 'ar',
                csrf: config.csrf || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
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

        get selectedSupplierInvoice() {
            if (!this.supplierPaymentInvoiceId || !this.supplierUnpaidInvoices) return null;
            return this.supplierUnpaidInvoices.find(inv => inv.id == this.supplierPaymentInvoiceId) || null;
        },

        get selectedInvoiceRemaining() {
            return this.selectedSupplierInvoice ? Number(this.selectedSupplierInvoice.remaining_amount) : null;
        },

        formatDateTime(dateStr) {
            if (!dateStr) return '';
            try {
                const date = new Date(dateStr);
                if (isNaN(date.getTime())) return dateStr.substring(0, 10);
                return date.toLocaleDateString(this.locale === 'ar' ? 'ar-u-nu-latn' : 'en-US', {
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

        // Universal Input Normalizers for Arabic Numbers and Digits
        normalizeArabicNumbers(val) {
            if (val === null || val === undefined) return '';
            let s = String(val);
            s = s.replace(/[٠-٩]/g, d => "٠١٢٣٤٥٦٧٨٩".indexOf(d));
            s = s.replace(/[۰-۹]/g, d => "۰۱۲۳۴۵۶۷۸۹".indexOf(d));
            s = s.replace(/[\u060C,]/g, '.');
            return s;
        },

        sanitizeAmountInput(val) {
            let normalized = this.normalizeArabicNumbers(val);
            normalized = normalized.replace(/[^0-9.]/g, '');
            const parts = normalized.split('.');
            if (parts.length > 2) {
                normalized = parts[0] + '.' + parts.slice(1).join('');
            }
            return normalized;
        },

        init() {
            this.fetchCustomers();
            this.fetchSuppliers();
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

            let supplierSearchTimeout;
            this.$watch('supplierSearch', () => {
                clearTimeout(supplierSearchTimeout);
                supplierSearchTimeout = setTimeout(() => {
                    this.fetchSuppliers();
                }, 500);
            });
            this.$watch('supplierFilter', () => { this.fetchSuppliers(); });
            
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
            } else if (tab === 'suppliers') {
                this.fetchSuppliers();
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
        
        async fetchCustomSummary() {
            if (!this.summaryCustomDate) return;
            this.isSummaryLoading = true;
            try {
                const res = await fetch(`${this.apiBase}/financial-summary?custom_date=${this.summaryCustomDate}`);
                const data = await res.json();
                if (res.ok && data.summary && data.summary.custom) {
                    this.summaryData.custom = data.summary.custom;
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
                    if (data.totalDebt !== undefined) this.totalDebt = data.totalDebt;
                    if (data.todayCollections !== undefined) this.todayCollections = data.todayCollections;
                    if (data.todayDirectSales !== undefined) this.todayDirectSales = data.todayDirectSales;
                    if (data.todayDebts !== undefined) this.todayDebts = data.todayDebts;
                }
            } catch (e) {
                console.error(e);
            }
            this.isLoading = false;
        },
        
        setFilter(f) {
            this.filter = f;
        },
        
        startVoiceSearch() {
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                alert('عذراً، متصفحك لا يدعم البحث الصوتي.');
                return;
            }
            
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            
            recognition.lang = 'ar-SA';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;
            
            recognition.onstart = () => {
                this.isListening = true;
            };
            
            recognition.onresult = (event) => {
                const speechResult = event.results[0][0].transcript;
                this.search = speechResult;
            };
            
            recognition.onspeechend = () => {
                recognition.stop();
            };
            
            recognition.onend = () => {
                this.isListening = false;
            };
            
            recognition.onerror = (event) => {
                this.isListening = false;
                console.error('Speech recognition error detected: ' + event.error);
                if (event.error === 'not-allowed') {
                    alert('المتصفح منع الوصول للمايكروفون. يرجى التأكد من أن الموقع يعمل عبر HTTPS والسماح للمتصفح باستخدام المايكروفون.');
                } else {
                    alert('حدث خطأ في المايكروفون: ' + event.error);
                }
            };
            
            recognition.start();
        },
        
        startAIVoiceCommand() {
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                Toast.show('تنبيه', 'متصفحك لا يدعم البحث الصوتي', 'warning');
                return;
            }
            
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            
            recognition.lang = 'ar-SA';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;
            
            recognition.onstart = () => {
                this.isAIListening = true;
            };
            
            recognition.onresult = async (event) => {
                const speechResult = event.results[0][0].transcript;
                
                // Keep the loading spinner active until the API responds
                try {
                    const res = await fetch(`${this.apiBase}/voice-command`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ text: speechResult })
                    });
                    
                    const data = await res.json();
                    this.isAIListening = false;
                    
                    if (res.ok && data.success) {
                        if (data.customer) {
                            // Pre-fill transaction data
                            this.activeCustomer = data.customer;
                            this.txType = data.type;
                            this.txAmount = data.amount;
                            this.txDescription = data.notes ? data.notes : (data.type === 'payment' ? 'دفعة مسجلة صوتياً' : 'مشتريات مسجلة صوتياً');
                            this.txDate = config.todayDate;
                            this.txBankAccountId = '';
                            
                            // Open modal
                            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'transactionModal' } }));
                            Toast.show('نجاح', 'تم تحليل الجملة، يرجى مراجعة البيانات والحفظ.', 'success');
                        } else {
                            Toast.show('تنبيه', `لم نعثر على عميل باسم مقارب لـ "${data.parsed_name}"`, 'warning');
                        }
                    } else {
                        Toast.show('خطأ', data.message || 'حدث خطأ في فهم الجملة', 'error');
                    }
                } catch (e) {
                    this.isAIListening = false;
                    console.error(e);
                    Toast.show('خطأ', 'تعذر الاتصال بالسيرفر', 'error');
                }
            };
            
            recognition.onspeechend = () => {
                recognition.stop();
            };
            
            recognition.onend = () => {
                // Do not set isAIListening to false here because we need it to spin during the API call
                // It's set to false after the API call finishes.
            };
            
            recognition.onerror = (event) => {
                this.isAIListening = false;
                console.error('Speech recognition error: ' + event.error);
                if (event.error === 'not-allowed') {
                    Toast.show('خطأ', 'المتصفح منع المايكروفون. تأكد من HTTPS', 'error');
                }
            };
            
            recognition.start();
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
            if(!this.newCustomerName) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterCustomerName, 'warning');
                return;
            }
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
            this.ledgerPerPage = 20;
            this.activeCustomer = this.customers.find(c => c.id === customerId) || { id: customerId, name: '...', balance: 0 };
            this.loadingCustomerId = customerId;
            await this.fetchLedger(customerId);
            this.loadingCustomerId = null;
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
            this.ledgerPerPage += 20;
            if(this.activeCustomer) {
                this.fetchLedger(this.activeCustomer.id);
            }
        },
        
        // Today's Collections
        async openTodayCollections() {
            this.collectionsPerPage = 20;
            this.isCollectionsCardLoading = true;
            await this.fetchTodayCollections();
            this.isCollectionsCardLoading = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'todayCollectionsModal' } }));
        },
        
        async fetchTodayCollections() {
            this.isCollectionsLoading = true;
            try {
                const res = await fetch(`${this.apiBase}/today-collections?per_page=${this.collectionsPerPage}`);
                const data = await res.json();
                if(res.ok) {
                    this.todayCollectionsList = data.transactions;
                    this.totalTodayCollectionsCount = data.total;
                }
            } catch (e) {
                console.error(e);
            }
            this.isCollectionsLoading = false;
        },
        
        loadMoreCollections() {
            this.collectionsPerPage += 20;
            this.fetchTodayCollections();
        },
        
        // Today's Debts
        async openTodayDebts() {
            this.debtsPerPage = 20;
            this.isDebtsCardLoading = true;
            await this.fetchTodayDebts();
            this.isDebtsCardLoading = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'todayDebtsModal' } }));
        },
        
        async fetchTodayDebts() {
            this.isDebtsLoading = true;
            try {
                const res = await fetch(`${this.apiBase}/today-debts?per_page=${this.debtsPerPage}`);
                const data = await res.json();
                if(res.ok) {
                    this.todayDebtsList = data.transactions;
                    this.totalTodayDebtsCount = data.total;
                }
            } catch (e) {
                console.error(e);
            }
            this.isDebtsLoading = false;
        },
        
        loadMoreDebts() {
            this.debtsPerPage += 20;
            this.fetchTodayDebts();
        },
        
        async openTodayDirectSales() {
            this.directSalesPerPage = 20;
            this.isDirectSalesCardLoading = true;
            await this.fetchTodayDirectSales();
            this.isDirectSalesCardLoading = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'todayDirectSalesModal' } }));
        },
        
        async fetchTodayDirectSales() {
            this.isDirectSalesLoading = true;
            try {
                const res = await fetch(`${this.apiBase}/today-direct-sales?per_page=${this.directSalesPerPage}`);
                const data = await res.json();
                if(res.ok) {
                    this.todayDirectSalesList = data.transactions;
                    this.totalTodayDirectSalesCount = data.total;
                    this.todayDirectSalesTotalCount = data.total;
                }
            } catch (e) {
                console.error(e);
            }
            this.isDirectSalesLoading = false;
        },
        
        loadMoreDirectSales() {
            this.directSalesPerPage += 20;
            this.fetchTodayDirectSales();
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
            
            if(!this.txAmount) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterAmount, 'warning');
                return;
            }
            if(!this.txDate) {
                Toast.show(config.translations.warning, config.translations.pleaseSelectDate, 'warning');
                return;
            }
            if(!this.activeCustomer) return;
            
            if ((this.txType === 'payment' || this.txType === 'direct_sale') && !this.txBankAccountId) {
                Toast.show(config.translations.warning, config.translations.selectAccount, 'warning');
                return;
            }
            
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
                    
                    // Optimistically update the customer's balance and re-fetch to ensure sync
                    this.fetchCustomers(); 
                    
                    // Only update the ledger if the ledger modal is currently open and active
                    if(this.activeCustomer && document.getElementById('ledgerModal')?.style.display !== 'none') {
                        this.fetchLedger(this.activeCustomer.id);
                    }
                    // No need to fetch today's collections/debts/sales unless their specific modals are open, 
                    // which we don't need to do here because they fetch when opened.
                } else {
                    Toast.show(config.translations.warning, data.message || 'Error occurred', 'error');
                }
            } catch(e) {
                console.error(e);
            }
            this.isSavingTransaction = false;
        },
        
        openDeleteModal(type, id, title = null, message = null) {
            this.deleteType = type;
            this.deleteId = id;
            
            if (type === 'transaction') {
                this.deleteModalTitle = config.translations.areYouSure || 'هل أنت متأكد؟';
                this.deleteModalMessage = config.translations.confirmDeleteTx || 'هل أنت متأكد من حذف هذه الحركة؟ لا يمكن التراجع عن هذا الإجراء.';
            } else if (type === 'withdrawal') {
                this.deleteModalTitle = config.translations.areYouSure || 'هل أنت متأكد؟';
                this.deleteModalMessage = config.translations.confirmDeleteWithdrawal || 'هل أنت متأكد من حذف هذا السحب؟ سيتم استرجاع المبلغ للخزينة.';
            } else if (type === 'supplier_invoice') {
                this.deleteModalTitle = config.translations.areYouSure || 'هل أنت متأكد؟';
                this.deleteModalMessage = config.translations.confirmDeleteInvoice || 'هل أنت متأكد من حذف هذه الفاتورة؟';
            } else if (type === 'supplier_payment') {
                this.deleteModalTitle = config.translations.areYouSure || 'هل أنت متأكد؟';
                this.deleteModalMessage = config.translations.confirmDeleteSupplierPayment || 'هل أنت متأكد من حذف هذه الدفعة؟ سيتم استرجاع المبلغ للخزينة.';
            }
            
            if (title) this.deleteModalTitle = title;
            if (message) this.deleteModalMessage = message;
            
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'deleteConfirmModal' } }));
        },

        async executeDelete() {
            if (!this.deleteId || !this.deleteType || this.isDeletingItem) return;
            this.isDeletingItem = true;
            
            try {
                let url = '';
                if (this.deleteType === 'transaction') {
                    url = `${this.apiBase}/transactions/${this.deleteId}`;
                } else if (this.deleteType === 'withdrawal') {
                    url = `${this.apiBase}/withdrawals/${this.deleteId}`;
                } else if (this.deleteType === 'supplier_invoice') {
                    url = `${this.apiBase}/invoices/${this.deleteId}`;
                } else if (this.deleteType === 'supplier_payment') {
                    url = `${this.apiBase}/payments/${this.deleteId}`;
                }
                
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await res.json();
                
                if (res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'deleteConfirmModal' } }));
                    Toast.show(config.translations.success, data.message || 'تم الحذف بنجاح', 'success');
                    
                    if (this.deleteType === 'transaction') {
                        this.fetchCustomers();
                        if (this.activeCustomer && document.getElementById('ledgerModal')?.style.display !== 'none') {
                            this.fetchLedger(this.activeCustomer.id);
                        }
                    } else if (this.deleteType === 'withdrawal') {
                        this.fetchWithdrawals();
                    } else if (this.deleteType === 'supplier_invoice') {
                        if (this.activeSupplier) {
                            this.fetchSupplierLedger(this.activeSupplier.id);
                        }
                        this.fetchSuppliers();
                    } else if (this.deleteType === 'supplier_payment') {
                        if (this.activeSupplier) {
                            this.fetchSupplierLedger(this.activeSupplier.id);
                        }
                        this.fetchSuppliers();
                        this.fetchWithdrawals(); // refresh bank balances & withdrawals
                    }
                } else {
                    Toast.show(config.translations.warning, data.message || 'حدث خطأ أثناء الحذف', 'error');
                }
            } catch (e) {
                console.error('Error in executeDelete:', e);
            } finally {
                this.isDeletingItem = false;
                this.deleteId = null;
            }
        },

        deleteTransaction(id) {
            this.openDeleteModal('transaction', id);
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
            
            if (!this.withdrawalAmount) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterAmount, 'warning');
                return;
            }
            if (!this.withdrawalReason) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterReason, 'warning');
                return;
            }
            if (!this.withdrawalDate) {
                Toast.show(config.translations.warning, config.translations.pleaseSelectDate, 'warning');
                return;
            }
            
            if (!this.withdrawalBankAccountId) {
                Toast.show(config.translations.warning, config.translations.selectAccount, 'warning');
                return;
            }
            
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

        deleteWithdrawal(id) {
            this.openDeleteModal('withdrawal', id);
        },
        // ==================== SUPPLIERS METHODS ====================
        
        setSupplierFilter(filter) {
            this.supplierFilter = filter;
            this.fetchSuppliers();
        },

        async fetchSuppliers() {
            this.isSuppliersLoading = true;
            try {
                const params = new URLSearchParams({
                    search: this.supplierSearch || '',
                    filter: this.supplierFilter || 'all'
                });
                const res = await fetch(`${this.apiBase}/suppliers?${params.toString()}`);
                const data = await res.json();
                if (res.ok) {
                    this.suppliers = data.suppliers || [];
                    this.totalSuppliers = data.totalSuppliers || 0;
                    this.totalActiveSuppliers = data.totalActiveSuppliers || 0;
                    this.suppliersWithDueCount = data.suppliersWithDueCount || 0;
                    if (data.totalPurchases !== undefined) this.totalPurchases = data.totalPurchases;
                    if (data.totalInvoicesCount !== undefined) this.totalInvoicesCount = data.totalInvoicesCount;
                    if (data.totalPaid !== undefined) this.totalPaid = data.totalPaid;
                    if (data.totalPaymentsCount !== undefined) this.totalPaymentsCount = data.totalPaymentsCount;
                    if (data.totalPendingDues !== undefined) this.totalPendingDues = data.totalPendingDues;
                    if (data.pendingInvoicesCount !== undefined) this.pendingInvoicesCount = data.pendingInvoicesCount;
                    if (data.totalSupplierDue !== undefined) this.totalSupplierDue = data.totalSupplierDue;
                }
            } catch (e) {
                console.error('Error fetching suppliers:', e);
            }
            this.isSuppliersLoading = false;
        },

        async openAllSupplierInvoices(filter = 'all') {
            this.invoicesModalFilter = filter;
            this.invoicesModalSearch = '';
            this.isAllSupplierInvoicesCardLoading = true;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'allSupplierInvoicesModal' } }));
            await this.fetchAllSupplierInvoices();
            this.isAllSupplierInvoicesCardLoading = false;
        },

        async fetchAllSupplierInvoices() {
            this.isAllSupplierInvoicesLoading = true;
            try {
                const params = new URLSearchParams({
                    status: this.invoicesModalFilter || 'all',
                    search: this.invoicesModalSearch || ''
                });
                const res = await fetch(`${this.apiBase}/suppliers/all-invoices?${params.toString()}`);
                const data = await res.json();
                if (res.ok) {
                    this.allSupplierInvoicesList = data.invoices || [];
                    if (data.overallTotalPurchases !== undefined) this.totalPurchases = data.overallTotalPurchases;
                }
            } catch (e) {
                console.error('Error fetching all supplier invoices:', e);
            }
            this.isAllSupplierInvoicesLoading = false;
        },

        async openAllSupplierPayments() {
            this.paymentsModalSearch = '';
            this.isAllSupplierPaymentsCardLoading = true;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'allSupplierPaymentsModal' } }));
            await this.fetchAllSupplierPayments();
            this.isAllSupplierPaymentsCardLoading = false;
        },

        async fetchAllSupplierPayments() {
            this.isAllSupplierPaymentsLoading = true;
            try {
                const params = new URLSearchParams({
                    search: this.paymentsModalSearch || ''
                });
                const res = await fetch(`${this.apiBase}/suppliers/all-payments?${params.toString()}`);
                const data = await res.json();
                if (res.ok) {
                    this.allSupplierPaymentsList = data.payments || [];
                    if (data.overallTotalPaid !== undefined) this.totalPaid = data.overallTotalPaid;
                }
            } catch (e) {
                console.error('Error fetching all supplier payments:', e);
            }
            this.isAllSupplierPaymentsLoading = false;
        },

        // Backward compatibility wrappers
        openTodaySupplierInvoices() {
            this.openAllSupplierInvoices('all');
        },
        openTodaySupplierPayments() {
            this.openAllSupplierPayments();
        },

        async openSupplierLedgerById(supplierId) {
            let supplier = this.suppliers.find(s => s.id == supplierId);
            if (!supplier) {
                try {
                    const res = await fetch(`${this.apiBase}/suppliers?search=&per_page=100`);
                    const data = await res.json();
                    if (res.ok && data.suppliers) {
                        supplier = data.suppliers.find(s => s.id == supplierId);
                    }
                } catch (e) {
                    console.error(e);
                }
            }
            if (supplier) {
                this.openSupplierLedger(supplier);
            }
        },

        openAddSupplier() {
            this.newSupplierName = '';
            this.newSupplierPhone = '';
            this.newSupplierBankName = '';
            this.newSupplierAccountNumber = '';
            this.newSupplierAddress = '';
            this.isSavingSupplier = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'addSupplierModal' } }));
        },

        async saveSupplier() {
            if (this.isSavingSupplier) return;

            if (!this.newSupplierName || !this.newSupplierName.trim()) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterSupplierName || 'الرجاء إدخال اسم المورد', 'warning');
                return;
            }

            if (!this.newSupplierPhone || !this.newSupplierPhone.trim()) {
                Toast.show(config.translations.warning, 'الرجاء إدخال رقم هاتف المورد', 'warning');
                return;
            }

            if (!this.newSupplierBankName || !this.newSupplierBankName.trim()) {
                Toast.show(config.translations.warning, config.translations.please_enter_bank_name || 'الرجاء إدخال اسم البنك أو المحفظة', 'warning');
                return;
            }

            this.isSavingSupplier = true;

            try {
                const res = await fetch(`${this.apiBase}/suppliers`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf
                    },
                    body: JSON.stringify({
                        name: this.newSupplierName.trim(),
                        mobile: this.newSupplierPhone.trim(),
                        bank_name: this.newSupplierBankName ? this.newSupplierBankName.trim() : '-',
                        account_number: this.newSupplierAccountNumber ? this.newSupplierAccountNumber.trim() : '-',
                        address: this.newSupplierAddress ? this.newSupplierAddress.trim() : null,
                    })
                });

                const data = await res.json();

                if (res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'addSupplierModal' } }));
                    Toast.show(config.translations.success, data.message || 'تمت إضافة المورد بنجاح', 'success');
                    this.newSupplierName = '';
                    this.newSupplierPhone = '';
                    this.newSupplierBankName = '';
                    this.newSupplierAccountNumber = '';
                    this.newSupplierAddress = '';
                    this.fetchSuppliers();
                } else {
                    let errMsg = data.message || 'حدث خطأ أثناء الحفظ';
                    if (data.errors) {
                        const firstErr = Object.values(data.errors)[0];
                        if (firstErr && firstErr[0]) errMsg = firstErr[0];
                    }
                    Toast.show(config.translations.warning, errMsg, 'error');
                }
            } catch (e) {
                console.error('Error saving supplier:', e);
                Toast.show(config.translations.warning, 'حدث خطأ في الاتصال بالخادم', 'error');
            }
            this.isSavingSupplier = false;
        },

        openEditSupplierModal() {
            if (!this.activeSupplier) return;
            this.editSupplierId = this.activeSupplier.id;
            this.editSupplierName = this.activeSupplier.name || '';
            this.editSupplierPhone = this.activeSupplier.mobile || '';
            this.editSupplierBankName = this.activeSupplier.bank_name || '';
            this.editSupplierAccountNumber = this.activeSupplier.account_number || '';
            this.editSupplierAddress = this.activeSupplier.address || '';
            this.isSavingSupplier = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'editSupplierModal' } }));
        },

        async updateSupplier() {
            if (this.isSavingSupplier || !this.editSupplierId) return;

            if (!this.editSupplierName || !this.editSupplierName.trim()) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterSupplierName || 'الرجاء إدخال اسم المورد', 'warning');
                return;
            }

            if (!this.editSupplierBankName || !this.editSupplierBankName.trim()) {
                Toast.show(config.translations.warning, config.translations.please_enter_bank_name || 'الرجاء إدخال اسم البنك أو المحفظة', 'warning');
                return;
            }

            this.isSavingSupplier = true;

            try {
                const res = await fetch(`${this.apiBase}/suppliers/${this.editSupplierId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf
                    },
                    body: JSON.stringify({
                        name: this.editSupplierName.trim(),
                        mobile: this.editSupplierPhone ? this.editSupplierPhone.trim() : '',
                        bank_name: this.editSupplierBankName ? this.editSupplierBankName.trim() : '-',
                        account_number: this.editSupplierAccountNumber ? this.editSupplierAccountNumber.trim() : '-',
                        address: this.editSupplierAddress ? this.editSupplierAddress.trim() : null,
                    })
                });

                const data = await res.json();

                if (res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'editSupplierModal' } }));
                    Toast.show(config.translations.success, data.message || 'تم التعديل بنجاح', 'success');
                    if (this.activeSupplier && this.activeSupplier.id === this.editSupplierId) {
                        this.activeSupplier.name = this.editSupplierName.trim();
                        this.activeSupplier.mobile = this.editSupplierPhone ? this.editSupplierPhone.trim() : '';
                        this.activeSupplier.bank_name = this.editSupplierBankName.trim();
                        this.activeSupplier.account_number = this.editSupplierAccountNumber ? this.editSupplierAccountNumber.trim() : '-';
                        this.activeSupplier.address = this.editSupplierAddress ? this.editSupplierAddress.trim() : null;
                    }
                    this.fetchSuppliers();
                } else {
                    let errMsg = data.message || 'حدث خطأ أثناء التعديل';
                    if (data.errors) {
                        const firstErr = Object.values(data.errors)[0];
                        if (firstErr && firstErr[0]) errMsg = firstErr[0];
                    }
                    Toast.show(config.translations.warning, errMsg, 'error');
                }
            } catch (e) {
                console.error('Error updating supplier:', e);
                Toast.show(config.translations.warning, 'حدث خطأ في الاتصال بالخادم', 'error');
            }
            this.isSavingSupplier = false;
        },

        async openSupplierLedger(supplier) {
            this.activeSupplier = supplier;
            this.supplierLedgerInvoices = [];
            this.supplierLedgerPayments = [];
            this.supplierUnpaidInvoices = [];
            this.supplierLedgerSummary = null;
            this.loadingSupplierId = supplier.id;

            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'supplierLedgerModal' } }));
            await this.fetchSupplierLedger(supplier.id);
            this.loadingSupplierId = null;
        },

        async fetchSupplierLedger(supplierId) {
            this.isSupplierLedgerLoading = true;
            try {
                const res = await fetch(`${this.apiBase}/suppliers/${supplierId}/ledger`);
                const data = await res.json();
                if (res.ok) {
                    this.activeSupplier = data.supplier;
                    this.supplierLedgerInvoices = data.invoices || [];
                    this.supplierLedgerPayments = data.payments || [];
                    this.supplierUnpaidInvoices = data.unpaidInvoices || [];
                    this.supplierLedgerSummary = data.summary || {};
                }
            } catch (e) {
                console.error('Error fetching supplier ledger:', e);
            }
            this.isSupplierLedgerLoading = false;
        },

        generateRandomInvoiceNumber() {
            let now = new Date();
            let year = now.getFullYear().toString().slice(-2);
            let month = ('0' + (now.getMonth() + 1)).slice(-2);
            let day = ('0' + now.getDate()).slice(-2);
            let randomDigits = Math.floor(1000 + Math.random() * 9000);
            return `INV-${year}${month}${day}-${randomDigits}`;
        },

        openAddSupplierInvoiceModal() {
            if (!this.activeSupplier) return;
            this.newSupplierInvoiceNumber = this.generateRandomInvoiceNumber();
            this.newSupplierInvoiceAmount = '';
            this.newSupplierInvoiceDate = config.todayDate || new Date().toISOString().substring(0, 10);
            this.newSupplierInvoiceNotes = '';
            this.isSavingSupplierInvoice = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'addSupplierInvoiceModal' } }));
        },

        async saveSupplierInvoice() {
            if (this.isSavingSupplierInvoice || !this.activeSupplier) return;

            if (!this.newSupplierInvoiceNumber || !this.newSupplierInvoiceNumber.trim()) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterInvoiceNumber || 'الرجاء إدخال رقم الفاتورة', 'warning');
                return;
            }

            if (!this.newSupplierInvoiceAmount || Number(this.newSupplierInvoiceAmount) <= 0) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterAmount || 'الرجاء إدخال المبلغ', 'warning');
                return;
            }

            if (!this.newSupplierInvoiceDate) {
                Toast.show(config.translations.warning, config.translations.pleaseSelectDate || 'الرجاء تحديد التاريخ', 'warning');
                return;
            }

            this.isSavingSupplierInvoice = true;

            try {
                const res = await fetch(`${this.apiBase}/suppliers/${this.activeSupplier.id}/invoices`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf
                    },
                    body: JSON.stringify({
                        invoice_number: this.newSupplierInvoiceNumber.trim(),
                        total_amount: this.newSupplierInvoiceAmount,
                        invoice_date: this.newSupplierInvoiceDate,
                        notes: this.newSupplierInvoiceNotes ? this.newSupplierInvoiceNotes.trim() : null
                    })
                });

                const data = await res.json();

                if (res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'addSupplierInvoiceModal' } }));
                    Toast.show(config.translations.success, data.message || 'تم تسجيل الفاتورة بنجاح', 'success');
                    this.fetchSupplierLedger(this.activeSupplier.id);
                    this.fetchSuppliers();
                } else {
                    Toast.show(config.translations.warning, data.message || 'حدث خطأ أثناء الحفظ', 'error');
                }
            } catch (e) {
                console.error('Error saving invoice:', e);
            }
            this.isSavingSupplierInvoice = false;
        },

        confirmDeleteSupplierInvoice(invoiceId) {
            this.openDeleteModal('supplier_invoice', invoiceId);
        },

        async openDirectSupplierPayment(invoiceId, supplierId = null) {
            if (!supplierId && this.activeSupplier) {
                supplierId = this.activeSupplier.id;
            }

            if (supplierId) {
                if (!this.activeSupplier || this.activeSupplier.id != supplierId) {
                    let supplier = this.suppliers.find(s => s.id == supplierId);
                    if (!supplier) {
                        try {
                            const res = await fetch(`${this.apiBase}/suppliers?search=&per_page=100`);
                            const data = await res.json();
                            if (res.ok && data.suppliers) {
                                supplier = data.suppliers.find(s => s.id == supplierId);
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    }
                    if (supplier) {
                        this.activeSupplier = supplier;
                    }
                }
                
                if (!this.supplierUnpaidInvoices || this.supplierUnpaidInvoices.length === 0) {
                    await this.fetchSupplierLedger(supplierId);
                }
            }

            // Fallback: If still empty, but target invoice exists in allSupplierInvoicesList
            if ((!this.supplierUnpaidInvoices || this.supplierUnpaidInvoices.length === 0) && invoiceId) {
                const inv = this.allSupplierInvoicesList.find(i => i.id == invoiceId);
                if (inv && Number(inv.remaining_amount) > 0) {
                    this.supplierUnpaidInvoices = [{
                        id: inv.id,
                        invoice_number: inv.invoice_number,
                        total_amount: inv.total_amount,
                        paid_amount: inv.paid_amount || 0,
                        remaining_amount: inv.remaining_amount,
                        invoice_date: inv.invoice_date,
                        status: inv.status
                    }];
                }
            }

            this.openAddSupplierPaymentModal(invoiceId);
        },

        openAddSupplierPaymentModal(invoiceId = null) {
            if (!this.activeSupplier) {
                if (invoiceId) {
                    const inv = this.allSupplierInvoicesList.find(i => i.id == invoiceId);
                    if (inv) {
                        this.openDirectSupplierPayment(invoiceId, inv.supplier_id);
                        return;
                    }
                }
                return;
            }

            if (!this.supplierUnpaidInvoices || this.supplierUnpaidInvoices.length === 0) {
                if (invoiceId) {
                    const inv = this.allSupplierInvoicesList.find(i => i.id == invoiceId);
                    if (inv && Number(inv.remaining_amount) > 0) {
                        this.supplierUnpaidInvoices = [{
                            id: inv.id,
                            invoice_number: inv.invoice_number,
                            total_amount: inv.total_amount,
                            paid_amount: inv.paid_amount || 0,
                            remaining_amount: inv.remaining_amount,
                            invoice_date: inv.invoice_date,
                            status: inv.status
                        }];
                    }
                }
            }

            if (!this.supplierUnpaidInvoices || this.supplierUnpaidInvoices.length === 0) {
                Toast.show(config.translations.warning, config.translations.no_unpaid_invoices || 'لا توجد فواتير مستحقة الدفع لهذا المورد', 'warning');
                return;
            }

            if (invoiceId) {
                this.supplierPaymentInvoiceId = invoiceId;
                const targetInv = this.supplierUnpaidInvoices.find(inv => inv.id == invoiceId);
                this.supplierPaymentAmount = targetInv ? Number(targetInv.remaining_amount).toFixed(1) : '';
            } else {
                // Auto-select first unpaid invoice
                const firstInv = this.supplierUnpaidInvoices[0];
                this.supplierPaymentInvoiceId = firstInv.id;
                this.supplierPaymentAmount = Number(firstInv.remaining_amount).toFixed(1);
            }

            // Default bank account if available
            if (config.storeAccounts && config.storeAccounts.length > 0) {
                this.supplierPaymentBankAccountId = config.storeAccounts[0].id;
            } else {
                this.supplierPaymentBankAccountId = '';
            }

            this.supplierPaymentDate = config.todayDate || new Date().toISOString().substring(0, 10);
            this.supplierPaymentNotes = '';
            this.isSavingSupplierPayment = false;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'addSupplierPaymentModal' } }));
        },

        async saveSupplierPayment() {
            if (this.isSavingSupplierPayment || !this.activeSupplier) return;

            if (!this.supplierPaymentInvoiceId) {
                Toast.show(config.translations.warning, config.translations.please_select_invoice || 'الرجاء اختيار الفاتورة المستحقة', 'warning');
                return;
            }

            if (!this.supplierPaymentBankAccountId) {
                Toast.show(config.translations.warning, config.translations.selectAccount || 'الرجاء اختيار حساب الدفع', 'warning');
                return;
            }

            if (!this.supplierPaymentAmount || Number(this.supplierPaymentAmount) <= 0) {
                Toast.show(config.translations.warning, config.translations.pleaseEnterAmount || 'الرجاء إدخال المبلغ', 'warning');
                return;
            }

            // Check invoice remaining limit
            if (this.selectedInvoiceRemaining !== null && Number(this.supplierPaymentAmount) > this.selectedInvoiceRemaining) {
                Toast.show(config.translations.warning, `المبلغ المطلوب يتجاوز المتبقي من الفاتورة (${Number(this.selectedInvoiceRemaining).toFixed(1)} ₪)`, 'warning');
                return;
            }

            // Check available balance
            const available = Number(this.bankBalances[this.supplierPaymentBankAccountId] || 0);
            if (Number(this.supplierPaymentAmount) > available) {
                Toast.show(config.translations.warning, config.translations.amount_exceeds_balance || 'عذراً، المبلغ المطلوب أكبر من الرصيد المتوفر في الخزينة!', 'warning');
                return;
            }

            if (!this.supplierPaymentDate) {
                Toast.show(config.translations.warning, config.translations.pleaseSelectDate || 'الرجاء تحديد التاريخ', 'warning');
                return;
            }

            this.isSavingSupplierPayment = true;

            try {
                const res = await fetch(`${this.apiBase}/suppliers/${this.activeSupplier.id}/payments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf
                    },
                    body: JSON.stringify({
                        amount: this.supplierPaymentAmount,
                        store_bank_account_id: this.supplierPaymentBankAccountId,
                        store_supplier_invoice_id: this.supplierPaymentInvoiceId,
                        payment_date: this.supplierPaymentDate,
                        notes: this.supplierPaymentNotes ? this.supplierPaymentNotes.trim() : null
                    })
                });

                const data = await res.json();

                if (res.ok) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'addSupplierPaymentModal' } }));
                    Toast.show(config.translations.success, data.message || 'تم تسجيل وصرف الدفعة بنجاح', 'success');
                    this.fetchSupplierLedger(this.activeSupplier.id);
                    this.fetchSuppliers();
                    this.fetchAllSupplierInvoices();
                    this.fetchAllSupplierPayments();
                    this.fetchWithdrawals(); // refresh bank balances & withdrawals tab
                } else {
                    Toast.show(config.translations.warning, data.message || 'حدث خطأ أثناء الصرف', 'error');
                }
            } catch (e) {
                console.error('Error saving payment:', e);
            }
            this.isSavingSupplierPayment = false;
        },

        confirmDeleteSupplierPayment(paymentId) {
            this.openDeleteModal('supplier_payment', paymentId);
        }
    };
}

window.casherNotebook = casherNotebook;

window.addEventListener('open-modal', () => {
    setTimeout(() => {
        document.querySelectorAll('.overlay-panel, .drawer-panel, [x-ref$="Scroll"], .custom-scrollbar, [class*="overflow-y-auto"]').forEach(el => {
            if (el.offsetParent !== null) {
                el.scrollTop = 0;
            }
        });
    }, 50);
});

document.addEventListener('alpine:init', () => {
    if (typeof Alpine !== 'undefined') {
        Alpine.data('casherNotebook', (cfg) => window.casherNotebook(cfg));
    }
});

if (typeof window.Alpine !== 'undefined') {
    window.Alpine.data('casherNotebook', (cfg) => window.casherNotebook(cfg));
}


