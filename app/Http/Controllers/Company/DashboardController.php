<?php

namespace App\Http\Controllers\Company;

use App\Models\Order;
use App\Models\Employee;
use App\Models\OrderItem;
use App\Models\ReceivedItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = Auth::guard('web')->user()->company_id;
        $totalEmployee = Employee::where('is_deleted','!=','1')
                            ->where('company_id',$companyId)
                            ->count();

        // $totalComplete = Order::where('status','=','Completed')
        //                         ->where('company_id',$companyId)
        //                         ->sum('qty');

        $totalComplete = ReceivedItem::where('company_id',$companyId)
                                    ->whereYear('created_at', date('Y'))
                                    ->sum('qty');

        $totalPcs = Order::where('company_id',$companyId)
                                    ->whereYear('created_at', date('Y'))
                                    ->sum('qty');

        $totalAmount = Order::where('company_id',$companyId)
                            ->whereYear('created_at', date('Y'))
                            ->sum('total_amount');

        $paidAmount = OrderItem::where('company_id',$companyId)
                                ->whereYear('created_at', date('Y'))
                                ->sum('amount');

        return view('dashboard',[
            'totalEmployee'=>$totalEmployee,
            'totalPcs'=>$totalPcs,
            'totalComplete'=>$totalComplete,
            'totalPending'=>$totalPcs - $totalComplete,
            'totalAmount'=>$totalAmount,
            'paidAmount'=>$paidAmount,
        ]);
    }
}
