<?php

namespace App\Livewire\Website\Casher;

use App\Models\StoreCustomer;
use App\Models\StoreTransaction;
use Carbon\Carbon;
use Livewire\Component;

class CasherNotebook extends Component
{
    use \Livewire\WithPagination;

    public $store_id;

    // Search & Filter
    public $search = '';
    public $filter = 'all'; // all, debt, paid

    // New Customer
    public $newCustomerName = '';
    public $newCustomerPhone = '';

    // Ledger Modal State
    public $activeCustomer = null;
    public $ledgerPage = 1;
    public $perPage = 5;
    
    public $customersPerPage = 5;

    // New / Edit Transaction
    public $txType = 'debt';
    public $txAmount = '';
    public $txDescription = '';
    public $editingTxId = null;
    public $txDate = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function mount()
    {
        $this->store_id = auth('casher')->user()->store_id;

        if (!auth('casher')->user()->hasAbility('notebook_read')) {
            abort(403, 'Unauthorized access to the notebook.');
        }
    }

    protected function isAuthorized($ability)
    {
        return auth('casher')->check() && auth('casher')->user()->hasAbility($ability);
    }

    public function loadMoreCustomers()
    {
        $this->customersPerPage += 20;
    }

    public function addCustomer()
    {
        \Log::info('Adding customer called!');
        if (!$this->isAuthorized('notebook_create')) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate([
            'newCustomerName' => 'required|string|max:255',
            'newCustomerPhone' => 'nullable|string|max:10',
        ]);
        $this->activeCustomer = StoreCustomer::create([
            'store_id' => $this->store_id,
            'name' => $this->newCustomerName,
            'phone' => $this->newCustomerPhone,
        ]);

        $this->newCustomerName = '';
        $this->newCustomerPhone = '';
        $this->dispatch('close-modal', id: 'addCustomerModal');

        $this->dispatch('toast', [
            'title' => __('notebook.success'),
            'message' => __('notebook.customer_added') . $this->activeCustomer->name,
            'type' => 'success'
        ]);

        $this->dispatch('open-modal', id: 'ledgerModal');
    }

    public function openLedger($customerId)
    {
        \Log::info('openLedger called with ID: ' . $customerId);
        $this->activeCustomer = StoreCustomer::find($customerId);
        $this->ledgerPage = 1;
        $this->dispatch('open-modal', id: 'ledgerModal');
    }

    public function loadMoreLedger()
    {
        $this->ledgerPage++;
    }

    public function openTxModal($type)
    {
        $this->txType = $type;
        $this->editingTxId = null;
        $this->reset(['txAmount', 'txDescription']);
        $this->txDate = Carbon::today()->format('Y-m-d');
        $this->dispatch('open-modal', id: 'transactionModal');
    }

    public function editTransaction($id)
    {
        if (!$this->isAuthorized('notebook_update')) {
            abort(403, 'Unauthorized action.');
        }

        $tx = StoreTransaction::find($id);
        if (!$tx) return;

        $this->editingTxId = $tx->id;
        $this->txType = $tx->type;
        $this->txAmount = $tx->amount;
        $this->txDescription = $tx->description;
        $this->txDate = $tx->transaction_date ? $tx->transaction_date->format('Y-m-d') : $tx->created_at->format('Y-m-d');
        $this->dispatch('open-modal', id: 'transactionModal');
    }

    public function deleteTransaction($id)
    {
        if (!$this->isAuthorized('notebook_delete')) {
            abort(403, 'Unauthorized action.');
        }

        $tx = StoreTransaction::find($id);
        if ($tx) {
            $tx->delete();
            $this->activeCustomer->refresh();
            $this->dispatch('toast', [
                'title' => __('notebook.deleted'),
                'message' => __('notebook.transaction_deleted'),
                'type' => 'error'
            ]);
        }
    }

    public function addTransaction()
    {
        $this->validate([
            'txAmount' => 'required|numeric|min:0.1',
            'txType' => 'required|in:debt,payment',
            'txDescription' => 'nullable|string|max:255',
            'txDate' => 'required|date',
        ]);

        if (!$this->activeCustomer) return;

        if ($this->editingTxId) {
            if (!$this->isAuthorized('notebook_update')) {
                abort(403, 'Unauthorized action.');
            }
            $tx = StoreTransaction::find($this->editingTxId);
            if ($tx) {
                $tx->update([
                    'type' => $this->txType,
                    'amount' => $this->txAmount,
                    'transaction_date' => $this->txDate,
                    'description' => $this->txDescription ?? ($this->txType === 'debt' ? __('notebook.debt') : __('notebook.payment')),
                ]);
                
                $this->dispatch('toast', [
                    'title' => __('notebook.updated'),
                    'message' => __('notebook.transaction_updated'),
                    'type' => 'info'
                ]);
            }
        } else {
            if (!$this->isAuthorized('notebook_create')) {
                abort(403, 'Unauthorized action.');
            }
            StoreTransaction::create([
                'store_id' => $this->store_id,
                'store_customer_id' => $this->activeCustomer->id,
                'type' => $this->txType,
                'amount' => $this->txAmount,
                'transaction_date' => $this->txDate,
                'description' => $this->txDescription ?? ($this->txType === 'debt' ? __('notebook.debt') : __('notebook.payment')),
            ]);

            $this->dispatch('toast', [
                'title' => __('notebook.registered'),
                'message' => $this->txType === 'debt' ? __('notebook.debt_registered') : __('notebook.payment_registered'),
                'type' => 'success'
            ]);
        }

        $this->activeCustomer->refresh();

        $this->dispatch('close-modal', id: 'transactionModal');
    }

    public function render()
    {
        if (!$this->isAuthorized('notebook_read')) {
            abort(403, 'Unauthorized access to the notebook.');
        }

        $query = StoreCustomer::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->filter === 'debt') {
            $query->where('balance', '>', 0);
        } elseif ($this->filter === 'paid') {
            $query->where('balance', '<=', 0);
        }

        $totalCustomers = $query->count();
        $customers = $query->latest()->take($this->customersPerPage)->get();

        $totalDebt = StoreCustomer::where('balance', '>', 0)->sum('balance');
        $todayCollections = StoreTransaction::where('type', 'payment')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $ledgerTransactions = [];
        $totalLedgerTransactions = 0;
        if ($this->activeCustomer) {
            $ledgerTransactions = $this->activeCustomer->transactions()
                ->latest()
                ->take($this->ledgerPage * $this->perPage)
                ->get();
            $totalLedgerTransactions = $this->activeCustomer->transactions()->count();
        }

        return view('livewire.website.casher.casher-notebook', [
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
            'totalDebt' => $totalDebt,
            'todayCollections' => $todayCollections,
            'ledgerTransactions' => $ledgerTransactions,
            'totalLedgerTransactions' => $totalLedgerTransactions,
        ])->layout('layouts.website.app');
    }
}
