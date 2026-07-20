<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'start_date',
        'end_date',
        'returned_at',
        'amount',
        'fine',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function fineRecord()
    {
        return $this->hasOne(Fine::class);
    }
}