<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category_id',
        'sku',
        'stock',
        'is_active',
        'is_featured',
        'is_on_sale',
        'sale_price',
        'sale_start_date',
        'sale_end_date',
        // SEO fields
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
        // Other meta fields as needed
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_on_sale' => 'boolean',
        'sale_start_date' => 'date',
        'sale_end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The media associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'product_media', 'product_id', 'media_id')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    /**
     * Get the primary media for the product.
     *
     * @return \App\Models\Media|null
     */
    public function primaryMedia(): ?Media
    {
        return $this->media()->wherePivot('is_primary', true)->first();
    }

    /**
     * Get the category that the product belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tags associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating a product
        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->name);
            }
        });

        // Clean up related models when a product is deleted
        static::deleting(function ($product) {
            // Optional: Delete media when product is deleted
            // $product->media()->delete();

            // Remove tag associations
            $product->tags()->detach();
        });
    }

    /**
     * Scope a query to only include active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include products on sale.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnSale($query)
    {
        $now = now();
        return $query->where('is_on_sale', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('sale_start_date')
                    ->orWhere('sale_start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('sale_end_date')
                    ->orWhere('sale_end_date', '>=', $now);
            })
            ->whereNotNull('sale_price');
    }

    /**
     * Scope a query to search products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter products by category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|array  $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCategory($query, $categoryId)
    {
        if (is_array($categoryId)) {
            return $query->whereIn('category_id', $categoryId);
        }

        return $query->where('category_id', $categoryId);
    }

    /**
     * Get the current price (sale price if on sale, regular price otherwise).
     *
     * @return float
     */
    public function getCurrentPriceAttribute()
    {
        if ($this->isOnSale()) {
            return $this->sale_price;
        }

        return $this->price;
    }

    /**
     * Check if product is currently on sale.
     *
     * @return bool
     */
    public function isOnSale(): bool
    {
        if (!$this->is_on_sale || is_null($this->sale_price)) {
            return false;
        }

        $now = now();

        $startDateValid = is_null($this->sale_start_date) || $this->sale_start_date <= $now;
        $endDateValid = is_null($this->sale_end_date) || $this->sale_end_date >= $now;

        return $startDateValid && $endDateValid;
    }

    /**
     * Get the discount percentage if on sale.
     *
     * @return float|null
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->isOnSale() || $this->price <= 0) {
            return null;
        }

        $discountAmount = $this->price - $this->sale_price;
        $discountPercentage = ($discountAmount / $this->price) * 100;

        return round($discountPercentage, 0);
    }

    /**
     * Check if the product is in stock.
     *
     * @return bool
     */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Decrement stock (e.g. when a product is purchased).
     *
     * @param int $quantity
     * @return bool
     */
    public function decrementStock(int $quantity = 1): bool
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            return $this->save();
        }

        return false;
    }
}
