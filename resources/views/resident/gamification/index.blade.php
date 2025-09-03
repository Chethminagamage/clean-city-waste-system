@extends('layouts.app')

@section('title', 'Eco Points & Progress')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Compact Header - matching dashboard style -->
    <div class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-trophy text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $stats['current_rank'] }}</h1>
                        <p class="text-gray-600">Level {{ $stats['current_level'] }} • {{ number_format($stats['total_points']) }} Points</p>
                    </div>
                </div>
                <!-- Quick Actions -->
                <div class="flex gap-3">
                    <div class="sm:hidden">
                        <a href="{{ route('resident.gamification.rewards') }}" 
                           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg font-medium transition-colors text-sm">
                            <i class="fas fa-gift text-sm"></i>
                            <span class="hidden xs:inline">Rewards</span>
                        </a>
                    </div>
                    <div class="hidden sm:flex gap-3">
                        <a href="{{ route('resident.gamification.rewards') }}" 
                           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-gift text-sm"></i>
                            <span>Rewards</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - wider container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <!-- Progress Section -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-green-600"></i>
                Your Progress
            </h2>
            
            @if($stats['points_to_next_level'] > 0)
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Level {{ $stats['current_level'] }}</span>
                        <span class="text-sm font-medium text-gray-600">Level {{ $stats['current_level'] + 1 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        @php
                            $totalNeeded = $stats['total_points'] + $stats['points_to_next_level'];
                            $progress = $totalNeeded > 0 ? ($stats['total_points'] / $totalNeeded) * 100 : 0;
                        @endphp
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-4 rounded-full flex items-center justify-end pr-2 text-white text-xs font-medium transition-all duration-500" 
                             style="width: {{ max($progress, 10) }}%">
                            {{ number_format($progress, 1) }}%
                        </div>
                    </div>
                    <p class="text-gray-600 mt-2 text-sm">{{ $stats['points_to_next_level'] }} more points to reach next level!</p>
                </div>
            @else
                <div class="mb-4">
                    <div class="w-full bg-yellow-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-4 rounded-full flex items-center justify-center text-white text-xs font-bold" 
                             style="width: 100%">
                            MAX LEVEL REACHED! 🏆
                        </div>
                    </div>
                    <p class="text-gray-600 mt-2 text-sm">Congratulations! You've reached the highest level!</p>
                </div>
            @endif
        </div>

        <!-- Stats Grid - wider layout -->
        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
            <!-- Total Points -->
            <div class="bg-white rounded-xl card-shadow p-6 text-center stat-card">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-coins text-yellow-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Total Points</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['total_points']) }}</p>
            </div>

            <!-- Achievements -->
            <div class="bg-white rounded-xl card-shadow p-6 text-center stat-card">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-trophy text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Achievements</h3>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['achievements_count'] }}</p>
            </div>

            <!-- Current Level -->
            <div class="bg-white rounded-xl card-shadow p-6 text-center stat-card">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-star text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Current Level</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['current_level'] }}</p>
            </div>

            <!-- Weekly Rank -->
            <div class="bg-white rounded-xl card-shadow p-6 text-center stat-card">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Your Rank</h3>
                <p class="text-2xl font-bold text-green-600">#2</p>
                <p class="text-sm text-gray-500">this week</p>
            </div>
        </div>

        <!-- Two Column Layout for Achievements and Leaderboard -->
        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Achievements -->
            @if($recentAchievements->count() > 0)
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-trophy text-yellow-600"></i>
                    Recent Achievements
                </h3>
                <div class="space-y-3">
                    @foreach($recentAchievements->take(3) as $userAchievement)
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-100">
                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-trophy text-white text-sm"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-semibold text-gray-800 text-sm">{{ $userAchievement->achievement->name }}</h4>
                            <p class="text-gray-600 text-xs">{{ $userAchievement->achievement->description }}</p>
                            <p class="text-yellow-600 text-xs">Earned {{ $userAchievement->earned_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Weekly Leaderboard -->
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-medal text-blue-600"></i>
                    Weekly Leaders
                </h3>
                <div class="space-y-3">
                    @foreach($weeklyLeaderboard->take(5) as $index => $user)
                    <div class="flex items-center gap-3 p-3 rounded-lg {{ Auth::id() === $user->id ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 
                                    {{ $index === 0 ? 'bg-yellow-500 text-white' : 
                                       ($index === 1 ? 'bg-gray-400 text-white' : 
                                       ($index === 2 ? 'bg-orange-500 text-white' : 'bg-gray-300 text-gray-600')) }}">
                            <span class="text-xs font-bold">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-800 text-sm">{{ $user->name }}</span>
                                @if(Auth::id() === $user->id)
                                    <span class="text-green-600 text-xs">(You)</span>
                                @endif
                            </div>
                            <span class="text-gray-600 text-xs">{{ number_format($user->weekly_points ?? 0) }} points this week</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl card-shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bolt text-orange-600"></i>
                Quick Actions
            </h3>
            <div class="grid md:grid-cols-2 gap-4">
                <a href="{{ route('resident.gamification.rewards') }}" 
                   class="flex items-center gap-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 rounded-lg border border-green-200 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-gift text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-green-700">Browse Rewards</h4>
                        <p class="text-green-600 text-sm">Spend your {{ number_format($stats['total_points']) }} points</p>
                    </div>
                </a>

                <a href="{{ route('resident.dashboard') }}" 
                   class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50 to-cyan-50 hover:from-blue-100 hover:to-cyan-100 rounded-lg border border-blue-200 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-blue-700">Submit Report</h4>
                        <p class="text-blue-600 text-sm">Earn more points by helping community</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card-shadow {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .stat-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection
