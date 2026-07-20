<?php

namespace App\Exports;

use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriptionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Subscription::with(['user','plan'])
            ->get()
            ->map(function ($subscription) {
                return [
                    'User' => $subscription->user->name,
                    'Plan' => $subscription->plan->name,
                    'Status' => $subscription->status,
                    'End Date' => $subscription->end_date,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'User',
            'Plan',
            'Status',
            'End Date'
        ];
    }
}