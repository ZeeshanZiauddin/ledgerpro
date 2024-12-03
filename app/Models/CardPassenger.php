<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'name',
        'ticket_1',
        'ticket_2',
        'issue_date',
        'option_date',
        'pnr'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }


}
