<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    static public function getEmployee()
    {
        return self::where('status',1)->where('is_deleted',0)->orderBy('name','ASC')->get();
    }
}
