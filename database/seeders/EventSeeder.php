<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * EventSeeder creates 15 test events distributed between the two organisers
 * Creates a variety of events with different capacities and dates
 */
class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organisers = User::where('user_type', 'organiser')->get();

        $events = [
            // Events by Sarah Johnson (organiser 1)
            [
                'title' => 'Tech Innovation Summit 2024',
                'description' => 'Join us for a comprehensive look at the latest technological innovations shaping our future. Featuring keynote speakers, panel discussions, and networking opportunities.',
                'date' => Carbon::now()->addDays(15),
                'time' => '09:00:00',
                'location' => 'Brisbane Convention Centre, South Brisbane',
                'capacity' => 200,
                'organiser_id' => $organisers[0]->id,
            ],
            [
                'title' => 'Digital Marketing Workshop',
                'description' => 'Learn the fundamentals of digital marketing including SEO, social media strategy, and content creation. Perfect for beginners and intermediate marketers.',
                'date' => Carbon::now()->addDays(22),
                'time' => '13:00:00',
                'location' => 'Griffith University, Nathan Campus',
                'capacity' => 50,
                'organiser_id' => $organisers[0]->id,
            ],
            [
                'title' => 'Startup Pitch Competition',
                'description' => 'Watch innovative startups pitch their ideas to a panel of investors. Network with entrepreneurs and discover the next big thing.',
                'date' => Carbon::now()->addDays(30),
                'time' => '18:00:00',
                'location' => 'The Precinct, Fortitude Valley',
                'capacity' => 100,
                'organiser_id' => $organisers[0]->id,
            ],
            [
                'title' => 'AI & Machine Learning Conference',
                'description' => 'Explore the latest developments in artificial intelligence and machine learning. Featuring industry experts and hands-on workshops.',
                'date' => Carbon::now()->addDays(45),
                'time' => '08:30:00',
                'location' => 'Queensland University of Technology, Gardens Point',
                'capacity' => 150,
                'organiser_id' => $organisers[0]->id,
            ],
            [
                'title' => 'Cybersecurity Awareness Seminar',
                'description' => 'Learn how to protect yourself and your business from cyber threats. Essential knowledge for the digital age.',
                'date' => Carbon::now()->addDays(60),
                'time' => '14:00:00',
                'location' => 'Brisbane City Hall, Brisbane',
                'capacity' => 80,
                'organiser_id' => $organisers[0]->id,
            ],
            [
                'title' => 'Data Analytics Bootcamp',
                'description' => 'Intensive 3-day bootcamp covering data analysis, visualization, and interpretation. Bring your laptop for hands-on exercises.',
                'date' => Carbon::now()->addDays(75),
                'time' => '09:00:00',
                'location' => 'Coder Academy, Fortitude Valley',
                'capacity' => 25,
                'organiser_id' => $organisers[0]->id,
            ],
            [
                'title' => 'Mobile App Development Workshop',
                'description' => 'Learn to build mobile applications for iOS and Android. No prior experience required.',
                'date' => Carbon::now()->addDays(90),
                'time' => '10:00:00',
                'location' => 'General Assembly, Brisbane',
                'capacity' => 30,
                'organiser_id' => $organisers[0]->id,
            ],

            // Events by Michael Chen (organiser 2)
            [
                'title' => 'Melbourne Food & Wine Festival',
                'description' => 'Celebrate the best of Melbourne\'s culinary scene with tastings, cooking demonstrations, and chef meet-and-greets.',
                'date' => Carbon::now()->addDays(12),
                'time' => '11:00:00',
                'location' => 'Royal Exhibition Building, Carlton',
                'capacity' => 300,
                'organiser_id' => $organisers[1]->id,
            ],
            [
                'title' => 'Photography Masterclass',
                'description' => 'Advanced photography techniques for portrait, landscape, and street photography. Bring your camera and creativity.',
                'date' => Carbon::now()->addDays(25),
                'time' => '15:00:00',
                'location' => 'National Gallery of Victoria, Melbourne',
                'capacity' => 40,
                'organiser_id' => $organisers[1]->id,
            ],
            [
                'title' => 'Sustainable Living Expo',
                'description' => 'Discover eco-friendly products and practices for sustainable living. Featuring local vendors and expert speakers.',
                'date' => Carbon::now()->addDays(35),
                'time' => '09:30:00',
                'location' => 'Melbourne Convention Centre, South Wharf',
                'capacity' => 120,
                'organiser_id' => $organisers[1]->id,
            ],
            [
                'title' => 'Fitness & Wellness Retreat',
                'description' => 'Weekend retreat focusing on physical fitness, mental wellness, and healthy lifestyle habits. All fitness levels welcome.',
                'date' => Carbon::now()->addDays(50),
                'time' => '07:00:00',
                'location' => 'Mornington Peninsula, Victoria',
                'capacity' => 60,
                'organiser_id' => $organisers[1]->id,
            ],
            [
                'title' => 'Music Production Workshop',
                'description' => 'Learn music production techniques using industry-standard software. Perfect for aspiring producers and musicians.',
                'date' => Carbon::now()->addDays(65),
                'time' => '19:00:00',
                'location' => 'SAE Institute, Melbourne',
                'capacity' => 20,
                'organiser_id' => $organisers[1]->id,
            ],
            [
                'title' => 'Art & Design Exhibition Opening',
                'description' => 'Opening night of contemporary art and design exhibition featuring local and international artists.',
                'date' => Carbon::now()->addDays(80),
                'time' => '18:30:00',
                'location' => 'Australian Centre for Contemporary Art, Southbank',
                'capacity' => 200,
                'organiser_id' => $organisers[1]->id,
            ],
            [
                'title' => 'Entrepreneurship Networking Event',
                'description' => 'Connect with fellow entrepreneurs, investors, and business mentors. Share ideas and build valuable relationships.',
                'date' => Carbon::now()->addDays(95),
                'time' => '17:30:00',
                'location' => 'The Commons, Collingwood',
                'capacity' => 75,
                'organiser_id' => $organisers[1]->id,
            ],
            [
                'title' => 'Language Learning Meetup',
                'description' => 'Practice your language skills in a friendly, supportive environment. Multiple languages supported.',
                'date' => Carbon::now()->addDays(110),
                'time' => '18:00:00',
                'location' => 'State Library Victoria, Melbourne',
                'capacity' => 50,
                'organiser_id' => $organisers[1]->id,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
