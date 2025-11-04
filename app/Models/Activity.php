<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $fillable = [
        'activityable_type',
        'activityable_id',
        'title',
        'description',
        'type',
        'status',
        'scheduled_at',
        'completed_at',
        'start_date',
        'end_date',
        'outcome'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent activityable model (Customer or Opportunity).
     */
    public function activityable(): MorphTo
    {
        return $this->morphTo();
    }
}
