<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationAssignedMail extends Mailable
{
    use SerializesModels;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this
            ->subject('BookNest - Reserved Book Available')
            ->view('emails.reservation-assigned');
    }
}