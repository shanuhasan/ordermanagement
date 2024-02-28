<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    static public function list()
    {
        $companyId = Auth::guard('web')->user()->company_id;
        $model = self::where('company_id',$companyId)->where('status',1)->get();
        return $model;
    }
}
