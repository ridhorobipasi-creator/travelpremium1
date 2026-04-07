<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Province extends Model
{
    protected $fillable = ['name', 'description', 'image', 'scope'];

    public function scopeTour(Builder $query)
    {
        return $query->where('scope', 'tour');
    }

    public function scopeOutbound(Builder $query)
    {
        return $query->where('scope', 'outbound');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
