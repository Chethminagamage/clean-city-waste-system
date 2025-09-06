<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for common model query scopes
 * Helps reduce code duplication across models
 */
trait HasCommonScopes
{
    /**
     * Scope to get active records
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get records by status
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get recent records
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to search by name (if model has name field)
     */
    public function scopeSearchByName(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where('name', 'like', "%{$search}%");
    }
}
