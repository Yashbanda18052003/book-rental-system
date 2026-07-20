<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'rental_price',
        'stock',
        'image',
        'status'
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}