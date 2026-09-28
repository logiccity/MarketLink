<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyStockTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'product_id',
        'default_quantity',
        'default_price',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'default_quantity' => 'integer',
        'default_price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function weeklyStockItems(): HasMany
    {
        return $this->hasMany(WeeklyStockItem::class, 'template_id');
    }
}
