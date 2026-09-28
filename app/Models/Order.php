<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PLACED = 'PLACED';
    public const STATUS_ACCEPTED = 'ACCEPTED';
    public const STATUS_DECLINED = 'DECLINED';
    public const STATUS_READY_FOR_PICKUP = 'READY_FOR_PICKUP';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'customer_id',
        'farmer_id',
        'market_id',
        'pickup_slot_id',
        'order_number',
        'pickup_date',
        'status',
        'subtotal',
        'total',
        'payment_method',
        'notes',
        'decline_reason',
        'cancellation_reason',
        'cancelled_by',
        'placed_at',
        'completed_at',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'placed_at' => 'datetime',
        'completed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function pickupSlot(): BelongsTo
    {
        return $this->belongsTo(PickupSlot::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $lastOrder = self::whereYear('created_at', $year)->latest('id')->first();
        $nextNumber = $lastOrder ? ($lastOrder->id + 1) : 1;
        return sprintf('ML-%s-%06d', $year, $nextNumber);
    }

    public function canCustomerModifyOrCancel(): bool
    {
        return $this->status === self::STATUS_PLACED 
            && (!$this->pickupSlot || !$this->pickupSlot->isCutoffPassed());
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) ($this->total ?? 0);
    }

    public function getCustomerNotesAttribute(): ?string
    {
        return $this->notes;
    }

    public function getReviewSubmittedAttribute(): bool
    {
        return $this->reviews()->where('customer_id', $this->customer_id)->exists();
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PLACED => 'badge-placed',
            self::STATUS_ACCEPTED => 'badge-accepted',
            self::STATUS_READY_FOR_PICKUP => 'badge-ready',
            self::STATUS_COMPLETED => 'badge-completed',
            self::STATUS_DECLINED, self::STATUS_CANCELLED => 'badge-cancelled',
            default => 'badge-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PLACED => 'Order Placed',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_READY_FOR_PICKUP => 'Ready for Pickup',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_DECLINED => 'Declined',
            self::STATUS_CANCELLED => 'Cancelled',
            default => $this->status,
        };
    }
}
