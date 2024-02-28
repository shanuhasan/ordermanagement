<?php


use App\Models\Item;
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

function years()
{
    $list = [
        '2024'=>'2024',
        '2025'=>'2025',
        '2026'=>'2026',
        '2027'=>'2027',
        '2028'=>'2028',
        '2029'=>'2029',
        '2030'=>'2030',
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

function getItemName($id)
{
    $item =  Item::find($id);
    
    if(empty($item))
    {
        return '';
    }
    return $item->name;
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

function GUIDv4 ($trim = true)
{
    // Windows
    if (function_exists('com_create_guid') === true) {
        if ($trim === true)
            return trim(com_create_guid(), '{}');
        else
            return com_create_guid();
    }

    // OSX/Linux
    if (function_exists('openssl_random_pseudo_bytes') === true) {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // Fallback (PHP 4.2+)
    mt_srand((double)microtime() * 10000);
    $charid = strtolower(md5(uniqid(rand(), true)));
    $hyphen = chr(45);                  // "-"
    $lbrace = $trim ? "" : chr(123);    // "{"
    $rbrace = $trim ? "" : chr(125);    // "}"
    $guidv4 = $lbrace.
              substr($charid,  0,  8).$hyphen.
              substr($charid,  8,  4).$hyphen.
              substr($charid, 12,  4).$hyphen.
              substr($charid, 16,  4).$hyphen.
              substr($charid, 20, 12).
              $rbrace;
    return $guidv4;
}