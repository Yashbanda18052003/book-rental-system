<?php

namespace App\Mail;

use App\Models\Fine;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FineGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fine;

    public function __construct(Fine $fine)
    {
        $this->fine = $fine;
    }

    public function build()
    {
        return $this->subject('BookNest - Overdue Fine Notice')
                    ->view('emails.fine-generated');
    }
}