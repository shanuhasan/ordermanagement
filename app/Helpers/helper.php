<?php


use App\Models\Company;
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

function getStatus()
{
    $list = [
        '0'=>'Pending',
        '1'=>'Complete',
    ];
    return $list;
}
function getSize()
{
    $list = [
        'S'=>'S',
        'M'=>'M',
        'L'=>'L',
        'XL'=>'XL',
        'XXL'=>'XXL',
    ];
    return $list;
}

function getCompany()
{
    $company =  Company::where('status',1)->get();
    
    if(empty($company))
    {
        return '';
    }
    return $company;
}

function companyName($id)
{
    $company =  Company::find($id);
    
    if(empty($company))
    {
        return '';
    }
    return $company->name;
}