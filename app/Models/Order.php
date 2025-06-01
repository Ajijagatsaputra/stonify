<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'nama_depan', 'nama_belakang', 'alamat',
        'state_country', 'postal_zip', 'email', 'phone',
        'notes', 'payment_method', 'status', 'va_number', 'unique_code'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
