<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['province_id', 'name', 'description', 'image'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
