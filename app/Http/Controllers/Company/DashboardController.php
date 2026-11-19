<?php

namespace App\Http\Controllers\Company;

use App\Models\Order;
use App\Models\Employee;
use App\Models\OrderItem;
use App\Models\ReceivedItem;

class DashboardController extends AppController
{
    public function index()
    {
        $year = date('Y');

        $totalEmployee = Employee::where('company_id', $this->companyId)
            ->where('is_deleted', '!=', '1')
            ->count();

        // One query for both order totals (was two).
        $orderTotals = Order::where('company_id', $this->companyId)
            ->whereYear('created_at', $year)
            ->selectRaw('COALESCE(SUM(qty), 0) as pcs, COALESCE(SUM(total_amount), 0) as amount')
            ->first();

        $totalComplete = ReceivedItem::where('company_id', $this->companyId)
            ->whereYear('created_at', $year)
            ->sum('qty');

        $paidAmount = OrderItem::where('company_id', $this->companyId)
            ->whereYear('created_at', $year)
            ->sum('amount');

        $totalPcs = (int) $orderTotals->pcs;
        $totalAmount = (float) $orderTotals->amount;

        return view('dashboard', [
            'year' => $year,
            'totalEmployee' => $totalEmployee,
            'totalPcs' => $totalPcs,
            'totalComplete' => $totalComplete,
            'totalPending' => $totalPcs - $totalComplete,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
        ]);
    }
}
