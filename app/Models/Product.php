<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'category_id',
        'market_id',
        'name',
        'slug',
        'description',
        'price',
        'unit',
        'quantity',
        'default_weekly_quantity',
        'availability_status',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'default_weekly_quantity' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'favorites')->withTimestamps();
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images()->where('is_primary', true)->latest('id')->first()
            ?: $this->images()->latest('id')->first();

        if ($primary && $primary->image_path) {
            $path = $primary->image_path;
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            $cleanPath = ltrim(preg_replace('#^/?storage/#', '', $path), '/');
            return asset('storage/' . $cleanPath);
        }

        return 'https://images.unsplash.com/photo-1610348725531-843dff563e2c?auto=format&fit=crop&w=600&q=80';
    }

    public function getEffectiveAvailabilityAttribute(): string
    {
        if ($this->quantity <= 0) {
            return 'sold_out';
        }
        if ($this->quantity <= 5) {
            return 'low_stock';
        }
        return $this->availability_status;
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->where('status', 'approved')->avg('rating'), 1) ?: 5.0;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->where('status', 'approved')->count();
    }

    public function getStockQuantityAttribute(): int
    {
        return $this->quantity ?? 0;
    }

    public function getImageAttribute(): ?string
    {
        return $this->primary_image_url;
    }

    public function getIsOrganicAttribute(): bool
    {
        return (bool) ($this->farmer?->is_organic ?? false);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->availability_status !== 'temporarily_unavailable';
    }

    public function getCutoffDateAttribute(): ?\Carbon\Carbon
    {
        return now()->addDays(3);
    }
}
