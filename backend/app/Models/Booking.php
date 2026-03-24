<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'type', 'item_id', 'start_date', 'end_date',
        'status', 'total_price', 'customer_name', 'customer_phone', 'customer_email', 'notes'
    ];

    protected $casts = [
        'total_price' => 'float',
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the associated item (Package or Car)
     */
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
