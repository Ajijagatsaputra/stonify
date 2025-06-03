<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    protected $table = 'orders';

    protected $fillable = [
        'user_id', 'total', 'status', 'payment_method', 'order_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function alamat()
    {
        return $this->hasOne(OrderAlamat::class);
    }

    public function midtrans()
    {
        return $this->hasOne(Midtrans::class, 'transaction_id', 'id');
    }
}
