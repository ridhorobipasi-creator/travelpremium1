<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'author_id', 'title', 'content', 'category', 'tags', 'image',
        'slug', 'featured_image', 'excerpt', 'is_published', 'published_at',
        'view_count', 'meta_title', 'meta_description', 'translations'
    ];

    protected $casts = [
        'tags'           => 'array',
        'featured_image' => 'array',
        'translations'   => 'array',
        'is_published'   => 'boolean',
        'published_at'   => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
