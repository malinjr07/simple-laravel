<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id'
    ];

    /**
     * Get all products in this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the parent category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the direct child categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all descendants (recursive).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors (recursive).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ancestors(): BelongsTo
    {
        return $this->parent()->with('ancestors');
    }

    /**
     * Check if category is a root node (has no parent).
     *
     * @return bool
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Check if category is a leaf node (has no children).
     *
     * @return bool
     */
    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }

    /**
     * Get all root categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get all leaf categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLeaves($query)
    {
        return $query->whereNotIn('id', function ($q) {
            $q->select('parent_id')
                ->from('categories')
                ->whereNotNull('parent_id');
        });
    }

    /**
     * Get all products that belong to this category or any of its descendants.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allProducts()
    {
        // Get IDs of this category and all descendants
        $categoryIds = $this->descendants()
            ->pluck('id')
            ->push($this->id)
            ->toArray();

        return Product::whereIn('category_id', $categoryIds)->get();
    }

    /**
     * Get the full path of categories from root to this category.
     *
     * @return string
     */
    public function getPathAttribute(): string
    {
        $path = $this->name;
        $category = $this;

        while ($category->parent) {
            $category = $category->parent;
            $path = $category->name . ' > ' . $path;
        }

        return $path;
    }

    /**
     * Get the depth of this category in the hierarchy.
     * Root categories have depth 0.
     *
     * @return int
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $category = $this;

        while ($category->parent) {
            $depth++;
            $category = $category->parent;
        }

        return $depth;
    }

    /**
     * Create a URL-friendly slug from the name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;

        if (!isset($this->attributes['slug']) || empty($this->attributes['slug'])) {
            $this->attributes['slug'] = \Str::slug($value);
        }
    }
}
