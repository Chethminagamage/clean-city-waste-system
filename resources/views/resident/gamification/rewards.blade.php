@extends('layouts.app')

@section('title', 'Rewards Store')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-colors duration-300">
    <!-- Compact Header - matching dashboard style -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-xl flex items-center justify-center">
                        <i class="fas fa-gift text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rewards Store</h1>
                        <p class="text-gray-600 dark:text-gray-300">You have <strong>{{ number_format($stats['total_points']) }} points</strong> to spend</p>
                    </div>
                </div>
                <!-- Quick Actions -->
                <div class="flex gap-3">
                    <div class="sm:hidden">
                        <a href="{{ route('resident.gamification.index') }}" 
                           class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-800 text-white px-3 py-2 rounded-lg font-medium transition-colors text-sm">
                            <i class="fas fa-arrow-left text-sm"></i>
                        </a>
                    </div>
                    <div class="hidden sm:flex gap-3">
                        <a href="{{ route('resident.gamification.index') }}" 
                           class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-800 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-arrow-left text-sm"></i>
                            <span>Back to Dashboard</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - wider container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Available Rewards -->
        @if(!empty($rewardsByType))
            @foreach($rewardsByType as $type => $rewards)
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-{{ $type === 'badge' ? 'yellow-400 to-yellow-500' : ($type === 'discount' ? 'green-400 to-green-500' : ($type === 'physical' ? 'blue-400 to-blue-500' : 'purple-400 to-purple-500')) }} rounded-lg flex items-center justify-center">
                            <i class="fas {{ 
                                $type === 'badge' ? 'fa-medal' : 
                                ($type === 'discount' ? 'fa-percentage' :
                                ($type === 'physical' ? 'fa-box' :
                                ($type === 'experience' ? 'fa-calendar-star' : 'fa-gift')))
                            }} text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white capitalize">
                            {{ str_replace('_', ' ', $type) }} Rewards
                        </h2>
                    </div>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($rewards as $reward)
                            <div class="bg-white dark:bg-gray-800 rounded-xl card-shadow overflow-hidden {{ $reward->can_afford ? 'ring-2 ring-green-200 dark:ring-green-700' : 'opacity-75' }} stat-card">
                                <!-- Reward Icon/Header -->
                                <div class="h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                    <i class="fas {{ 
                                        $type === 'badge' ? 'fa-medal' : 
                                        ($type === 'discount' ? 'fa-percentage' :
                                        ($type === 'physical' ? 'fa-box' :
                                        ($type === 'experience' ? 'fa-calendar-star' : 'fa-gift')))
                                    }} text-gray-400 dark:text-gray-500 text-2xl"></i>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $reward->name }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3 h-10 overflow-hidden">{{ $reward->description }}</p>
                                    
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-coins text-yellow-500 dark:text-yellow-400 mr-1"></i>
                                            <span class="font-bold text-lg text-gray-800 dark:text-white">{{ number_format($reward->cost_points) }}</span>
                                            <span class="text-gray-500 dark:text-gray-400 text-xs ml-1">pts</span>
                                        </div>
                                        
                                        @if($reward->remaining_quantity !== null)
                                            <span class="text-xs bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400 px-2 py-1 rounded">
                                                {{ $reward->remaining_quantity }} left
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($reward->can_afford && $reward->isAvailable())
                                        <form method="POST" action="{{ route('resident.gamification.redeem', $reward) }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                                                <i class="fas fa-shopping-cart mr-2"></i>Redeem Now
                                            </button>
                                        </form>
                                    @elseif(!$reward->can_afford)
                                        <button disabled class="w-full bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 px-4 py-2 rounded-lg font-medium cursor-not-allowed text-sm">
                                            <i class="fas fa-lock mr-2"></i>Need {{ number_format($reward->cost_points - $stats['total_points']) }} more points
                                        </button>
                                    @else
                                        <button disabled class="w-full bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 px-4 py-2 rounded-lg font-medium cursor-not-allowed text-sm">
                                            <i class="fas fa-ban mr-2"></i>Currently Unavailable
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl card-shadow p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gift text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">No Rewards Available</h3>
                <p class="text-gray-600 dark:text-gray-300">Check back later for exciting rewards!</p>
            </div>
        @endif

        <!-- Recent Redemptions -->
        @if($userRedemptions->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl card-shadow p-6 mt-8">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-gray-600 dark:text-gray-400"></i>
                    Your Recent Redemptions
                </h3>
                <div class="space-y-3">
                    @foreach($userRedemptions->take(5) as $redemption)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-medium text-gray-800 dark:text-white">{{ $redemption->reward->name }}</h4>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $redemption->redeemed_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $redemption->points_spent }} points spent</span>
                                    @if($redemption->redemption_code)
                                        <span class="text-xs bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 px-2 py-1 rounded font-mono">
                                            {{ $redemption->redemption_code }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
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
