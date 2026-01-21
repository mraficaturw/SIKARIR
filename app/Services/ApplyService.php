<?php

namespace App\Services;

use App\Models\UserAccount;

/**
 * ============================================================================
 * SERVICE: APPLY
 * ============================================================================
 * Service untuk mengelola fitur apply/melamar lowongan.
 * Sentralisasi logic yang sebelumnya duplikat di:
 * - VacancyController::toggleApplied()
 * - ApplyButton Livewire component
 * 
 * Keuntungan:
 * - Single source of truth
 * - Mudah di-test
 * - Dapat di-inject via dependency injection
 * ============================================================================
 */
class ApplyService
{
    /**
     * Toggle status apply untuk lowongan.
     * 
     * @param UserAccount $user User yang melakukan toggle
     * @param int $jobId ID lowongan yang di-toggle
     * @return bool True jika sekarang applied, false jika tidak
     */
    public function toggle(UserAccount $user, int $jobId): bool
    {
        if ($this->isApplied($user, $jobId)) {
            $user->appliedJobs()->detach($jobId);
            return false;
        }

        $user->appliedJobs()->attach($jobId, ['applied_at' => now()]);
        return true;
    }

    /**
     * Cek apakah user sudah apply ke lowongan.
     * 
     * @param UserAccount $user User yang dicek
     * @param int $jobId ID lowongan yang dicek
     * @return bool True jika sudah apply
     */
    public function isApplied(UserAccount $user, int $jobId): bool
    {
        return $user->appliedJobs()->where('vacancy_id', $jobId)->exists();
    }
}
