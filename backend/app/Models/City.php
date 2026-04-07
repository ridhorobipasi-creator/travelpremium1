<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class City extends Model
{
    protected $fillable = ['province_id', 'name', 'description', 'image', 'scope'];

    public function scopeTour(Builder $query)
    {
        return $query->where('scope', 'tour');
    }

    public function scopeOutbound(Builder $query)
    {
        return $query->where('scope', 'outbound');
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
