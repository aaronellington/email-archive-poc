<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'processing_status',
        'processing_result',
        'sender',
        'recipient',
        'subject',
        'has_attachments',
        'is_test',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
