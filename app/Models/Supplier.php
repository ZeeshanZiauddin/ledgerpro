<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'fax',
        'credit_limit',
        'comment',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplier) {
            if (empty($supplier->code)) {
                // Get the latest supplier and extract the numeric part of the code
                $lastsupplier = static::latest('id')->first();
                $lastCode = $lastsupplier?->code ? intval(substr($lastsupplier->code, 1)) : 0;

                // Increment and format the new code
                $supplier->code = 'S' . str_pad($lastCode + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
