<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $rewards = Reward::where('user_id', $user->id)
            ->with('review.reservation')
            ->latest()
            ->paginate(10);
        
        $stats = [
            'total_rewards' => Reward::where('user_id', $user->id)->count(),
            'available_rewards' => Reward::where('user_id', $user->id)
                ->where('used', false)
                ->where(function($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->count(),
            'used_rewards' => Reward::where('user_id', $user->id)
                ->where('used', true)
                ->count(),
        ];
        
        return view('client.rewards.index', compact('rewards', 'stats'));
    }
}
