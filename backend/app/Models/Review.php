<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    protected $fillable = [
        'package_id', 'user_id', 'customer_name', 
        'rating', 'comment', 'is_approved', 'scope'
    ];

    public function scopeTour(Builder $query)
    {
        return $query->where('scope', 'tour');
    }

    public function scopeOutbound(Builder $query)
    {
        return $query->where('scope', 'outbound');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
