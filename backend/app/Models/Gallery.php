<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Gallery extends Model
{
    protected $fillable = ['title', 'image_path', 'category', 'scope'];

    public function scopeTour(Builder $query)
    {
        return $query->where('scope', 'tour');
    }

    public function scopeOutbound(Builder $query)
    {
        return $query->where('scope', 'outbound');
    }
}
