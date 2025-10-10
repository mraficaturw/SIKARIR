<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internjob extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'location', 
        'type',
        'salary_min',
        'salary_max',
        'description',
        'responsibility',
        'qualifications',
        'deadline',
        'logo',
        'category',
        'apply_url',
    ];

    protected $casts = [
        'deadline' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

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