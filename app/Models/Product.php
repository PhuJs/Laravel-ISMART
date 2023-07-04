<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "products";
    protected $fillable = [
        'name',
        'product_code',
        'desc', 
        'price' ,
        'discount',
        'number_stock',
        'thumbnail',
        'product_detail',
        'status',
        'user_create',
        'tags',
        'cat_id',
    ];

    function cat(){
        $this->belongsTo(Product_cat::class);
    }
}
