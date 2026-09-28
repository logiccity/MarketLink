<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name_snapshot',
        'unit_price_snapshot',
        'unit_snapshot',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'unit_price_snapshot' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getProductNameAttribute(): string
    {
        return $this->product_name_snapshot ?? ($this->product?->name ?? 'Produce Item');
    }

    public function getUnitPriceAttribute(): float
    {
        return (float) ($this->unit_price_snapshot ?? ($this->product?->price ?? 0));
    }

    public function getUnitAttribute(): string
    {
        return $this->unit_snapshot ?? ($this->product?->unit ?? 'unit');
    }
}
