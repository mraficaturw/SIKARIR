<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internjob extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company_id',
        'location',
        'type',
        'salary_min',
        'salary_max',
        'description',
        'responsibility',
        'qualifications',
        'deadline',
        'category',
        'apply_url',
    ];

    protected $casts = [
        'deadline' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    /**
     * Get the company that owns the internjob
     */
    public function company()
    {
        return $this->belongsTo(companies::class, 'company_id');
    }

    /**
     * Get the logo URL from Company relationship
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->company) {
            return $this->company->logo_url;
        }
        return asset('img/com-logo-1.jpg');
    }

    /**
     * Relasi dengan users yang memfavoritkan job ini
     */
    public function favoredBy()
    {
        return $this->belongsToMany(UserAccount::class, 'user_account_favorites', 'internjob_id', 'user_account_id');
    }

    /**
     * Relasi dengan users yang apply ke job ini
     */
    public function appliedBy()
    {
        return $this->belongsToMany(UserAccount::class, 'user_account_applied', 'internjob_id', 'user_account_id')
            ->withPivot('applied_at');
    }
}
