<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'fine_per_day',
        'rental_days',
        'contact_email',
        'contact_phone'
    ];
}