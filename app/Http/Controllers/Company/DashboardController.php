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
                                    ->sum('qty');

        $totalPending = Order::where('company_id',$companyId)
                            ->sum('qty');

        $totalAmount = Order::where('company_id',$companyId)
                            ->sum('total_amount');

        $paidAmount = OrderItem::where('company_id',$companyId)
                                ->sum('amount');

        return view('dashboard',[
            'totalEmployee'=>$totalEmployee,
            'totalComplete'=>$totalComplete,
            'totalPending'=>$totalPending - $totalComplete,
            'totalAmount'=>$totalAmount,
            'paidAmount'=>$paidAmount,
        ]);
    }
}
