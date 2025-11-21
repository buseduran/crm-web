<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CardReadLog extends Model
{
    protected $fillable = [
        'user_id',
        'card_string',
        'status',
        'read_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the card read log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance associated with this card read log.
     */
    public function attendance(): HasOne
    {
        return $this->hasOne(Attendance::class);
    }
}
