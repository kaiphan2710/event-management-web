<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder orchestrates all seeders to populate the database with test data
 * Creates the minimum required data: 2 Organisers, 10 Attendees, 15 Events
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EventSeeder::class,
            BookingSeeder::class,
            WaitlistSeeder::class,
        ]);
    }
}
