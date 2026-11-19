<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedItem extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        // Keep the per-request receivedItems() cache in sync.
        static::saved(fn () => flushReceivedItemsCache());
        static::deleted(fn () => flushReceivedItemsCache());
    }

    static public function findByIdAndCompanyId($id, $companyId)
    {
        return self::where('id', $id)->where('company_id', $companyId)->first();
    }
}
