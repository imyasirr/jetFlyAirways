<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class SupportContentSeeder extends Seeder
{
    public function run(): void
    {
        if (! Faq::query()->exists()) {
            Faq::create([
                'question' => 'How do I change or cancel a booking?',
                'answer' => 'Open My account → Bookings, or contact support with your booking code. Refunds follow our refund policy.',
                'is_active' => true,
            ]);
            Faq::create([
                'question' => 'Which payment methods are supported?',
                'answer' => 'When Razorpay is configured, you can pay with UPI, cards and netbanking. Otherwise complete the booking and our team will assist with payment.',
                'is_active' => true,
            ]);
        }

        if (! Testimonial::query()->exists()) {
            Testimonial::create([
                'name' => 'Priya S.',
                'designation' => 'Frequent flyer',
                'review' => 'Smooth booking and clear fares. Support was quick when I had a date change.',
                'rating' => 5,
                'is_active' => true,
            ]);
            Testimonial::create([
                'name' => 'Rahul M.',
                'designation' => 'Family traveller',
                'review' => 'Liked having flights, hotels and cabs in one place. Will book packages again.',
                'rating' => 5,
                'is_active' => true,
            ]);
        }

        if (! Career::query()->exists()) {
            Career::create([
                'job_title' => 'Customer Support Executive',
                'department' => 'Operations',
                'location' => 'Remote / Mumbai',
                'salary' => 'As per experience',
                'openings' => 2,
                'job_description' => "Assist travellers with bookings, changes and escalations via phone and email.\nWeekend shifts may apply.",
                'required_skills' => "Excellent communication in English and Hindi.\nPrior travel or BPO experience preferred.",
                'apply_last_date' => now()->addMonths(3)->toDateString(),
                'is_hiring' => true,
            ]);
        }
    }
}
