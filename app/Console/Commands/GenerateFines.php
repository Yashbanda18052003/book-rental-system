<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Rental;
use App\Models\Fine;
class GenerateFines extends Command
{
    protected $signature = 'app:generate-fines';
    protected $description = 'Generate fines for overdue rentals';
    public function handle()
    {
        $rentals = Rental::with([
            'user',
            'book'
        ])
        ->where(
            'status',
            'active'
        )
        ->whereDate(
            'end_date',
            '<',
            now()->subDays(2)
        )
        ->get();
        foreach ($rentals as $rental) {
            $lateDays = now()->diffInDays(
                $rental->end_date
            );
            $rental->update([
                'status' => 'overdue'
            ]);
            $fine = Fine::firstOrCreate(
                [
                    'rental_id' => $rental->id
                ],
                [
                    'user_id' => $rental->user_id,
                    'late_days' => $lateDays,
                    'fine_amount' => $lateDays * 10,
                    'status' => 'pending'
                ]
            );
        }
        $this->info('Fines generated successfully.');
    }
}