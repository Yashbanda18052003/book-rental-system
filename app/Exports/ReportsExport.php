<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Payment::with('user')
            ->get()
            ->map(function ($payment) {

                return [
                    'ID'           => $payment->id,
                    'User'         => $payment->user->name ?? 'N/A',
                    'Type'         => ucfirst($payment->payment_type),
                    'Amount'       => $payment->amount,
                    'Status'       => ucfirst($payment->status),
                    'Created At'   => $payment->created_at->format('d-m-Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Payment Type',
            'Amount',
            'Status',
            'Created At'
        ];
    }
}