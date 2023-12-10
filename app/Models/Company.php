<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public static function getCompanies(){
        return self::orderBy('name','ASC')
                        ->where('status','=','1')
                        ->get();
    }
}
