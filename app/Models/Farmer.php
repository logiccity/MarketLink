<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stall_name',
        'contact_person',
        'phone',
        'address',
        'bio',
        'profile_image',
        'banner_image',
        'operating_days',
        'pickup_windows',
        'order_cutoff_hours',
        'latitude',
        'longitude',
        'approval_status',
    ];

    protected $casts = [
        'operating_days' => 'array',
        'order_cutoff_hours' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markets(): BelongsToMany
    {
        return $this->belongsToMany(Market::class, 'farmer_market')
            ->withPivot('stall_identifier')
            ->withTimestamps();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts(): HasMany
    {
        return $this->products()->where('availability_status', '!=', 'temporarily_unavailable');
    }

    public function getActiveProductsCountAttribute(): int
    {
        return $this->activeProducts()->count();
    }

    public function pickupSlots(): HasMany
    {
        return $this->hasMany(PickupSlot::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function weeklyStockTemplates(): HasMany
    {
        return $this->hasMany(WeeklyStockTemplate::class);
    }

    public function weeklyStockItems(): HasMany
    {
        return $this->hasMany(WeeklyStockItem::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'favorite_farmers')->withTimestamps();
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isSuspended(): bool
    {
        return $this->approval_status === 'suspended';
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->where('status', 'approved')->avg('rating'), 1) ?: 5.0;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->where('status', 'approved')->count();
    }
}
