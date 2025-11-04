<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'address',
        'city',
        'country',
        'postal_code',
        'status',
        'notes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the opportunities for the customer.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    /**
     * Get the activities for the customer.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activityable');
    }
}
