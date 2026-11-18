<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\StoreCustomerRepository;

class StoreCustomerService
{
    protected $storeCustomerRepository;
    // constructor
    public function __construct(StoreCustomerRepository $storeCustomerRepository)
    {
        $this->storeCustomerRepository = $storeCustomerRepository;
    }

    // get one
    public function getOne($id)
    {
        return $this->storeCustomerRepository->getOne($id);
    }

    // get all
    public function getAll($keyword = null, $store_id = null, $status = null, $sort_by = null, $balance_status = null)
    {
        return $this->storeCustomerRepository->getAll($keyword, $store_id, $status, $sort_by, $balance_status);
    }

    // get metrics
    public function getMetrics($keyword = null, $store_id = null, $status = null, $balance_status = null)
    {
        return $this->storeCustomerRepository->getMetrics($keyword, $store_id, $status, $balance_status);
    }

    // create
    public function create($data)
    {
        $storeCustomer = $this->storeCustomerRepository->create($data);
        if (!$storeCustomer) {
            return false;
        }
        return $storeCustomer;
    }

    // update
    public function update($data)
    {
        $storeCustomer = self::getOne($data['id']);

        if (!$storeCustomer) {
            return false;
        }

        $storeCustomer = $this->storeCustomerRepository->update($storeCustomer, $data);
        if (!$storeCustomer) {
            return false;
        }
        return $storeCustomer;
    }

    // destroy
    public function destroy($id)
    {
        $storeCustomer = $this->getOne($id);

        if (!$storeCustomer) {
            return false;
        }

        if ($storeCustomer->is_walk_in) {
            throw new \App\Exceptions\DeleteRestrictionException(__('store_customers.cannot_delete_walk_in') ?? 'لا يمكن حذف زبون المبيعات المباشرة (الطياري).');
        }

        $storeCustomer->checkRestrictiveRelations();

        $storeCustomer = $this->storeCustomerRepository->destroy($storeCustomer);
        if (!$storeCustomer) {
            return false;
        }
        return $storeCustomer;
    }

    // change status
    public function changeStatus($id, $status)
    {
        $storeCustomer = self::getOne($id);
        if (!$storeCustomer) {
            return false;
        }
        $storeCustomer = $this->storeCustomerRepository->changeStatus($storeCustomer, $status);
        if (!$storeCustomer) {
            return false;
        }
        return $storeCustomer;
    }

    // export customer statement pdf
    public function exportPdf($id)
    {
        $customer = $this->getOne($id);
        if (!$customer) {
            abort(404, __('store_customers.customer_not_found'));
        }

        // Fetch last 200 transactions ordered chronologically
        $recentTransactions = \App\Models\StoreTransaction::where('store_customer_id', $customer->id)
            ->with(['createdBy'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(200)
            ->get()
            ->reverse()
            ->values();

        // Calculate debts and payments for these 200 transactions
        $recentDebts = (float) $recentTransactions->where('type', 'debt')->sum('amount');
        $recentPayments = (float) $recentTransactions->where('type', 'payment')->sum('amount');
        $recentNet = $recentDebts - $recentPayments;

        // Current real balance & Previous balance before these 200 transactions
        $currentBalance = (float) $customer->calculated_balance;
        $previousBalance = $currentBalance - $recentNet;

        // Calculate running balance for each transaction row
        $runningBalance = $previousBalance;
        foreach ($recentTransactions as $tx) {
            if ($tx->type === 'debt') {
                $runningBalance += (float) $tx->amount;
            } else {
                $runningBalance -= (float) $tx->amount;
            }
            $tx->running_balance = $runningBalance;
        }

        // Store branding details
        $store = $customer->store;
        $storeLogo = null;
        if ($store && !empty($store->logo)) {
            if (file_exists(public_path('uploads/stores/' . $store->logo))) {
                $storeLogo = public_path('uploads/stores/' . $store->logo);
            } elseif (file_exists(public_path($store->logo))) {
                $storeLogo = public_path($store->logo);
            }
        }
        if (!$storeLogo && file_exists(public_path('assets/dashbaord/images/pdf-logo.png'))) {
            $storeLogo = public_path('assets/dashbaord/images/pdf-logo.png');
        }

        $data = [
            'customer' => $customer,
            'store' => $store,
            'storeLogo' => $storeLogo,
            'transactions' => $recentTransactions,
            'previousBalance' => $previousBalance,
            'recentDebts' => $recentDebts,
            'recentPayments' => $recentPayments,
            'currentBalance' => $currentBalance,
            'generatedAt' => now(),
        ];

        $pdf = \Mccarlosen\LaravelMpdf\Facades\LaravelMpdf::loadView(
            'dashboard.store_customers.pdf.statement',
            $data,
            [],
            [
                'title' => __('store_customers.statement_title') . ' - ' . $customer->name,
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 8,
                'margin_right' => 8,
                'margin_top' => 14,
                'margin_bottom' => 12,
            ]
        );

        $safeCustomerName = preg_replace('/[^\p{L}\p{N}_\-]+/u', '_', $customer->name);
        $fileName =  $safeCustomerName . '-' . date('Y-m-d') . '.pdf';

        $content = $pdf->output();

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => strlen($content),
        ]);
    }
}
