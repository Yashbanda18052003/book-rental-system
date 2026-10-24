<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin@123'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'demo@booknest.com'],
            [
                'name' => 'Demo Reader',
                'password' => bcrypt('demo12345'),
                'role' => 'user'
            ]
        );

        \App\Models\Setting::firstOrCreate([], [
            'fine_per_day' => 10,
            'rental_days' => 7,
            'contact_email' => null,
            'contact_phone' => null,
        ]);

        \App\Models\MembershipPlan::firstOrCreate(
            ['name' => 'Silver Reader'],
            [
                'price' => 29,
                'duration' => 'monthly',
                'rental_limit' => 3,
                'description' => 'Perfect for casual readers. Rent up to 3 books simultaneously with standard return window.',
                'status' => true,
            ]
        );

        \App\Models\MembershipPlan::firstOrCreate(
            ['name' => 'Gold Scholar'],
            [
                'price' => 69,
                'duration' => 'monthly',
                'rental_limit' => 7,
                'description' => 'Ideal for avid bookworms and students. Rent up to 7 books with flexible returns.',
                'status' => true,
            ]
        );

        \App\Models\MembershipPlan::firstOrCreate(
            ['name' => 'Platinum Bibliophile'],
            [
                'price' => 199,
                'duration' => 'annual',
                'rental_limit' => 15,
                'description' => 'VIP tier with priority reservation queuing, up to 15 concurrent rentals, and zero late-fee grace periods.',
                'status' => true,
            ]
        );
    }
}
