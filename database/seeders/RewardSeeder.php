<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reward;

class RewardSeeder extends Seeder
{
    public function run()
    {
        $rewards = [
            // Digital Rewards
            [
                'name' => 'Eco Hero Badge',
                'description' => 'A special digital badge to show on your profile',
                'cost_points' => 50,
                'type' => 'badge',
                'reward_data' => [
                    'badge_name' => 'Eco Hero',
                    'badge_icon' => 'fas fa-leaf',
                    'badge_color' => 'green'
                ],
                'quantity' => null,
                'is_active' => true
            ],
            [
                'name' => 'Planet Protector Title',
                'description' => 'Unlock the "Planet Protector" title for your profile',
                'cost_points' => 100,
                'type' => 'badge',
                'reward_data' => [
                    'title' => 'Planet Protector',
                    'title_color' => 'blue'
                ],
                'quantity' => null,
                'is_active' => true
            ],
            [
                'name' => 'Green Warrior Avatar',
                'description' => 'Special avatar frame with green theme',
                'cost_points' => 75,
                'type' => 'badge',
                'reward_data' => [
                    'avatar_frame' => 'green_warrior',
                    'duration_days' => 30
                ],
                'quantity' => null,
                'is_active' => true
            ],

            // Discount Vouchers
            [
                'name' => '10% Off EcoStore',
                'description' => '10% discount at local eco-friendly stores',
                'cost_points' => 150,
                'type' => 'discount',
                'reward_data' => [
                    'discount_percentage' => 10,
                    'store_type' => 'eco_store',
                    'validity_days' => 30
                ],
                'quantity' => 100,
                'is_active' => true
            ],
            [
                'name' => '15% Off Green Products',
                'description' => '15% discount on eco-friendly products',
                'cost_points' => 200,
                'type' => 'discount',
                'reward_data' => [
                    'discount_percentage' => 15,
                    'product_category' => 'green_products',
                    'validity_days' => 45
                ],
                'quantity' => 50,
                'is_active' => true
            ],
            [
                'name' => 'Free Waste Collection',
                'description' => 'One free additional waste collection service',
                'cost_points' => 300,
                'type' => 'service',
                'reward_data' => [
                    'service_type' => 'waste_collection',
                    'quantity' => 1,
                    'validity_days' => 60
                ],
                'quantity' => 20,
                'is_active' => true
            ],

            // Physical Rewards
            [
                'name' => 'Reusable Water Bottle',
                'description' => 'Eco-friendly stainless steel water bottle',
                'cost_points' => 400,
                'type' => 'physical',
                'reward_data' => [
                    'item_type' => 'water_bottle',
                    'material' => 'stainless_steel',
                    'delivery_required' => true
                ],
                'quantity' => 25,
                'is_active' => true
            ],
            [
                'name' => 'Organic Cotton Tote Bag',
                'description' => 'Stylish organic cotton tote bag with CleanCity logo',
                'cost_points' => 250,
                'type' => 'physical',
                'reward_data' => [
                    'item_type' => 'tote_bag',
                    'material' => 'organic_cotton',
                    'delivery_required' => true
                ],
                'quantity' => 50,
                'is_active' => true
            ],
            [
                'name' => 'Bamboo Cutlery Set',
                'description' => 'Portable bamboo cutlery set with carrying case',
                'cost_points' => 350,
                'type' => 'physical',
                'reward_data' => [
                    'item_type' => 'cutlery_set',
                    'material' => 'bamboo',
                    'includes' => ['fork', 'knife', 'spoon', 'chopsticks', 'case'],
                    'delivery_required' => true
                ],
                'quantity' => 30,
                'is_active' => true
            ],

            // Experience Rewards
            [
                'name' => 'Eco Workshop Ticket',
                'description' => 'Free ticket to environmental awareness workshop',
                'cost_points' => 200,
                'type' => 'experience',
                'reward_data' => [
                    'event_type' => 'workshop',
                    'topic' => 'environmental_awareness',
                    'duration_hours' => 3,
                    'validity_days' => 90
                ],
                'quantity' => 15,
                'is_active' => true
            ],
            [
                'name' => 'Community Garden Tour',
                'description' => 'Guided tour of local community gardens and composting facilities',
                'cost_points' => 180,
                'type' => 'experience',
                'reward_data' => [
                    'event_type' => 'tour',
                    'location' => 'community_garden',
                    'duration_hours' => 2,
                    'validity_days' => 60
                ],
                'quantity' => 20,
                'is_active' => true
            ],

            // Premium Features
            [
                'name' => 'Priority Support',
                'description' => 'Get priority customer support for 30 days',
                'cost_points' => 120,
                'type' => 'service',
                'reward_data' => [
                    'service_type' => 'priority_support',
                    'duration_days' => 30
                ],
                'quantity' => null,
                'is_active' => true
            ],
            [
                'name' => 'Advanced Analytics',
                'description' => 'Access to detailed waste analytics for 60 days',
                'cost_points' => 180,
                'type' => 'feature',
                'reward_data' => [
                    'feature_type' => 'advanced_analytics',
                    'duration_days' => 60
                ],
                'quantity' => null,
                'is_active' => true
            ],

            // Charity Rewards
            [
                'name' => 'Tree Planting Donation',
                'description' => 'Plant one tree in your name through our partner organizations',
                'cost_points' => 100,
                'type' => 'charity',
                'reward_data' => [
                    'charity_type' => 'tree_planting',
                    'quantity' => 1,
                    'partner' => 'Green Earth Foundation'
                ],
                'quantity' => null,
                'is_active' => true
            ],
            [
                'name' => 'Ocean Cleanup Support',
                'description' => 'Support ocean cleanup efforts with your points',
                'cost_points' => 150,
                'type' => 'charity',
                'reward_data' => [
                    'charity_type' => 'ocean_cleanup',
                    'impact' => 'Remove 1kg of ocean plastic',
                    'partner' => 'Ocean Heroes Initiative'
                ],
                'quantity' => null,
                'is_active' => true
            ]
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
