<?php

namespace App\Observers;

use App\Models\Vacancy;

/**
 * ============================================================================
 * VACANCY OBSERVER
 * ============================================================================
 * Observer untuk menangani event lifecycle Vacancy model.
 * Fungsi utama: Automatic cache invalidation saat data berubah.
 * 
 * Event yang di-observe:
 * - created: Saat vacancy baru dibuat
 * - updated: Saat vacancy diupdate
 * - deleted: Saat vacancy dihapus
 * 
 * Semua event akan trigger clearCache() untuk menjaga konsistensi data.
 * ============================================================================
 */
class VacancyObserver
{
    /**
     * -------------------------------------------------------------------------
     * Event: Vacancy Baru Dibuat
     * -------------------------------------------------------------------------
     * Dipanggil setelah vacancy baru berhasil disimpan ke database.
     * Cache perlu di-clear karena ada lowongan baru yang harus muncul.
     * 
     * @param Vacancy $vacancy Instance vacancy yang baru dibuat
     * @return void
     */
    public function created(Vacancy $vacancy): void
    {
        // Clear semua cache vacancy karena ada data baru
        Vacancy::clearCache();
    }

    /**
     * -------------------------------------------------------------------------
     * Event: Vacancy Diupdate
     * -------------------------------------------------------------------------
     * Dipanggil setelah vacancy berhasil diupdate di database.
     * Cache perlu di-clear karena data yang tersimpan mungkin sudah berbeda.
     * 
     * @param Vacancy $vacancy Instance vacancy yang diupdate
     * @return void
     */
    public function updated(Vacancy $vacancy): void
    {
        // Clear semua cache vacancy karena data telah berubah
        Vacancy::clearCache();
    }

    /**
     * -------------------------------------------------------------------------
     * Event: Vacancy Dihapus
     * -------------------------------------------------------------------------
     * Dipanggil setelah vacancy berhasil dihapus dari database.
     * Cache perlu di-clear karena lowongan tersebut sudah tidak ada.
     * 
     * @param Vacancy $vacancy Instance vacancy yang dihapus
     * @return void
     */
    public function deleted(Vacancy $vacancy): void
    {
        // Clear semua cache vacancy karena data telah dihapus
        Vacancy::clearCache();
    }

    /**
     * -------------------------------------------------------------------------
     * Event: Vacancy Di-restore (Soft Delete)
     * -------------------------------------------------------------------------
     * Dipanggil setelah vacancy yang soft-deleted di-restore.
     * Jika menggunakan soft deletes, cache juga perlu di-clear.
     * 
     * @param Vacancy $vacancy Instance vacancy yang di-restore
     * @return void
     */
    public function restored(Vacancy $vacancy): void
    {
        // Clear semua cache vacancy karena data telah di-restore
        Vacancy::clearCache();
    }

    /**
     * -------------------------------------------------------------------------
     * Event: Vacancy Force Deleted
     * -------------------------------------------------------------------------
     * Dipanggil setelah vacancy benar-benar dihapus dari database (permanent).
     * Cache perlu di-clear untuk menghapus referensi yang sudah tidak valid.
     * 
     * @param Vacancy $vacancy Instance vacancy yang force deleted
     * @return void
     */
    public function forceDeleted(Vacancy $vacancy): void
    {
        // Clear semua cache vacancy karena data telah dihapus permanen
        Vacancy::clearCache();
    }
}
