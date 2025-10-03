<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder creates test users including organisers and attendees
 * Creates 2 organisers and 10 attendees as required by the assignment
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 2 Organisers
        $organisers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'organiser',
                'phone' => '+61 412 345 678',
                'address' => '123 Main Street, Brisbane, QLD 4000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'organiser',
                'phone' => '+61 423 456 789',
                'address' => '456 Queen Street, Melbourne, VIC 3000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
        ];

        foreach ($organisers as $organiser) {
            User::create($organiser);
        }

        // Create 10 Attendees
        $attendees = [
            [
                'name' => 'Emma Wilson',
                'email' => 'emma.wilson@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 434 567 890',
                'address' => '789 Collins Street, Sydney, NSW 2000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 445 678 901',
                'address' => '321 Flinders Street, Adelaide, SA 5000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 456 789 012',
                'address' => '654 Hay Street, Perth, WA 6000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'James Taylor',
                'email' => 'james.taylor@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 467 890 123',
                'address' => '987 George Street, Hobart, TAS 7000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 478 901 234',
                'address' => '147 Smith Street, Darwin, NT 0800',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'Robert Lee',
                'email' => 'robert.lee@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 489 012 345',
                'address' => '258 Bourke Street, Canberra, ACT 2600',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'Jennifer Davis',
                'email' => 'jennifer.davis@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 490 123 456',
                'address' => '369 Elizabeth Street, Melbourne, VIC 3000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'Christopher Miller',
                'email' => 'christopher.miller@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 401 234 567',
                'address' => '741 Pitt Street, Sydney, NSW 2000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'Amanda White',
                'email' => 'amanda.white@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 412 345 678',
                'address' => '852 King Street, Brisbane, QLD 4000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
            [
                'name' => 'Thomas Clark',
                'email' => 'thomas.clark@example.com',
                'password' => Hash::make('password123'),
                'user_type' => 'attendee',
                'phone' => '+61 423 456 789',
                'address' => '963 Murray Street, Perth, WA 6000',
                'privacy_policy_agreed' => true,
                'terms_agreed' => true,
                'agreed_at' => now(),
            ],
        ];

        foreach ($attendees as $attendee) {
            User::create($attendee);
        }
    }
}
