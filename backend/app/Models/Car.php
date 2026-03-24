<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'name', 'type', 'price_per_day', 'status',
        'image', 'seats', 'fuel', 'transmission',
        'vehicle_id', 'category', 'capacity', 'description',
        'price_with_driver', 'pricing_details', 'features',
        'includes', 'terms', 'featured_image', 'gallery_images',
        'is_featured', 'sort_order', 'meta_title', 'meta_description',
        'translations', 'slug'
    ];

    protected $casts = [
        'price_per_day'     => 'float',
        'seats'             => 'integer',
        'price_with_driver' => 'float',
        'pricing_details'   => 'array',
        'features'          => 'array',
        'includes'          => 'array',
        'featured_image'    => 'array',
        'gallery_images'    => 'array',
        'translations'      => 'array',
        'is_featured'       => 'boolean',
    ];
}
