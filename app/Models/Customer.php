<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'fax',
        'comment',
        'credit_limit',
        'status',
    ];

    /**
     * Boot method to handle auto-incrementing 'code'.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->code)) {
                // Get the latest customer and extract the numeric part of the code
                $lastCustomer = static::latest('id')->first();
                $lastCode = $lastCustomer?->code ? intval(substr($lastCustomer->code, 1)) : 0;

                // Increment and format the new code
                $customer->code = 'S' . str_pad($lastCode + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
