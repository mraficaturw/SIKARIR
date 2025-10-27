<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;

class UserAccount extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

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

    public function favorites()
    {
        return $this->belongsToMany(Internjob::class, 'user_account_favorites', 'user_account_id', 'internjob_id')
                    ->withTimestamps();
    }

    public function appliedJobs()
    {
        return $this->belongsToMany(Internjob::class, 'user_account_applied', 'user_account_id', 'internjob_id')
                    ->withPivot('applied_at')
                    ->withTimestamps();
    }

    public function hasFavorited($jobId): bool
    {
        return $this->favorites->contains('id', $jobId);
    }

    public function hasApplied($jobId): bool
    {
        return $this->appliedJobs->contains('id', $jobId);
    }
}