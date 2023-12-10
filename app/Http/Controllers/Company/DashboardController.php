<?php

namespace App\Http\Controllers\Company;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployee = Employee::where('is_deleted','!=','1')->count();
        return view('dashboard',[
            'totalEmployee'=>$totalEmployee
        ]);
    }
}
