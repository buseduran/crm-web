<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'work_duration',
        'status',
        'notes',
        'card_read_log_id',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'work_duration' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Attendance $attendance) {
            // Eğer check_in ve check_out varsa, work_duration'ı otomatik hesapla
            if ($attendance->check_in && $attendance->check_out) {
                $attendance->work_duration = $attendance->calculateWorkDuration();
            }
        });
    }

    /**
     * Get the user that owns the attendance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the card read log associated with this attendance.
     */
    public function cardReadLog(): BelongsTo
    {
        return $this->belongsTo(CardReadLog::class);
    }

    /**
     * Calculate work duration from check_in and check_out times.
     */
    public function calculateWorkDuration(): ?int
    {
        if (!$this->check_in || !$this->check_out) {
            return null;
        }

        // check_in ve check_out datetime olarak cast edilmiş, ama sadece time kısmını kullanacağız
        $checkIn = $this->check_in instanceof \Carbon\Carbon ? $this->check_in : \Carbon\Carbon::parse($this->check_in);
        $checkOut = $this->check_out instanceof \Carbon\Carbon ? $this->check_out : \Carbon\Carbon::parse($this->check_out);

        // Aynı gün içinde saat farkını hesapla
        $checkInTime = $checkIn->format('H:i:s');
        $checkOutTime = $checkOut->format('H:i:s');

        $checkInCarbon = \Carbon\Carbon::createFromTimeString($checkInTime);
        $checkOutCarbon = \Carbon\Carbon::createFromTimeString($checkOutTime);

        // Eğer çıkış saati giriş saatinden küçükse (gece vardiyası), 24 saat ekle
        if ($checkOutCarbon->lt($checkInCarbon)) {
            $checkOutCarbon->addDay();
        }

        return $checkInCarbon->diffInMinutes($checkOutCarbon);
    }

    /**
     * Get work duration in hours and minutes format.
     */
    public function getWorkDurationFormattedAttribute(): ?string
    {
        if (!$this->work_duration) {
            return null;
        }

        $hours = floor($this->work_duration / 60);
        $minutes = $this->work_duration % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }
}
