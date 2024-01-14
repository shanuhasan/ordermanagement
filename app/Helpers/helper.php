<?php


use App\Models\Size;
use App\Models\Order;
use App\Models\Company;
use App\Models\Employee;
use App\Models\ReceivedItem;
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

function itemStatus()
{
    $list = [
        'Pending'=>'Pending',
        'Completed'=>'Completed',
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

function paymentMethod()
{
    $list = [
        'Cash'=>'Cash',
        'Online'=>'Online',
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

function sizeName($id)
{
    $size =  Size::find($id);
    
    if(empty($size))
    {
        return '';
    }
    return $size->name;
}


function receivedItems($orderId)
{
    $items =  ReceivedItem::where('order_id',$orderId)->sum('qty');
    
    if(empty($items))
    {
        return 0;
    }
    return $items;
}

function getOrder($id)
{
    $order =  Order::find($id);
    
    if(empty($order))
    {
        return [];
    }
    return $order;
}