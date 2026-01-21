<?php

namespace App\Services;

use App\Models\UserAccount;

/**
 * ============================================================================
 * SERVICE: FAVORITE
 * ============================================================================
 * Service untuk mengelola fitur favorit lowongan.
 * Sentralisasi logic yang sebelumnya duplikat di:
 * - VacancyController::toggleFavorite()
 * - FavoriteButton Livewire component
 * 
 * Keuntungan:
 * - Single source of truth
 * - Mudah di-test
 * - Dapat di-inject via dependency injection
 * ============================================================================
 */
class FavoriteService
{
    /**
     * Toggle status favorit untuk lowongan.
     * 
     * @param UserAccount $user User yang melakukan toggle
     * @param int $jobId ID lowongan yang di-toggle
     * @return bool True jika sekarang favorit, false jika tidak
     */
    public function toggle(UserAccount $user, int $jobId): bool
    {
        if ($this->isFavorited($user, $jobId)) {
            $user->favorites()->detach($jobId);
            return false;
        }

        $user->favorites()->attach($jobId);
        return true;
    }

    /**
     * Cek apakah lowongan sudah difavoritkan oleh user.
     * 
     * @param UserAccount $user User yang dicek
     * @param int $jobId ID lowongan yang dicek
     * @return bool True jika sudah favorit
     */
    public function isFavorited(UserAccount $user, int $jobId): bool
    {
        return $user->favorites()->where('vacancy_id', $jobId)->exists();
    }
}
