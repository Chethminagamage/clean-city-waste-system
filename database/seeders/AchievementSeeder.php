<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $achievements = [
            // Milestone Achievements
            [
                'name' => 'First Steps',
                'slug' => 'first-report',
                'description' => 'Submit your first waste report',
                'icon' => 'fas fa-baby',
                'category' => 'milestone',
                'points_reward' => 25,
                'requirements' => ['reports_count' => 1],
                'rarity' => 'common'
            ],
            [
                'name' => 'Report Master',
                'slug' => 'report-master',
                'description' => 'Submit 10 waste reports',
                'icon' => 'fas fa-clipboard-check',
                'category' => 'milestone',
                'points_reward' => 100,
                'requirements' => ['reports_count' => 10],
                'rarity' => 'rare'
            ],
            [
                'name' => 'Eco Warrior',
                'slug' => 'eco-warrior',
                'description' => 'Submit 50 waste reports',
                'icon' => 'fas fa-leaf',
                'category' => 'milestone',
                'points_reward' => 500,
                'requirements' => ['reports_count' => 50],
                'rarity' => 'epic'
            ],
            [
                'name' => 'Environmental Legend',
                'slug' => 'environmental-legend',
                'description' => 'Submit 100 waste reports',
                'icon' => 'fas fa-crown',
                'category' => 'milestone',
                'points_reward' => 1000,
                'requirements' => ['reports_count' => 100],
                'rarity' => 'legendary'
            ],

            // Consistency Achievements
            [
                'name' => 'Punctual Reporter',
                'slug' => 'punctual-reporter',
                'description' => 'Submit 3 reports in a week',
                'icon' => 'fas fa-clock',
                'category' => 'consistency',
                'points_reward' => 50,
                'requirements' => ['weekly_reports' => 3],
                'rarity' => 'common',
                'is_repeatable' => true
            ],
            [
                'name' => 'Consistency King',
                'slug' => 'consistency-king',
                'description' => 'Maintain consistent reporting for 30 days',
                'icon' => 'fas fa-calendar-check',
                'category' => 'consistency',
                'points_reward' => 200,
                'requirements' => ['consistent_days' => 30],
                'rarity' => 'rare'
            ],
            [
                'name' => 'Dedicated Environmentalist',
                'slug' => 'dedicated-environmentalist',
                'description' => 'Report waste every day for a week',
                'icon' => 'fas fa-fire',
                'category' => 'consistency',
                'points_reward' => 150,
                'requirements' => ['daily_streak' => 7],
                'rarity' => 'rare'
            ],

            // Community Achievements
            [
                'name' => 'Feedback Champion',
                'slug' => 'feedback-champion',
                'description' => 'Provide 5 pieces of feedback',
                'icon' => 'fas fa-comments',
                'category' => 'community',
                'points_reward' => 75,
                'requirements' => ['feedback_count' => 5],
                'rarity' => 'common'
            ],
            [
                'name' => 'Community Hero',
                'slug' => 'community-hero',
                'description' => 'Report 5 urgent waste issues',
                'icon' => 'fas fa-exclamation-triangle',
                'category' => 'community',
                'points_reward' => 150,
                'requirements' => ['urgent_reports' => 5],
                'rarity' => 'rare'
            ],
            [
                'name' => 'Neighborhood Guardian',
                'slug' => 'neighborhood-guardian',
                'description' => 'Be the top reporter in your area for a month',
                'icon' => 'fas fa-shield-alt',
                'category' => 'community',
                'points_reward' => 300,
                'requirements' => ['monthly_area_rank' => 1],
                'rarity' => 'epic'
            ],

            // Environmental Impact Achievements
            [
                'name' => 'Green Thumb',
                'slug' => 'green-thumb',
                'description' => 'Report organic waste 10 times',
                'icon' => 'fas fa-seedling',
                'category' => 'environmental',
                'points_reward' => 80,
                'requirements' => ['organic_reports' => 10],
                'rarity' => 'common'
            ],
            [
                'name' => 'Recycling Champion',
                'slug' => 'recycling-champion',
                'description' => 'Report recyclable waste 20 times',
                'icon' => 'fas fa-recycle',
                'category' => 'environmental',
                'points_reward' => 120,
                'requirements' => ['recyclable_reports' => 20],
                'rarity' => 'rare'
            ],
            [
                'name' => 'Hazard Hunter',
                'slug' => 'hazard-hunter',
                'description' => 'Report 5 hazardous waste items',
                'icon' => 'fas fa-radiation',
                'category' => 'environmental',
                'points_reward' => 200,
                'requirements' => ['hazardous_reports' => 5],
                'rarity' => 'epic'
            ],

            // Speed Achievements
            [
                'name' => 'Speed Demon',
                'slug' => 'speed-demon',
                'description' => 'Submit a report in under 1 minute',
                'icon' => 'fas fa-bolt',
                'category' => 'speed',
                'points_reward' => 30,
                'requirements' => ['submission_time' => 60],
                'rarity' => 'common'
            ],
            [
                'name' => 'Quick Draw',
                'slug' => 'quick-draw',
                'description' => 'Submit 10 reports in under 2 minutes each',
                'icon' => 'fas fa-stopwatch',
                'category' => 'speed',
                'points_reward' => 100,
                'requirements' => ['quick_reports' => 10],
                'rarity' => 'rare'
            ],

            // Point Achievements
            [
                'name' => 'Point Collector',
                'slug' => 'points-collector',
                'description' => 'Earn 500 total points',
                'icon' => 'fas fa-coins',
                'category' => 'points',
                'points_reward' => 50,
                'requirements' => ['total_points' => 500],
                'rarity' => 'common'
            ],
            [
                'name' => 'Point Master',
                'slug' => 'points-master',
                'description' => 'Earn 2000 total points',
                'icon' => 'fas fa-gem',
                'category' => 'points',
                'points_reward' => 200,
                'requirements' => ['total_points' => 2000],
                'rarity' => 'rare'
            ],
            [
                'name' => 'Level Climber',
                'slug' => 'level-climber',
                'description' => 'Reach level 5',
                'icon' => 'fas fa-mountain',
                'category' => 'progression',
                'points_reward' => 150,
                'requirements' => ['level' => 5],
                'rarity' => 'rare'
            ],
            [
                'name' => 'Elite Status',
                'slug' => 'elite-status',
                'description' => 'Reach level 8',
                'icon' => 'fas fa-star',
                'category' => 'progression',
                'points_reward' => 400,
                'requirements' => ['level' => 8],
                'rarity' => 'epic'
            ]
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
