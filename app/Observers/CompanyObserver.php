<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Vacancy;

/**
 * ============================================================================
 * COMPANY OBSERVER
 * ============================================================================
 * Observer untuk menangani event lifecycle Company model.
 * Fungsi utama: Automatic cache invalidation saat data berubah.
 * 
 * Event yang di-observe:
 * - updated: Saat company diupdate
 * - deleted: Saat company dihapus
 * 
 * Ketika company berubah, cache company detail dan vacancy cache juga perlu di-clear
 * karena vacancy menampilkan data company (nama, logo, dll).
 * ============================================================================
 */
class CompanyObserver
{
    /**
     * -------------------------------------------------------------------------
     * Event: Company Diupdate
     * -------------------------------------------------------------------------
     * Dipanggil setelah company berhasil diupdate di database.
     * Cache perlu di-clear karena data yang tersimpan mungkin sudah berbeda.
     * 
     * Contoh perubahan yang mempengaruhi:
     * - Nama perusahaan berubah (tampil di vacancy card)
     * - Logo perusahaan berubah (tampil di vacancy card)
     * 
     * @param Company $company Instance company yang diupdate
     * @return void
     */
    public function updated(Company $company): void
    {
        // Clear cache company detail
        Company::clearCache($company->id);

        // Clear vacancy cache karena vacancy menampilkan data company
        // (nama dan logo company tampil di setiap vacancy card)
        Vacancy::clearCache();
    }

    /**
     * -------------------------------------------------------------------------
     * Event: Company Dihapus
     * -------------------------------------------------------------------------
     * Dipanggil setelah company berhasil dihapus dari database.
     * Cache perlu di-clear karena company tersebut sudah tidak ada.
     * 
     * Note: Dalam praktik, company biasanya tidak dihapus jika masih punya
     * vacancy aktif (foreign key constraint). Tapi untuk safety, tetap clear cache.
     * 
     * @param Company $company Instance company yang dihapus
     * @return void
     */
    public function deleted(Company $company): void
    {
        // Clear cache company detail
        Company::clearCache($company->id);

        // Clear vacancy cache untuk safety
        Vacancy::clearCache();
    }

    /**
     * -------------------------------------------------------------------------
     * Event: Company Di-restore (Soft Delete)
     * -------------------------------------------------------------------------
     * Dipanggil setelah company yang soft-deleted di-restore.
     * Jika menggunakan soft deletes, cache juga perlu di-clear.
     * 
     * @param Company $company Instance company yang di-restore
     * @return void
     */
    public function restored(Company $company): void
    {
        // Clear cache company detail
        Company::clearCache($company->id);

        // Clear vacancy cache
        Vacancy::clearCache();
    }

    /**
     * -------------------------------------------------------------------------
     * Event: Company Force Deleted
     * -------------------------------------------------------------------------
     * Dipanggil setelah company benar-benar dihapus dari database (permanent).
     * Cache perlu di-clear untuk menghapus referensi yang sudah tidak valid.
     * 
     * @param Company $company Instance company yang force deleted
     * @return void
     */
    public function forceDeleted(Company $company): void
    {
        // Clear cache company detail
        Company::clearCache($company->id);

        // Clear vacancy cache
        Vacancy::clearCache();
    }
}
