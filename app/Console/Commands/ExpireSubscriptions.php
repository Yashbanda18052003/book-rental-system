<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Expire subscriptions automatically';

    public function handle()
    {
        Subscription::where('status', 'active')
            ->whereDate('end_date', '<', Carbon::today())
            ->update([
                'status' => 'expired'
            ]);

        $this->info('Subscriptions expired successfully.');
    }
}