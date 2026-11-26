<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'business_id',
        'original_name',
        'stored_path',
        'mime_type',
        'size',
        'kind',
        'extracted_text',
        'extraction_status',
        'extraction_error',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
