<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    // Only these fields can be saved from a form or from code.
    protected $fillable = [
        'name',
        'city',
        'google_business_url',
        'facebook_url',
        'instagram_url',
        'website_url',
        'status',
        'generated_content',
        'slug',
        'published_at',
        'error_message',
    ];

    // Convert these fields automatically when reading and saving.
    protected $casts = [
        'generated_content' => 'array',
        'published_at' => 'datetime',
    ];
}