<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
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
    ];

    /**
     * Get the products associated with the tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating a tag
        static::creating(function ($tag) {
            if (!$tag->slug) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        // Clean up related records when a tag is deleted
        static::deleting(function ($tag) {
            // Remove product associations
            $tag->products()->detach();
        });
    }

    /**
     * Scope a query to find tags by name or partial name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Scope a query to find tags by exact slug.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get tag by slug.
     *
     * @param  string  $slug
     * @return \App\Models\Tag|null
     */
    public static function findBySlug($slug)
    {
        return static::bySlug($slug)->first();
    }

    /**
     * Get the number of products using this tag.
     *
     * @return int
     */
    public function getProductCountAttribute(): int
    {
        return $this->products()->count();
    }

    /**
     * Create a new tag or find existing by name.
     *
     * @param  string  $name
     * @return \App\Models\Tag
     */
    public static function findOrCreateByName(string $name): Tag
    {
        $slug = Str::slug($name);

        return static::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'slug' => $slug,
            ]
        );
    }
}
