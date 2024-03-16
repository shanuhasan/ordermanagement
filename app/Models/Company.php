<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    static public function getCompanies()
    {
        return self::orderBy('name', 'ASC')
            ->where('status', '=', '1')
            ->where('is_deleted', '!=', '1')
            ->get();
    }

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }
}
