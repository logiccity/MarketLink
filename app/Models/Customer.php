<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferences',
    ];

    protected $casts = [
        'preferences' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function favoriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimestamps();
    }

    public function favoriteFarmers(): BelongsToMany
    {
        return $this->belongsToMany(Farmer::class, 'favorite_farmers')->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function hasFavoritedProduct(int $productId): bool
    {
        return $this->favoriteProducts()->where('product_id', $productId)->exists();
    }

    public function hasFavoritedFarmer(int $farmerId): bool
    {
        return $this->favoriteFarmers()->where('farmer_id', $farmerId)->exists();
    }

    public function hasFavoritedMarket(int $marketId): bool
    {
        $preferred = $this->preferences['preferred_market_ids'] ?? [];
        return in_array($marketId, $preferred);
    }
}
