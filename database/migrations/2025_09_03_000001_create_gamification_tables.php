<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // User Points and Levels
        Schema::create('user_gamification', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_points')->default(0);
            $table->integer('current_level')->default(1);
            $table->integer('points_to_next_level')->default(100);
            $table->string('current_rank')->default('Eco Newbie');
            $table->integer('weekly_points')->default(0);
            $table->integer('monthly_points')->default(0);
            $table->date('last_weekly_reset')->nullable();
            $table->date('last_monthly_reset')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
        });

        // Achievements/Badges
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon');
            $table->string('category'); // environmental, consistency, community, milestone
            $table->integer('points_reward');
            $table->json('requirements'); // flexible requirements structure
            $table->boolean('is_repeatable')->default(false);
            $table->string('rarity')->default('common'); // common, rare, epic, legendary
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User Achievements
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->integer('earned_points');
            $table->timestamp('earned_at');
            $table->json('progress_data')->nullable(); // for tracking progress
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id']);
            $table->index(['user_id', 'earned_at']);
        });

        // Point Transactions
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // earned, spent, bonus
            $table->integer('points');
            $table->string('source'); // report_submitted, achievement_earned, daily_bonus, etc.
            $table->string('description');
            $table->json('metadata')->nullable(); // additional context
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // Rewards/Store Items
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('cost_points');
            $table->string('type'); // discount, voucher, badge, physical
            $table->json('reward_data'); // specific reward details
            $table->integer('quantity')->nullable(); // null for unlimited
            $table->boolean('is_active')->default(true);
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });

        // User Reward Redemptions
        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reward_id')->constrained()->onDelete('cascade');
            $table->integer('points_spent');
            $table->string('status')->default('pending'); // pending, completed, expired
            $table->string('redemption_code')->unique();
            $table->timestamp('redeemed_at');
            $table->timestamp('expires_at')->nullable();
            $table->json('redemption_data')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'redeemed_at']);
        });

        // Leaderboards (weekly/monthly snapshots)
        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('period_type'); // weekly, monthly, all_time
            $table->date('period_date'); // start date of the period
            $table->integer('points');
            $table->integer('rank');
            $table->string('area')->nullable(); // for neighborhood leaderboards
            $table->timestamps();

            $table->index(['period_type', 'period_date', 'rank']);
            $table->index(['user_id', 'period_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaderboard_entries');
        Schema::dropIfExists('reward_redemptions');
        Schema::dropIfExists('rewards');
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('user_gamification');
    }
};
