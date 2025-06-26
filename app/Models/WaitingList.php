<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model
{
    protected $fillable = [
        'customer_id', 
        'guests',
        'from_time', 
        'to_time'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
