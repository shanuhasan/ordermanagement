<?php


use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

function employeeExist($id)
{
    $companyId = Auth::guard('web')->user()->company_id;
    $exist = Employee::where('id',$id)
                ->where('company_id',$companyId)
                ->where('is_deleted','!=','1')
                ->first();

    return $exist;
}

function getEmployeeDetail($id)
{
    $employee =  Employee::find($id);
    
    if(empty($employee))
    {
        return 'NA';
    }
    return $employee;
}