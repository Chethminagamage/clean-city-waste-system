<?php

namespace App\Services;

use App\Models\User;
use App\Mail\CollectorAccountMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Service class for collector management operations
 * Centralizes business logic without changing existing functionality
 */
class CollectorService
{
    /**
     * Create a new collector (preserves existing functionality)
     */
    public function createCollector(array $data): User
    {
        $plainPassword = Str::random(10);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($plainPassword),
            'role'              => 'collector',
            'contact'           => $data['contact'] ?? null,
            'location'          => $data['location'] ?? null,
            'status'            => true, 
            'email_verified_at' => now(),
        ]);

        // Send email (preserves existing functionality)
        Mail::to($user->email)->send(new CollectorAccountMail(
            $user->name,
            $user->email,
            $plainPassword
        ));

        return $user;
    }

    /**
     * Update collector (preserves existing functionality)
     */
    public function updateCollector(User $collector, array $data): User
    {
        $collector->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'contact' => $data['contact'] ?? null,
            'location' => $data['location'] ?? null,
        ]);

        return $collector;
    }

    /**
     * Toggle collector status (preserves existing functionality)
     */
    public function toggleCollectorStatus(User $collector): array
    {
        $collector->status = ($collector->status === 'active') ? 'blocked' : 'active';
        $collector->save();

        $action = ($collector->status === 'active') ? 'unblocked' : 'blocked';

        return [
            'status' => $collector->status,
            'action' => $action,
            'message' => "Collector has been {$action} successfully."
        ];
    }

    /**
     * Get collectors with search (preserves existing functionality)
     */
    public function getCollectorsWithSearch(?string $search, int $perPage = 10)
    {
        return User::where('role', 'collector')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('location', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
