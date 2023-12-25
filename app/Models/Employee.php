<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    static public function getEmployee()
    {
        $companyId = Auth::guard('web')->user()->company_id;

        return self::where('status',1)
                        ->where('company_id',$companyId)
                        ->where('is_deleted','!=',1)
                        ->orderBy('name','ASC')
                        ->get();
    }

    static public function getSingleEmployee($id)
    {
        $companyId = Auth::guard('web')->user()->company_id;

        return self::where('status',1)
                        ->where('id',$id)
                        ->where('company_id',$companyId)
                        ->where('is_deleted','!=',1)
                        ->orderBy('name','ASC')
                        ->first();
    }
}
