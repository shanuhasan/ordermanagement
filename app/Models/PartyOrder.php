<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyOrder extends Model
{
    use HasFactory;

    static public function findByIdAndCompanyId($id, $companyId)
    {
        return self::where('id', $id)->where('company_id', $companyId)->first();
    }
}
