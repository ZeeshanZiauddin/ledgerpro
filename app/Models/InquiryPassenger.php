<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id',
        'from_city_id',
        'from_country_id',
        'des_city_id',
        'des_country_id',
        'dep_date',
        'return_date',
        'adults',
        'child',
        'infants',
        'flight_type',
        'airline',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
}
