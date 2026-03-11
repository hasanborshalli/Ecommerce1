<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'short_description', 'description',
        'price', 'sale_price', 'stock', 'sku',
        'main_image', 'gallery', 'variants',
        'is_active', 'is_featured', 'is_new', 'is_on_sale', 'show_when_out_of_stock',
        'meta_title', 'meta_description', 'meta_keywords', 'sort_order',
    ];

    protected $casts = [
        'price'                   => 'decimal:2',
        'sale_price'              => 'decimal:2',
        'gallery'                 => 'array',
        'variants'                => 'array',
        'is_active'               => 'boolean',
        'is_featured'             => 'boolean',
        'is_new'                  => 'boolean',
        'is_on_sale'              => 'boolean',
        'show_when_out_of_stock'  => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Returns current effective price (sale price if on sale, else regular price)
    public function getEffectivePriceAttribute(): float
    {
        return ($this->is_on_sale && $this->sale_price) ? (float) $this->sale_price : (float) $this->price;
    }

    // Returns discount percentage
    public function getDiscountPercentAttribute(): int
    {
        if ($this->is_on_sale && $this->sale_price && $this->price > 0) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // True only when stock = 0
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    // Scopes
    // "visible" = is_active AND (stock > 0 OR show_when_out_of_stock)
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->where('stock', '>', 0)
                           ->orWhere('show_when_out_of_stock', true);
                     });
    }

    // For admin add-to-cart check — only truly active products
    public function scopeOrderable($query)
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new', true)->where('is_active', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true)->where('is_active', true);
    }
}