<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class companies extends Model
{
    protected $table = 'companies';
    protected $fillable = [
        'company_name',
        'logo',
        'official_website',
        'email',
        'phone',
        'address',
        'company_description',
    ];

    /**
     * Get the logo URL from Supabase Storage
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return Storage::disk('supabase')->url($this->logo);
        }
        return asset('img/com-logo-1.jpg');
    }

    /**
     * Relasi dengan internjobs
     */
    public function internjobs()
    {
        return $this->hasMany(Internjob::class, 'company_id');
    }
}
