<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PickupSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'market_id',
        'pickup_date',
        'start_time',
        'end_time',
        'capacity',
        'booked_count',
        'cutoff_time',
        'is_active',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'cutoff_time' => 'datetime',
        'capacity' => 'integer',
        'booked_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getTimeRangeAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
    }

    public function isCutoffPassed(): bool
    {
        if ($this->cutoff_time) {
            return Carbon::now()->greaterThanOrEqualTo($this->cutoff_time);
        }
        $slotStart = Carbon::parse($this->pickup_date->format('Y-m-d') . ' ' . $this->start_time);
        return Carbon::now()->greaterThanOrEqualTo($slotStart->subHours($this->farmer?->order_cutoff_hours ?? 12));
    }

    public function isFull(): bool
    {
        return $this->booked_count >= $this->capacity;
    }

    public function isAvailable(): bool
    {
        return $this->is_active && !$this->isFull() && !$this->isCutoffPassed();
    }

    public function getRemainingCapacityAttribute(): int
    {
        return max(0, $this->capacity - $this->booked_count);
    }
}
