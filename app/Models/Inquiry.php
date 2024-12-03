<?php

namespace App\Models;

use App\Filament\Resources\InquiryResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_name',
        'user_id',
        'date',
        'year',
        'owner_firstname',
        'owner_lastname',
        'status',
        'contact_name',
        'contact_email',
        'contact_mobile',
        'contact_home_number',
        'contact_address',
        'price_option',
        'query_owner',
        'option_date',
        'card_no',
        'pnr',
        'filter_point',
    ];

    public function passengers()
    {
        return $this->hasMany(InquiryPassenger::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class); // Defines a one-to-many relationship with Card
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($inquiry) {
            if (empty($inquiry->inquiry_name)) {
                $inquiry->inquiry_name = InquiryResource::generateInquiryName();
            }
            if (empty($inquiry->user_id)) {
                $inquiry->user_id = auth()->id();
            }
            if (empty($inquiry->date)) {
                $inquiry->date = Carbon::now();
            }
            if (empty($inquiry->year)) {
                $inquiry->year = Carbon::now()->format('Y');
            }

            if (empty($inquiry->owner_firstname)) {
                $inquiry->owner_firstname = auth()->user()->name;
            }
            if (empty($inquiry->owner_lastname)) {
                $inquiry->owner_lastname = auth()->user()->name;
            }
            if (empty($inquiry->query_owner)) {
                $inquiry->query_owner = auth()->id();
            }
        });
    }
}
