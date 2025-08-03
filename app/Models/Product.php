<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'quantity',
        'cost',
        'price',
        'expiry_date',
        'description',
        'sku',
        'is_active',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'cost' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    /**
     * Get the category for this product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get sale items for this product
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get profit margin
     */
    public function getProfitMarginAttribute(): float
    {
        if ($this->cost > 0) {
            return (($this->price - $this->cost) / $this->cost) * 100;
        }
        return 0;
    }

    /**
     * Get total value
     */
    public function getTotalValueAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Check if product is low in stock (less than 10 items)
     */
    public function isLowStock(): bool
    {
        return $this->quantity < 10;
    }

    /**
     * Check if product is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
