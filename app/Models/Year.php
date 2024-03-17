<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    static public function getYear()
    {
        return  self::get();
    }

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }
}
