<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAlamat extends Model
{
    use SoftDeletes;

    protected $table = 'order_alamats';
    protected $fillable = [
        'order_id', 'first_name', 'last_name', 'address_line1', 'address_line2', 'state_country', 'postal_code', 'email', 'phone'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


}
