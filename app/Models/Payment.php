<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
protected $fillable = [
    'rental_id',
    'subscription_id',
    'fine_id',
    'user_id',
    'payment_type',
    'stripe_payment_id',
    'amount',
    'status'
];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
{
    return $this->belongsTo(Subscription::class);
}

public function fine()
{
    return $this->belongsTo(Fine::class);
}
}