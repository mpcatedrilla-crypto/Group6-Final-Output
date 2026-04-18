<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        // Create 10 additional users (11 users total)
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Maria', 'William', 'Emma', 'Daniel', 'Olivia', 'Matthew', 'Sophia', 'Joseph', 'Isabella', 'Andrew', 'Ava', 'Christopher', 'Mia', 'Joshua', 'Charlotte', 'Nicholas', 'Amelia', 'Ryan', 'Harper', 'Tyler', 'Evelyn', 'Brandon', 'Abigail', 'Jacob', 'Elizabeth', 'Kevin', 'Sofia', 'Eric', 'Avery', 'Brian', 'Ella'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores', 'Green', 'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 'Mitchell', 'Carter', 'Roberts'];

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $firstNames[$i] . ' ' . $lastNames[$i],
                'email' => strtolower($firstNames[$i]) . '.' . strtolower($lastNames[$i]) . '@example.com',
                'password' => Hash::make('password123')
            ]);
        }

        // Create 100 events (exceeding 1,000 records requirement with participants)
        $eventTitles = [
            'Tech Conference', 'Project Meeting', 'Team Building', 'Client Presentation', 'Workshop',
            'Training Session', 'Quarterly Review', 'Product Launch', 'Annual Gala', 'Networking Event',
            'Board Meeting', 'Strategy Session', 'Code Review', 'Sprint Planning', 'Retrospective',
            'Design Review', 'User Testing', 'Focus Group', 'Market Research', 'Sales Meeting',
            'Marketing Campaign', 'Product Demo', 'Investor Pitch', 'Team Lunch', 'Holiday Party',
            'New Year Kickoff', 'Summer Outing', 'Charity Event', 'Volunteer Day', 'Award Ceremony',
            'Department Meeting', 'All-Hands Meeting', 'Town Hall', 'Fireside Chat', 'AMA Session',
            'Hackathon', 'Innovation Day', 'Brainstorming', 'Ideation Workshop', 'Design Sprint',
            'API Review', 'Security Audit', 'Performance Review', 'Goal Setting', 'Career Development',
            'Mentorship Program', 'Onboarding Session', 'Exit Interview', 'Feedback Session', 'Celebration',
            'Budget Review', 'Forecasting Meeting', 'Risk Assessment', 'Compliance Training', 'Policy Update',
            'IT Support Session', 'System Demo', 'Database Migration', 'Cloud Migration', 'DevOps Training',
            'Agile Workshop', 'Scrum Master Training', 'Leadership Training', 'Communication Skills', 'Time Management',
            'Conflict Resolution', 'Negotiation Skills', 'Public Speaking', 'Presentation Skills', 'Writing Workshop',
            'Data Analysis', 'SQL Training', 'Python Workshop', 'JavaScript Basics', 'React Training',
            'Laravel Advanced', 'Docker Workshop', 'Kubernetes Intro', 'CI/CD Pipeline', 'Testing Strategies',
            'Mobile Development', 'iOS Workshop', 'Android Basics', 'Flutter Intro', 'React Native',
            'UX Design', 'UI Design', 'Figma Workshop', 'Prototyping', 'User Research', 'Accessibility',
            'SEO Workshop', 'Content Strategy', 'Social Media', 'Email Marketing', 'Analytics Review'
        ];

        $descriptions = [
            'An engaging session for all participants',
            'Important updates and announcements',
            'Collaborative discussion and planning',
            'Review of current progress and goals',
            'Interactive workshop with hands-on activities',
            'Training session for skill development',
            'Quarterly business review and planning',
            'Exciting product showcase event',
            'Celebration and recognition of achievements',
            'Networking opportunity with industry professionals'
        ];

        $eventIds = [];
        for ($i = 0; $i < 100; $i++) {
            $title = $eventTitles[$i % count($eventTitles)] . ' ' . (2024 + floor($i / 50)) . '-' . str_pad(($i % 50) + 1, 2, '0', STR_PAD_LEFT);
            $event = Event::create([
                'title' => $title,
                'description' => $descriptions[$i % count($descriptions)],
                'event_date' => date('Y-m-d', strtotime('+' . $i . ' days', strtotime('2024-01-01')))
            ]);
            $eventIds[] = $event->id;
        }

        // Create 10 participants per event = 1,000 participants
        $participantCount = 0;
        foreach ($eventIds as $eventId) {
            for ($j = 0; $j < 10; $j++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                Participant::create([
                    'name' => $firstName . ' ' . $lastName,
                    'email' => strtolower($firstName) . '.' . strtolower($lastName) . $participantCount . '@example.com',
                    'event_id' => $eventId
                ]);
                $participantCount++;
            }
        }

        // Summary: 11 users + 100 events + 1,000 participants = 1,111 records
        echo "Seeded: 11 users, 100 events, " . $participantCount . " participants (Total: " . (11 + 100 + $participantCount) . " records)\n";
    }
}
