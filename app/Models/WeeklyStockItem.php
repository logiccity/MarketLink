<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyStockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'farmer_id',
        'product_id',
        'week_date',
        'quantity',
        'price',
        'availability_status',
    ];

    protected $casts = [
        'week_date' => 'date',
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(WeeklyStockTemplate::class, 'template_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
