<?php

namespace App\Http\Controllers\Website\Casher;

use App\Http\Controllers\Controller;
use App\Models\StoreSupplier;
use App\Models\StoreSupplierInvoice;
use App\Models\StoreSupplierPayment;
use App\Models\StoreBankAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CasherSupplierController extends Controller
{
    /**
     * Check if the casher is authorized.
     */
    protected function isAuthorized($ability, $fallback = 'notebook_read')
    {
        if (!Auth::guard('casher')->check()) {
            return false;
        }
        $user = Auth::guard('casher')->user();
        return $user->hasAbility($ability) || $user->hasAbility($fallback) || $user->id === 1 || $user->role_id === 1;
    }

    /**
     * Get the store ID for the current casher.
     */
    protected function getStoreId()
    {
        return Auth::guard('casher')->user()->store_id;
    }

    /**
     * API: Get suppliers with search, filter, and balances.
     */
    public function getSuppliers(Request $request)
    {
        if (!$this->isAuthorized('store_suppliers_read', 'notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $search = $request->query('search', '');
        $filter = $request->query('filter', 'all');
        $perPage = (int) $request->query('per_page', 20);

        $query = StoreSupplier::where('store_id', $storeId)
            ->withSum('invoices as total_invoices_amount', 'total_amount')
            ->withSum('payments as total_payments_amount', 'amount')
            ->withCount('invoices')
            ->withCount('payments');

        if ($search) {
            $searchTerms = array_filter(explode(' ', trim($search)));
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $termClean = str_replace(['أ', 'إ', 'آ'], 'ا', $term);
                    $termClean = str_replace('ة', 'ه', $termClean);
                    $termClean = str_replace('ى', 'ي', $termClean);

                    $q->where(function ($subQ) use ($term, $termClean) {
                        $subQ->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا'), 'ة', 'ه'), 'ى', 'ي') LIKE ?", ['%' . $termClean . '%'])
                            ->orWhere('mobile', 'LIKE', "%{$term}%")
                            ->orWhere('address', 'LIKE', "%{$term}%");
                    });
                }
            });
        }

        $allSuppliers = $query->latest()->get();

        // Calculate dynamic balances
        $allSuppliers->transform(function ($supplier) {
            $invoices = (float) ($supplier->total_invoices_amount ?? 0);
            $payments = (float) ($supplier->total_payments_amount ?? 0);
            $supplier->total_invoices = $invoices;
            $supplier->total_payments = $payments;
            $supplier->balance_due = $invoices - $payments;
            return $supplier;
        });

        $suppliersWithDueCount = $allSuppliers->filter(fn($s) => $s->balance_due > 0)->count();

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        // Apply in-memory filtering
        if ($filter === 'due' || $filter === 'has_due') {
            $filtered = $allSuppliers->filter(fn($s) => $s->balance_due > 0)->values();
        } elseif ($filter === 'cleared') {
            $filtered = $allSuppliers->filter(fn($s) => $s->balance_due <= 0)->values();
        } elseif ($filter === 'today') {
            $todaySupplierIds = StoreSupplierInvoice::where('store_id', $storeId)
                ->where(function ($q) use ($todayStart, $todayEnd) {
                    $q->whereBetween('invoice_date', [$todayStart, $todayEnd])
                      ->orWhereBetween('created_at', [$todayStart, $todayEnd]);
                })
                ->pluck('store_supplier_id')
                ->merge(
                    StoreSupplierPayment::where('store_id', $storeId)
                        ->where(function ($q) use ($todayStart, $todayEnd) {
                            $q->whereBetween('payment_date', [$todayStart, $todayEnd])
                              ->orWhereBetween('created_at', [$todayStart, $todayEnd]);
                        })
                        ->pluck('store_supplier_id')
                )->unique();
            $filtered = $allSuppliers->filter(fn($s) => $todaySupplierIds->contains($s->id))->values();
        } else {
            $filtered = $allSuppliers;
        }

        $totalSuppliers = $filtered->count();
        $totalActiveSuppliers = $allSuppliers->count();
        $suppliers = $filtered->take($perPage);

        // Overall Totals
        $totalPurchases = (float) StoreSupplierInvoice::where('store_id', $storeId)->sum('total_amount');
        $totalInvoicesCount = StoreSupplierInvoice::where('store_id', $storeId)->count();
        $totalPaid = (float) StoreSupplierPayment::where('store_id', $storeId)->sum('amount');
        $totalPaymentsCount = StoreSupplierPayment::where('store_id', $storeId)->count();
        $totalPendingDues = (float) StoreSupplierInvoice::where('store_id', $storeId)->sum('remaining_amount');
        $pendingInvoicesCount = StoreSupplierInvoice::where('store_id', $storeId)
            ->where('status', '!=', 'paid')
            ->where('remaining_amount', '>', 0)
            ->count();
        $totalSupplierDue = max(0, $totalPurchases - $totalPaid);

        return response()->json([
            'suppliers' => $suppliers,
            'totalSuppliers' => $totalSuppliers,
            'totalActiveSuppliers' => $totalActiveSuppliers,
            'suppliersWithDueCount' => $suppliersWithDueCount,
            'totalPurchases' => $totalPurchases,
            'totalInvoicesCount' => $totalInvoicesCount,
            'totalPaid' => $totalPaid,
            'totalPaymentsCount' => $totalPaymentsCount,
            'totalPendingDues' => $totalPendingDues,
            'pendingInvoicesCount' => $pendingInvoicesCount,
            'totalSupplierDue' => $totalSupplierDue,
        ]);
    }

    /**
     * API: Get all supplier invoices with optional status/search filter.
     */
    public function getAllSupplierInvoices(Request $request)
    {
        if (!$this->isAuthorized('store_supplier_invoices_read', 'notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $status = $request->query('status', 'all');
        $search = $request->query('search', '');

        $query = StoreSupplierInvoice::where('store_id', $storeId)
            ->with(['supplier:id,name,mobile,bank_name', 'createdBy:id,name']);

        if ($status === 'pending' || $status === 'unpaid') {
            $query->where(function ($q) {
                $q->where('status', '!=', 'paid')->where('remaining_amount', '>', 0);
            });
        } elseif ($status === 'paid') {
            $query->where('status', 'paid');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%{$search}%")->orWhere('mobile', 'LIKE', "%{$search}%");
                  });
            });
        }

        $invoices = $query->latest('id')->get();

        $invoices->transform(function ($inv) {
            return [
                'id' => $inv->id,
                'invoice_number' => $inv->invoice_number,
                'supplier_name' => $inv->supplier ? $inv->supplier->name : '-',
                'supplier_mobile' => $inv->supplier ? $inv->supplier->mobile : '-',
                'supplier_id' => $inv->store_supplier_id,
                'total_amount' => (float) $inv->total_amount,
                'paid_amount' => (float) $inv->paid_amount,
                'remaining_amount' => (float) $inv->remaining_amount,
                'status' => $inv->status,
                'invoice_date' => $inv->invoice_date,
                'created_at' => $inv->created_at ? $inv->created_at->format('Y-m-d H:i') : null,
                'cashier_name' => $inv->createdBy ? $inv->createdBy->name : null,
                'notes' => $inv->notes,
            ];
        });

        $totalPurchases = (float) StoreSupplierInvoice::where('store_id', $storeId)->sum('total_amount');
        $totalPaid = (float) StoreSupplierInvoice::where('store_id', $storeId)->sum('paid_amount');
        $totalRemaining = (float) StoreSupplierInvoice::where('store_id', $storeId)->sum('remaining_amount');

        return response()->json([
            'invoices' => $invoices,
            'totalAmount' => (float) $invoices->sum('total_amount'),
            'totalPaid' => (float) $invoices->sum('paid_amount'),
            'totalRemaining' => (float) $invoices->sum('remaining_amount'),
            'count' => $invoices->count(),
            'overallTotalPurchases' => $totalPurchases,
            'overallTotalPaid' => $totalPaid,
            'overallTotalRemaining' => $totalRemaining,
        ]);
    }

    /**
     * API: Get all supplier payments with search.
     */
    public function getAllSupplierPayments(Request $request)
    {
        if (!$this->isAuthorized('store_supplier_payments_read', 'notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $search = $request->query('search', '');

        $query = StoreSupplierPayment::where('store_id', $storeId)
            ->with(['supplier:id,name,mobile,bank_name', 'invoice:id,invoice_number,total_amount', 'bankAccount.paymentEntity', 'createdBy:id,name']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('supplier', function ($sq) use ($search) {
                    $sq->where('name', 'LIKE', "%{$search}%")->orWhere('mobile', 'LIKE', "%{$search}%");
                })->orWhereHas('invoice', function ($iq) use ($search) {
                    $iq->where('invoice_number', 'LIKE', "%{$search}%");
                });
            });
        }

        $payments = $query->latest('id')->get();
        $locale = app()->getLocale();

        $payments->transform(function ($p) use ($locale) {
            $bankAccountName = '-';
            if ($p->bankAccount) {
                $entityName = optional($p->bankAccount->paymentEntity)->getTranslation('name', $locale)
                    ?: optional($p->bankAccount->paymentEntity)->getTranslation('name', 'ar')
                    ?: 'حساب';
                $bankAccountName = $p->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' (' . $p->bankAccount->account_number . ')';
            }

            return [
                'id' => $p->id,
                'amount' => (float) $p->amount,
                'payment_date' => $p->payment_date,
                'supplier_name' => $p->supplier ? $p->supplier->name : '-',
                'supplier_mobile' => $p->supplier ? $p->supplier->mobile : '-',
                'supplier_id' => $p->store_supplier_id,
                'invoice_number' => $p->invoice ? $p->invoice->invoice_number : '-',
                'bank_account_name' => $bankAccountName,
                'cashier_name' => $p->createdBy ? $p->createdBy->name : null,
                'created_at' => $p->created_at ? $p->created_at->format('Y-m-d H:i') : null,
                'notes' => $p->notes,
            ];
        });

        return response()->json([
            'payments' => $payments,
            'totalAmount' => (float) $payments->sum('amount'),
            'count' => $payments->count(),
            'overallTotalPaid' => (float) StoreSupplierPayment::where('store_id', $storeId)->sum('amount'),
        ]);
    }

    /**
     * API: Get today's supplier invoices (backward compatibility).
     */
    public function getTodaySupplierInvoices(Request $request)
    {
        return $this->getAllSupplierInvoices($request);
    }

    /**
     * API: Get today's supplier payments (backward compatibility).
     */
    public function getTodaySupplierPayments(Request $request)
    {
        return $this->getAllSupplierPayments($request);
    }

    /**
     * API: Store a new supplier.
     */
    public function storeSupplier(Request $request)
    {
        if (!$this->isAuthorized('store_suppliers_create', 'notebook_create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        try {
            $supplier = StoreSupplier::create([
                'store_id' => $this->getStoreId(),
                'name' => $validated['name'],
                'mobile' => $validated['mobile'],
                'bank_name' => !empty($validated['bank_name']) ? $validated['bank_name'] : '-',
                'account_number' => !empty($validated['account_number']) ? $validated['account_number'] : '-',
                'address' => $validated['address'] ?? null,
                'email' => $validated['email'] ?? null,
                'status' => true,
                'created_by' => Auth::guard('casher')->id(),
            ]);

            return response()->json([
                'supplier' => $supplier,
                'message' => __('notebook.supplier_added', ['name' => $supplier->name]) ?? ('تمت إضافة المورد ' . $supplier->name)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating supplier: ' . $e->getMessage());
            return response()->json([
                'message' => 'حدث خطأ أثناء إضافة المورد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update an existing supplier.
     */
    public function updateSupplier(Request $request, $id)
    {
        if (!$this->isAuthorized('store_suppliers_update', 'notebook_update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $supplier = StoreSupplier::where('store_id', $this->getStoreId())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        try {
            $supplier->update([
                'name' => $validated['name'],
                'mobile' => $validated['mobile'],
                'bank_name' => !empty($validated['bank_name']) ? $validated['bank_name'] : ($supplier->bank_name ?: '-'),
                'account_number' => !empty($validated['account_number']) ? $validated['account_number'] : ($supplier->account_number ?: '-'),
                'address' => $validated['address'] ?? null,
                'email' => $validated['email'] ?? null,
            ]);

            return response()->json([
                'supplier' => $supplier,
                'message' => __('notebook.supplier_updated') ?? 'تم تعديل بيانات المورد بنجاح'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating supplier: ' . $e->getMessage());
            return response()->json([
                'message' => 'حدث خطأ أثناء تعديل المورد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get supplier ledger (Invoices, Payments, Unpaid Invoices, Summary).
     */
    public function getLedger(Request $request, $supplierId)
    {
        if (!$this->isAuthorized('store_suppliers_read', 'notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $supplier = StoreSupplier::where('store_id', $storeId)->findOrFail($supplierId);

        // Fetch Invoices
        $invoices = StoreSupplierInvoice::where('store_id', $storeId)
            ->where('store_supplier_id', $supplierId)
            ->with(['createdBy'])
            ->orderBy('invoice_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $invoices->transform(function ($inv) {
            $inv->cashier_name = $inv->createdBy ? $inv->createdBy->name : null;
            return $inv;
        });

        // Fetch Payments
        $payments = StoreSupplierPayment::where('store_id', $storeId)
            ->where('store_supplier_id', $supplierId)
            ->with(['createdBy', 'invoice', 'bankAccount.paymentEntity'])
            ->orderBy('payment_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $payments->transform(function ($p) {
            $p->cashier_name = $p->createdBy ? $p->createdBy->name : null;
            if ($p->store_bank_account_id && $p->bankAccount) {
                $entityName = optional($p->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($p->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $p->bank_account_name = $p->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $p->bankAccount->account_number;
            } else {
                $p->bank_account_name = null;
            }
            return $p;
        });

        // Unpaid or partially paid invoices for the payment modal dropdown
        $unpaidInvoices = StoreSupplierInvoice::where('store_id', $storeId)
            ->where('store_supplier_id', $supplierId)
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->orderBy('invoice_date', 'asc')
            ->get(['id', 'invoice_number', 'total_amount', 'paid_amount', 'remaining_amount', 'invoice_date', 'status']);

        $totalPurchases = (float) $invoices->sum('total_amount');
        $totalPaid = (float) $payments->sum('amount');
        $balanceDue = $totalPurchases - $totalPaid;

        return response()->json([
            'supplier' => $supplier,
            'invoices' => $invoices,
            'payments' => $payments,
            'unpaidInvoices' => $unpaidInvoices,
            'summary' => [
                'totalPurchases' => $totalPurchases,
                'totalPaid' => $totalPaid,
                'balanceDue' => $balanceDue,
                'invoicesCount' => $invoices->count(),
                'paymentsCount' => $payments->count(),
            ]
        ]);
    }

    /**
     * API: Store a new supplier invoice.
     */
    public function storeInvoice(Request $request, $supplierId)
    {
        if (!$this->isAuthorized('store_supplier_invoices_create', 'notebook_create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $supplier = StoreSupplier::where('store_id', $storeId)->findOrFail($supplierId);

        $request->validate([
            'invoice_number' => 'required|string|max:100',
            'total_amount' => 'required|numeric|min:0.1',
            'invoice_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $invoice = StoreSupplierInvoice::create([
            'store_id' => $storeId,
            'store_supplier_id' => $supplier->id,
            'invoice_number' => $request->invoice_number,
            'total_amount' => $request->total_amount,
            'paid_amount' => 0,
            'remaining_amount' => $request->total_amount,
            'invoice_date' => $request->invoice_date,
            'status' => 'unpaid',
            'notes' => $request->notes,
            'created_by' => Auth::guard('casher')->id(),
        ]);

        return response()->json([
            'invoice' => $invoice,
            'message' => __('notebook.invoice_registered') ?? 'تم تسجيل الفاتورة بنجاح'
        ]);
    }

    /**
     * API: Update a supplier invoice.
     */
    public function updateInvoice(Request $request, $id)
    {
        if (!$this->isAuthorized('store_supplier_invoices_update', 'notebook_update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $invoice = StoreSupplierInvoice::where('store_id', $storeId)->findOrFail($id);

        $request->validate([
            'invoice_number' => 'required|string|max:100',
            'total_amount' => 'required|numeric|min:0.1',
            'invoice_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $totalPaid = (float) $invoice->payments()->sum('amount');
        $newTotal = (float) $request->total_amount;

        if ($newTotal < $totalPaid) {
            return response()->json([
                'message' => __('notebook.invoice_amount_less_than_paid') ?? 'لا يمكن تعديل إجمالي الفاتورة ليكون أقل من المبالغ المدفوعة مسبقاً لها.'
            ], 422);
        }

        $remaining = $newTotal - $totalPaid;
        $status = 'unpaid';
        if ($totalPaid >= $newTotal && $newTotal > 0) {
            $status = 'paid';
        } elseif ($totalPaid > 0) {
            $status = 'partially_paid';
        }

        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'total_amount' => $newTotal,
            'remaining_amount' => $remaining,
            'invoice_date' => $request->invoice_date,
            'status' => $status,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'invoice' => $invoice,
            'message' => __('notebook.invoice_updated') ?? 'تم تعديل الفاتورة بنجاح'
        ]);
    }

    /**
     * API: Delete a supplier invoice.
     */
    public function destroyInvoice($id)
    {
        if (!$this->isAuthorized('store_supplier_invoices_delete', 'notebook_delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $invoice = StoreSupplierInvoice::where('store_id', $storeId)->findOrFail($id);

        if ($invoice->payments()->count() > 0) {
            return response()->json([
                'message' => __('notebook.cannot_delete_invoice_with_payments') ?? 'لا يمكن حذف الفاتورة لوجود دفعات مسجلة عليها.'
            ], 422);
        }

        $invoice->delete();

        return response()->json([
            'message' => __('notebook.invoice_deleted') ?? 'تم حذف الفاتورة بنجاح'
        ]);
    }

    /**
     * API: Store a new supplier payment (Payout).
     */
    public function storePayment(Request $request, $supplierId)
    {
        if (!$this->isAuthorized('store_supplier_payments_create', 'notebook_create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $supplier = StoreSupplier::where('store_id', $storeId)->findOrFail($supplierId);

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'store_bank_account_id' => [
                'required',
                Rule::exists('store_bank_accounts', 'id')->where('store_id', $storeId)
            ],
            'store_supplier_invoice_id' => [
                'required',
                Rule::exists('store_supplier_invoices', 'id')->where('store_id', $storeId)->where('store_supplier_id', $supplierId)
            ],
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check invoice remaining balance & status
        $invoice = StoreSupplierInvoice::where('store_id', $storeId)
            ->where('store_supplier_id', $supplierId)
            ->findOrFail($request->store_supplier_invoice_id);

        if ($invoice->status === 'paid' || (float) $invoice->remaining_amount <= 0) {
            return response()->json([
                'message' => __('notebook.invoice_already_paid') ?? 'هذه الفاتورة مسددة بالكامل بالفعل.',
                'errors' => [
                    'store_supplier_invoice_id' => [__('notebook.invoice_already_paid') ?? 'هذه الفاتورة مسددة بالكامل بالفعل.']
                ]
            ], 422);
        }

        if ((float) $request->amount > (float) $invoice->remaining_amount) {
            $msg = __('notebook.amount_exceeds_invoice_remaining', [
                'amount' => number_format((float) $request->amount, 2),
                'remaining' => number_format((float) $invoice->remaining_amount, 2)
            ]);
            return response()->json([
                'message' => $msg,
                'errors' => [
                    'amount' => [$msg]
                ]
            ], 422);
        }

        // Check bank account balance
        $bankAccount = StoreBankAccount::where('store_id', $storeId)->find($request->store_bank_account_id);
        if ($bankAccount) {
            $availableBalance = (float) $bankAccount->current_balance;
            if ((float) $request->amount > $availableBalance) {
                return response()->json([
                    'message' => __('store_withdrawals.insufficient_balance', ['balance' => number_format($availableBalance, 2)]) ?? 'عذراً، الرصيد المتوفر في الخزينة لا يكفي لدفع هذا المبلغ!',
                    'errors' => [
                        'amount' => [__('store_withdrawals.insufficient_balance', ['balance' => number_format($availableBalance, 2)])]
                    ]
                ], 422);
            }
        }

        return DB::transaction(function () use ($request, $storeId, $supplier) {
            // Note: StoreSupplierPaymentObserver will automatically:
            // 1. Create a StoreWithdrawal record linked to this payment and bank account.
            // 2. StoreWithdrawalObserver will decrement the StoreBankAccount current_balance.
            // 3. Recalculate the invoice status and remaining amount since invoice_id is required.
            $payment = StoreSupplierPayment::create([
                'store_id' => $storeId,
                'store_supplier_id' => $supplier->id,
                'store_supplier_invoice_id' => $request->store_supplier_invoice_id,
                'store_bank_account_id' => $request->store_bank_account_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'notes' => $request->notes,
                'created_by' => Auth::guard('casher')->id(),
            ]);

            return response()->json([
                'payment' => $payment,
                'message' => __('notebook.payment_registered') ?? 'تم تسجيل وصرف الدفعة بنجاح'
            ]);
        });
    }

    /**
     * API: Delete a supplier payment.
     */
    public function destroyPayment($id)
    {
        if (!$this->isAuthorized('store_supplier_payments_delete', 'notebook_delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $payment = StoreSupplierPayment::where('store_id', $storeId)->findOrFail($id);

        return DB::transaction(function () use ($payment) {
            // Observer will automatically delete withdrawal, refund bank balance, and recalculate invoice
            $payment->delete();

            return response()->json([
                'message' => __('notebook.payment_deleted') ?? 'تم حذف الدفعة وإرجاع المبلغ للخزينة بنجاح'
            ]);
        });
    }
}
