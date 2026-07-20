<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration',
        'rental_limit',
        'description',
        'status'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}