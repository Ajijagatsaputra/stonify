<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Midtrans extends Model
{
    use SoftDeletes;

    protected $table = 'midtrans';
    protected $fillable = [
        'transaction_id',
        'order_id',
        'snap_token',
        'payment_type',
        'transaction_status',
        'transaction_time',
        'gross_amount',
        'bank',
        'va_number',
        'pdf_url',
        'finish_redirect_url',
        'unfinish_redirect_url',
        'error_redirect_url'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'transaction_id', 'id');
    }
}
