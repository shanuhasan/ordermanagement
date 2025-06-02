<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }

    static public function findByGuidAndCompanyId($guid, $companyId)
    {
        return self::where('guid', $guid)->where('company_id', $companyId)->first();
    }
}
