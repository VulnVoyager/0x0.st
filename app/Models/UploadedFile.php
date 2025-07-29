<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UploadedFile extends Model
{
    protected $fillable = [
        'file_hash',
        'delete_token',
        'original_name',
        'mime_type',
        'file_size',
        'file_path',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'file_size' => 'integer'
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}