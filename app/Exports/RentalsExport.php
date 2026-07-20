<?php

namespace App\Exports;

use App\Models\Rental;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RentalsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Rental::with(['user','book'])
            ->get()
            ->map(function ($rental) {
                return [
                    'User' => $rental->user->name,
                    'Book' => $rental->book->title,
                    'Start Date' => $rental->start_date,
                    'End Date' => $rental->end_date,
                    'Amount' => $rental->amount,
                    'Fine' => $rental->fine,
                    'Status' => $rental->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'User',
            'Book',
            'Start Date',
            'End Date',
            'Amount',
            'Fine',
            'Status'
        ];
    }
}