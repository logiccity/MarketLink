<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'city',
        'operating_days',
        'opening_time',
        'closing_time',
        'latitude',
        'longitude',
        'map_url',
        'image',
        'status',
    ];

    protected $casts = [
        'operating_days' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function farmers(): BelongsToMany
    {
        return $this->belongsToMany(Farmer::class, 'farmer_market')
            ->withPivot('stall_identifier')
            ->withTimestamps();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function pickupSlots(): HasMany
    {
        return $this->hasMany(PickupSlot::class);
    }

    public function getActiveFarmersCountAttribute(): int
    {
        return $this->farmers()->where('approval_status', 'approved')->count();
    }

    public function getLocationAttribute(): string
    {
        return $this->address . ($this->city ? ', ' . $this->city : '');
    }

    public function getMarketDaysAttribute(): array
    {
        return $this->operating_days ?? [];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('storage/' . $this->image);
    }
}
