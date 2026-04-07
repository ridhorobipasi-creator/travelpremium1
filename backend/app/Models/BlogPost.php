<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'author_id', 'title', 'content', 'category', 'tags', 'image', 'scope',
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

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->title);
                $slug = $base;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
                    $slug = $base . '-' . $counter++;
                }
                $model->slug = $slug;
            }
        });
    }

    public function scopeTour(Builder $query)
    {
        return $query->where('scope', 'tour');
    }

    public function scopeOutbound(Builder $query)
    {
        return $query->where('scope', 'outbound');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
