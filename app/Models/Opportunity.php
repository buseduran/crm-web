<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Opportunity extends Model
{
    protected $fillable = [
        'customer_id',
        'title',
        'description',
        'value',
        'stage',
        'priority',
        'expected_close_date',
        'actual_close_date',
        'notes'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the opportunity.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the activities for the opportunity.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activityable');
    }
}
