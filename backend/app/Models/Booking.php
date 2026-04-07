<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'type', 'item_id', 'start_date', 'end_date', 'scope',
        'status', 'total_price', 'customer_name', 'customer_phone', 'customer_email', 'notes'
    ];

    protected $casts = [
        'total_price' => 'float',
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];

    /**
     * Priority 1: Multi-Context Isolation Scopes
     */
    public function scopeTour(Builder $query)
    {
        return $query->where('scope', 'tour');
    }

    public function scopeOutbound(Builder $query)
    {
        return $query->where('scope', 'outbound');
    }

    /**
     * Virtual Attribute for Frontend (Priority 3)
     */
    public function getItemNameAttribute()
    {
        if ($this->type === 'package') {
            return Package::find($this->item_id)?->name ?? 'Paket Tidak Tersedia';
        } elseif ($this->type === 'car') {
            return Car::find($this->item_id)?->name ?? 'Mobil Tidak Tersedia';
        }
        return 'Custom Order';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        if ($this->type === 'package') {
            return $this->belongsTo(Package::class, 'item_id');
        } elseif ($this->type === 'car') {
            return $this->belongsTo(Car::class, 'item_id');
        }
        return null;
    }
}
