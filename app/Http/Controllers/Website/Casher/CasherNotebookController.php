<?php

namespace App\Http\Controllers\Website\Casher;

use App\Http\Controllers\Controller;
use App\Models\StoreCustomer;
use App\Models\StoreTransaction;
use App\Models\StoreWithdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CasherNotebookController extends Controller
{
    /**
     * Check if the casher is authorized for the given ability.
     */
    protected function isAuthorized($ability)
    {
        return Auth::guard('casher')->check() && Auth::guard('casher')->user()->hasAbility($ability);
    }

    /**
     * Get the store ID for the current casher.
     */
    protected function getStoreId()
    {
        return Auth::guard('casher')->user()->store_id;
    }

    /**
     * Render the main notebook view.
     */
    public function index()
    {
        if (!$this->isAuthorized('notebook_read')) {
            abort(403, 'Unauthorized access to the notebook.');
        }

        $storeBankAccounts = \App\Models\StoreBankAccount::with('paymentEntity')
            ->where('store_id', $this->getStoreId())
            ->get();

        return view('website.casher.notebook.index', compact('storeBankAccounts'));
    }

    /**
     * API: Get customers with search, filter, and pagination.
     */
    public function getCustomers(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $search = $request->query('search', '');
        $filter = $request->query('filter', 'all');
        $perPage = (int) $request->query('per_page', 15);

        $query = StoreCustomer::where('store_id', $storeId);

        if ($search || $filter !== 'all') {
            $query->where(function($q) use ($search, $filter) {
                if ($search) {
                    $searchTerms = array_filter(explode(' ', trim($search)));
                    $q->where(function($termQ) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $termClean = str_replace(['أ', 'إ', 'آ'], 'ا', $term);
                            $termClean = str_replace('ة', 'ه', $termClean);
                            $termClean = str_replace('ى', 'ي', $termClean);
                            
                            $termQ->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا'), 'ة', 'ه'), 'ى', 'ي') LIKE ?", ['%' . $termClean . '%']);
                        }
                    });
                }
                if ($filter === 'debt' || $filter === 'highest_debt' || $filter === 'oldest_debt') {
                    $q->where('balance', '>', 0);
                } elseif ($filter === 'paid') {
                    $q->where('balance', '=', 0);
                } elseif ($filter === 'credit') {
                    $q->where('balance', '<', 0);
                } elseif ($filter === 'disabled') {
                    $q->where('status', 0);
                }
            });
        }

        $totalCustomers = $query->count();
        
        if ($filter === 'highest_debt') {
            $customers = $query->orderByDesc('is_walk_in')->orderByDesc('balance')->latest()->take($perPage)->get();
        } elseif ($filter === 'oldest_debt') {
            $lastTxSubquery = StoreTransaction::select('transaction_date')
                ->whereColumn('store_customer_id', 'store_customers.id')
                ->latest('transaction_date')
                ->limit(1);

            $customers = $query->addSelect([
                    'last_activity_date' => $lastTxSubquery
                ])
                ->orderByDesc('is_walk_in')
                ->orderByRaw('COALESCE(last_activity_date, store_customers.created_at) ASC')
                ->take($perPage)
                ->get();
        } else {
            $customers = $query->orderByDesc('is_walk_in')->latest()->take($perPage)->get();
        }

        $response = [
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
        ];

        // Only calculate heavy stats if there is no search query (initial load / page reset)
        if (empty($search)) {
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();

            $totalDebt = StoreCustomer::where('store_id', $storeId)->where('balance', '>', 0)->sum('balance');
            $todayCollections = StoreTransaction::where('store_id', $storeId)
                ->where('type', 'payment')
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
            $todayDirectSales = StoreTransaction::where('store_id', $storeId)
                ->where('type', 'direct_sale')
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');
            $todayDebts = StoreTransaction::where('store_id', $storeId)
                ->where('type', 'debt')
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->sum('amount');

            $response = array_merge($response, [
                'totalDebt' => $totalDebt,
                'todayCollections' => $todayCollections,
                'todayDirectSales' => $todayDirectSales,
                'todayDebts' => $todayDebts,
            ]);
        }

        return response()->json($response);
    }

    /**
     * API: Get financial summary for today, week, and month.
     */
    public function getFinancialSummary(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();

        $periods = [
            'today' => [Carbon::today()->startOfDay(), Carbon::now()],
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()],
        ];

        if ($request->has('custom_date')) {
            $customDate = Carbon::parse($request->custom_date);
            $periods['custom'] = [$customDate->startOfDay(), $customDate->copy()->endOfDay()];
        }

        $summary = [];

        foreach ($periods as $key => $dates) {
            $summary[$key] = [
                'collections' => StoreTransaction::where('store_id', $storeId)
                    ->where('type', 'payment')
                    ->whereBetween('created_at', [$dates[0], $dates[1]])
                    ->sum('amount'),
                'direct_sales' => StoreTransaction::where('store_id', $storeId)
                    ->where('type', 'direct_sale')
                    ->whereBetween('created_at', [$dates[0], $dates[1]])
                    ->sum('amount'),
                'debts' => StoreTransaction::where('store_id', $storeId)
                    ->where('type', 'debt')
                    ->whereBetween('created_at', [$dates[0], $dates[1]])
                    ->sum('amount'),
            ];
        }

        return response()->json(['summary' => $summary]);
    }

    /**
     * API: Add a new customer.
     */
    public function storeCustomer(Request $request)
    {
        if (!$this->isAuthorized('notebook_create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function() use ($request) {
            $customer = StoreCustomer::create([
                'store_id' => $this->getStoreId(),
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            if ($request->filled('opening_balance') && $request->opening_balance > 0) {
                StoreTransaction::create([
                    'store_id' => $customer->store_id,
                    'store_customer_id' => $customer->id,
                    'type' => 'debt',
                    'amount' => $request->opening_balance,
                    'transaction_date' => now(),
                    'description' => __('notebook.opening_balance') ?? 'رصيد افتتاحي',
                    'created_by' => Auth::guard('casher')->id(),
                ]);
            }

            return response()->json(['customer' => $customer, 'message' => __('notebook.customer_added') . ' ' . $customer->name]);
        });
    }

    /**
     * API: Update an existing customer.
     */
    public function updateCustomer(Request $request, $id)
    {
        if (!$this->isAuthorized('notebook_update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $customer = StoreCustomer::where('store_id', $this->getStoreId())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json(['customer' => $customer, 'message' => __('notebook.customer_updated') ?? 'تم تحديث بيانات الزبون']);
    }

    /**
     * API: Get ledger for a specific customer.
     */
    public function getLedger(Request $request, $customerId)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $customer = StoreCustomer::where('store_id', $this->getStoreId())->findOrFail($customerId);
        $perPage = (int) $request->query('per_page', 20);

        $transactions = $customer->transactions()
            ->selectRaw("store_transactions.*, SUM(CASE WHEN type = 'debt' THEN amount ELSE -amount END) OVER (PARTITION BY store_customer_id ORDER BY transaction_date ASC, id ASC) as running_balance")
            ->with(['createdBy', 'bankAccount.paymentEntity'])
            ->reorder()
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take($perPage)
            ->get();
            
        $transactions->transform(function ($tx) {
            $tx->cashier_name = $tx->createdBy ? $tx->createdBy->name : null;
            
            if ($tx->type === 'payment' && $tx->store_bank_account_id && $tx->bankAccount) {
                $entityName = optional($tx->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($tx->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $tx->bank_account_name = $tx->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $tx->bankAccount->account_number;
            } else {
                $tx->bank_account_name = null;
            }
            
            return $tx;
        });

        $totalTransactions = $customer->transactions()->count();

        return response()->json([
            'customer' => $customer,
            'transactions' => $transactions,
            'totalLedgerTransactions' => $totalTransactions,
        ]);
    }

    /**
     * API: Get today's collections (payments and direct sales).
     */
    public function getTodayCollections(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $perPage = (int) $request->query('per_page', 20);
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $transactions = StoreTransaction::with(['customer', 'createdBy', 'bankAccount.paymentEntity'])
            ->where('store_id', $this->getStoreId())
            ->whereIn('type', ['payment', 'direct_sale'])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $transactions->getCollection()->transform(function ($tx) {
            $tx->cashier_name = $tx->createdBy ? $tx->createdBy->name : null;
            
            if (($tx->type === 'payment' || $tx->type === 'direct_sale') && $tx->store_bank_account_id && $tx->bankAccount) {
                $entityName = optional($tx->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($tx->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $tx->bank_account_name = $tx->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $tx->bankAccount->account_number;
            } else {
                $tx->bank_account_name = null;
            }
            
            return $tx;
        });

        return response()->json([
            'transactions' => $transactions->items(),
            'total' => $transactions->total(),
            'hasMore' => $transactions->hasMorePages()
        ]);
    }

    /**
     * API: Get today's debts.
     */
    public function getTodayDebts(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $perPage = (int) $request->query('per_page', 20);
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $transactions = StoreTransaction::with(['customer', 'createdBy'])
            ->where('store_id', $this->getStoreId())
            ->where('type', 'debt')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $transactions->getCollection()->transform(function ($tx) {
            $tx->cashier_name = $tx->createdBy ? $tx->createdBy->name : null;
            return $tx;
        });

        return response()->json([
            'transactions' => $transactions->items(),
            'total' => $transactions->total(),
            'hasMore' => $transactions->hasMorePages()
        ]);
    }

    /**
     * API: Get today's direct sales.
     */
    public function getTodayDirectSales(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $perPage = (int) $request->query('per_page', 20);
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $transactions = StoreTransaction::with(['customer', 'createdBy', 'bankAccount.paymentEntity'])
            ->where('store_id', $this->getStoreId())
            ->where('type', 'direct_sale')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $transactions->getCollection()->transform(function ($tx) {
            $tx->cashier_name = $tx->createdBy ? $tx->createdBy->name : null;
            
            if ($tx->store_bank_account_id && $tx->bankAccount) {
                $entityName = optional($tx->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($tx->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $tx->bank_account_name = $tx->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $tx->bankAccount->account_number;
            } else {
                $tx->bank_account_name = null;
            }
            
            return $tx;
        });

        return response()->json([
            'transactions' => $transactions->items(),
            'total' => $transactions->total(),
            'hasMore' => $transactions->hasMorePages()
        ]);
    }

    /**
     * API: Store a new transaction.
     */
    public function storeTransaction(Request $request, $customerId)
    {
        if (!$this->isAuthorized('notebook_create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $customer = StoreCustomer::where('store_id', $storeId)->findOrFail($customerId);

        if ($customer->status == 0 && $request->type === 'debt' && !$request->boolean('is_direct_sale')) {
            return response()->json(['message' => __('notebook.customer_disabled_cannot_add_debt') ?? 'العميل معطل، لا يمكن إضافة ديون، يُسمح بتسديد الدفعات والشراء المباشر فقط.'], 403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'type' => 'required|in:debt,payment',
            'store_bank_account_id' => [
                'nullable',
                'required_if:type,payment',
                Rule::exists('store_bank_accounts', 'id')->where('store_id', $storeId)
            ],
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'is_direct_sale' => 'nullable|boolean',
        ]);

        return DB::transaction(function() use ($request, $customer, $storeId) {
            if ($request->boolean('is_direct_sale')) {
                // Direct POS sale: record debt then immediate payment atomically
                $debtTx = new StoreTransaction([
                    'store_id' => $storeId,
                    'store_customer_id' => $customer->id,
                    'type' => 'debt',
                    'amount' => $request->amount,
                    'transaction_date' => $request->transaction_date,
                    'description' => $request->description ?: 'مبيعات',
                    'created_by' => Auth::guard('casher')->id(),
                ]);
                $debtTx->skip_limit_check = true;
                $debtTx->save();

                $tx = StoreTransaction::create([
                    'store_id' => $storeId,
                    'store_customer_id' => $customer->id,
                    'store_bank_account_id' => $request->store_bank_account_id,
                    'type' => 'payment',
                    'amount' => $request->amount,
                    'transaction_date' => $request->transaction_date,
                    'description' => $request->description ?: 'دفع مباشر',
                    'linked_transaction_id' => $debtTx->id,
                    'created_by' => Auth::guard('casher')->id(),
                ]);

                // Link debt to payment
                $debtTx->updateQuietly(['linked_transaction_id' => $tx->id]);
            } else {
                $description = $request->description ?: ($request->type === 'debt' ? __('notebook.debt') : __('notebook.payment'));

                $tx = StoreTransaction::create([
                    'store_id' => $storeId,
                    'store_customer_id' => $customer->id,
                    'store_bank_account_id' => $request->type === 'payment' ? $request->store_bank_account_id : null,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'transaction_date' => $request->transaction_date,
                    'description' => $description,
                    'created_by' => Auth::guard('casher')->id(),
                ]);
            }

            $customer->refresh();

            return response()->json([
                'transaction' => $tx,
                'customer' => $customer,
                'message' => $request->type === 'debt' ? __('notebook.debt_registered') : __('notebook.payment_registered'),
            ]);
        });
    }

    /**
     * API: Update a transaction.
     */
    public function updateTransaction(Request $request, $id)
    {
        if (!$this->isAuthorized('notebook_update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $tx = StoreTransaction::where('store_id', $storeId)->findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'type' => 'required|in:debt,payment',
            'store_bank_account_id' => [
                'nullable',
                'required_if:type,payment',
                Rule::exists('store_bank_accounts', 'id')->where('store_id', $storeId)
            ],
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        return DB::transaction(function() use ($request, $tx) {
            $description = $request->description ?: ($request->type === 'debt' ? __('notebook.debt') : __('notebook.payment'));

            $tx->update([
                'type' => $request->type,
                'store_bank_account_id' => $request->type === 'payment' ? $request->store_bank_account_id : null,
                'amount' => $request->amount,
                'transaction_date' => $request->transaction_date,
                'description' => $description,
            ]);

            $customer = $tx->customer()->first();
            if ($customer) {
                $customer->refresh();
            }

            return response()->json([
                'transaction' => $tx,
                'customer' => $customer,
                'message' => __('notebook.transaction_updated'),
            ]);
        });
    }

    /**
     * API: Delete a transaction.
     */
    public function destroyTransaction($id)
    {
        if (!$this->isAuthorized('notebook_delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tx = StoreTransaction::where('store_id', $this->getStoreId())->findOrFail($id);

        return DB::transaction(function() use ($tx) {
            $customer = $tx->customer()->first();
            $tx->forceDelete();
            if ($customer) {
                $customer->refresh();
            }

            return response()->json([
                'customer' => $customer,
                'message' => __('notebook.transaction_deleted'),
            ]);
        });
    }

    /**
     * API: Process AI Voice Command to extract transaction details.
     */
    public function processAIVoiceCommand(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'text' => 'required|string|max:1000'
        ]);

        $text = $request->text;
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json(['message' => 'Gemini API Key is missing from .env'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "أنت مساعد كاشير ذكي. قام الكاشير بنطق هذه الجملة: \"{$text}\". 
الرجاء استخراج البيانات التالية:
- customer_name: اسم الزبون.
- amount: قيمة المبلغ كرقم فقط.
- type: نوع الحركة (يجب أن يكون حصرياً إما 'debt' إذا كانت دين/شراء بالآجل، أو 'payment' إذا كانت دفعة/سداد).
- notes: الأصناف والمشتريات التي أخذها الزبون (مثال: لحوم، شيبس). إذا لم تذكر اجعلها فارغة.
أرجع النتيجة بصيغة JSON فقط. مثال: {\"customer_name\": \"محمد\", \"amount\": 50, \"type\": \"debt\", \"notes\": \"لحوم وشيبس\"}"
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error: ' . $response->body());
                $errorMsg = $response->json('error.message') ?? 'خطأ غير معروف من جوجل';
                return response()->json(['message' => 'خطأ من جوجل: ' . $errorMsg], 500);
            }

            $body = $response->json();
            $aiText = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            // Clean markdown formatting if Gemini returns ```json ... ```
            $aiText = preg_replace('/```json\s*/i', '', $aiText);
            $aiText = preg_replace('/```\s*/', '', $aiText);
            
            $data = json_decode(trim($aiText), true);

            if (!$data || !isset($data['customer_name']) || !isset($data['amount']) || !isset($data['type'])) {
                return response()->json(['message' => 'لم يتمكن الذكاء الاصطناعي من فهم الجملة بوضوح'], 400);
            }

            // Search for customer safely
            $search = $data['customer_name'];
            $searchTerms = array_filter(explode(' ', trim($search)));
            
            $customerQuery = StoreCustomer::where('store_id', $this->getStoreId());
            
            if (!empty($searchTerms)) {
                $customerQuery->where(function($q) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $termClean = str_replace(['أ', 'إ', 'آ'], 'ا', $term);
                        $termClean = str_replace('ة', 'ه', $termClean);
                        $termClean = str_replace('ى', 'ي', $termClean);
                        
                        $q->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا'), 'ة', 'ه'), 'ى', 'ي') LIKE ?", ['%' . $termClean . '%']);
                    }
                });
            }

            $customer = $customerQuery->first();

            return response()->json([
                'success' => true,
                'customer' => $customer,
                'parsed_name' => $data['customer_name'],
                'amount' => $data['amount'],
                'type' => $data['type'],
                'notes' => $data['notes'] ?? ''
            ]);

        } catch (\Exception $e) {
            Log::error('AI Voice Error: ' . $e->getMessage());
            return response()->json(['message' => 'حدث خطأ في معالجة الصوت: ' . $e->getMessage()], 500);
        }
    }
}
