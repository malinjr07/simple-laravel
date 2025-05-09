<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'media_id';

    /**
     * The custom created at column name.
     */
    public const CREATED_AT = 'media_created_at';

    /**
     * The custom updated at column name.
     */
    public const UPDATED_AT = 'media_updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'url',
        'fileName',
        'extension',
        'mime_type',
        'disk',
        'path',
        'size',
        'is_primary',
        'product_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'is_primary' => 'boolean',
        'media_created_at' => 'datetime',
        'media_updated_at' => 'datetime',
    ];

    /**
     * Get the product that owns the media.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Scope a query to only include primary media.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Check if this media is the primary one.
     *
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->is_primary === true;
    }

    /**
     * Set this media as primary and unset other primary media for the same product.
     *
     * @return bool
     */
    public function setPrimary(): bool
    {
        if ($this->product_id) {
            // Unset other primary media for this product
            self::where('product_id', $this->product_id)
                ->where('media_id', '!=', $this->media_id)
                ->update(['is_primary' => false]);
        }

        $this->is_primary = true;
        return $this->save();
    }

    /**
     * Get file name with extension.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->fileName . ($this->extension ? '.' . $this->extension : '');
    }

    /**
     * Get human-readable file size.
     *
     * @return string
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes === null) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }
}
