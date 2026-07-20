<?php

namespace App\Exports;

use App\Models\Fine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Fine::with(['user','rental.book'])
            ->get()
            ->map(function ($fine) {
                return [
                    'User' => $fine->user->name,
                    'Book' => $fine->rental->book->title ?? 'N/A',
                    'Late Days' => $fine->late_days,
                    'Fine Amount' => $fine->fine_amount,
                    'Status' => $fine->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'User',
            'Book',
            'Late Days',
            'Fine Amount',
            'Status'
        ];
    }
}