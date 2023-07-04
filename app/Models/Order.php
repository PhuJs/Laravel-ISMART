<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'orders';
    protected $fillable = [
        'order_code',
        'customer_name',
        'email',
        'address',
        'phone',
        'note',
        'total_order',
        'num_order',
        'payment',
        'status'
    ];
}
