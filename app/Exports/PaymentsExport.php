<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Payment::with('user')
            ->get()
            ->map(function ($payment) {

                return [
                    'ID' => $payment->id,
                    'User' => $payment->user->name ?? 'N/A',
                    'Type' => $payment->payment_type,
                    'Amount' => $payment->amount,
                    'Status' => $payment->status,
                    'Stripe ID' => $payment->stripe_payment_id,
                    'Date' => $payment->created_at,
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
            'Stripe Payment ID',
            'Date'
        ];
    }
}