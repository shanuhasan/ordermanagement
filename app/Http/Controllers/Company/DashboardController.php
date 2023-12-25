<?php

namespace App\Http\Controllers\Company;

use App\Models\Order;
use App\Models\Employee;
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

        $totalComplete = Order::where('status','=','1')
                            ->where('company_id',$companyId)
                            ->count();
        $totalPending = Order::where('status','!=','1')
                            ->where('company_id',$companyId)
                            ->count();

        return view('dashboard',[
            'totalEmployee'=>$totalEmployee,
            'totalComplete'=>$totalComplete,
            'totalPending'=>$totalPending,
        ]);
    }
}
