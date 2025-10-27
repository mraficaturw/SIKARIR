<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;

class UserAccount extends Authenticatable
{
    use HasFactory, Notifiable, MustVerifyEmail;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi many-to-many dengan Internjob untuk favorites
     */
    public function favorites()
    {
        return $this->belongsToMany(Internjob::class, 'user_account_favorites', 'user_account_id', 'internjob_id')
                    ->withTimestamps();
    }

    /**
     * Relasi many-to-many dengan Internjob untuk applied jobs
     */
    public function appliedJobs()
    {
        return $this->belongsToMany(Internjob::class, 'user_account_applied', 'user_account_id', 'internjob_id')
                    ->withPivot('applied_at')
                    ->withTimestamps();
    }

    /**
     * Accessor untuk memastikan favorites selalu mengembalikan Collection
     */
    public function getFavoritesAttribute()
    {
        if (!$this->relationLoaded('favorites')) {
            $this->load('favorites');
        }
        
        return $this->getRelation('favorites') ?? new Collection();
    }

    /**
     * Accessor untuk memastikan appliedJobs selalu mengembalikan Collection
     */
    public function getAppliedJobsAttribute()
    {
        if (!$this->relationLoaded('appliedJobs')) {
            $this->load('appliedJobs');
        }
        
        return $this->getRelation('appliedJobs') ?? new Collection();
    }

    /**
     * Check if user has favorited a job
     */
    public function hasFavorited($jobId): bool
    {
        return $this->favorites->contains('id', $jobId);
    }

    /**
     * Check if user has applied to a job
     */
    public function hasApplied($jobId): bool
    {
        return $this->appliedJobs->contains('id', $jobId);
    }
}