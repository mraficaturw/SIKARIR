<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class internjob extends Model
{
    protected $fillable = [
        'title',
        'company',
        'location',
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
    ];
}
