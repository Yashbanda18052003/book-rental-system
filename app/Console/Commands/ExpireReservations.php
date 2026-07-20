<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Services\NotificationService;
use App\Mail\ReservationAvailableMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';

    protected $description = 'Expire reservations left unclaimed 48 hours after becoming available, and promote the next person in queue';

    public function handle()
    {
        $expired = Reservation::where('status', 'available')
            ->where('available_at', '<=', Carbon::now()->subHours(48))
            ->get();

        foreach ($expired as $reservation) {
            $reservation->update([
                'status' => 'expired',
            ]);

            $this->promoteNextInQueue($reservation->book_id);
        }

        $this->info($expired->count() . ' reservation(s) expired.');
    }

    private function promoteNextInQueue(int $bookId): void
    {
        $nextReservation = Reservation::where('book_id', $bookId)
            ->where('status', 'waiting')
            ->orderBy('queue_position')
            ->first();

        if (!$nextReservation) {
            return;
        }

        $nextReservation->update([
            'status' => 'available',
            'available_at' => now(),
        ]);

        Mail::to($nextReservation->user->email)
            ->send(new ReservationAvailableMail($nextReservation));

        NotificationService::send(
            $nextReservation->user,
            'Book Available',
            "'{$nextReservation->book->title}' is now available. Please contact admin to assign it to you within 48 hours.",
            'info',
            route('my.reservations')
        );
    }
}