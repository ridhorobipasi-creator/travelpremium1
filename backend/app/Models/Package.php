<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Package extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'city_id', 'price', 'duration',
        'includes', 'excludes', 'images', 'status', 'scope', 'price_category',
        'category_id', 'location_tag', 'pre_order_info', 'pricing_details',
        'price_min', 'price_max', 'child_price', 'price_display',
        'short_description', 'itinerary', 'itinerary_text', 'drone_price',
        'drone_location', 'notes', 'is_featured', 'sort_order',
        'meta_title', 'meta_description', 'translations'
    ];

    protected $casts = [
        'includes'        => 'array',
        'excludes'        => 'array',
        'images'          => 'array',
        'price'           => 'float',
        'pricing_details' => 'array',
        'itinerary'       => 'array',
        'translations'    => 'array',
        'is_featured'     => 'boolean',
        'price_min'       => 'float',
        'price_max'       => 'float',
        'child_price'     => 'float',
        'drone_price'     => 'float',
    ];

    protected $appends = ['average_rating', 'review_count'];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->name);
                $slug = $base;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
                    $slug = $base . '-' . $counter++;
                }
                $model->slug = $slug;
            }
        });

        static::deleted(function ($model) {
            if ($model->images && is_array($model->images)) {
                foreach ($model->images as $path) {
                    if (!Str::startsWith($path, 'http')) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
        });
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?: 5, 1);
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    public function getMainImageAttribute()
    {
        if (!empty($this->images) && isset($this->images[0])) {
            $path = $this->images[0];
            return Str::startsWith($path, 'http') ? $path : asset('storage/' . $path);
        }
        return asset('assets/images/placeholder-package.webp');
    }

    public function scopeTour(Builder $query)
    {
        return $query->where('scope', 'tour');
    }

    public function scopeOutbound(Builder $query)
    {
        return $query->where('scope', 'outbound');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
