<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'city_id', 'price', 'duration',
        'includes', 'excludes', 'images', 'status',
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

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
