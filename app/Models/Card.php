<?php
namespace App\Models;

use App\Filament\Resources\CardResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_name',
        'user_id',
        'customer',
        'supplier',
        'inquiry_id',
        'contact_name',
        'contact_email',
        'contact_mobile',
        'contact_home_number',
        'contact_other_number',
        'contact_address',
        'sales_price',
        'net_cost',
        'tax',
        'margin'
    ];

    public function passengers()
    {
        return $this->hasMany(CardPassenger::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class); // Defines an optional relationship to Inquiry
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class); // Defines an optional relationship to Inquiry
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class); // Defines an optional relationship to Inquiry
    }


    protected static function booted()
    {
        parent::booted();

        static::creating(function ($card) {
            if (empty($card->card_name)) {
                $card->card_name = CardResource::generateCardName();
            }
            if (empty($inquiry->user_id)) {
                $card->user_id = auth()->id();
            }
        });
    }

}
