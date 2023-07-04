<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product_cat extends Model
{
    use HasFactory;
    use softDeletes;
    protected $table = 'product_cats';
    protected $fillable = [
        'name',
        'user_create',
        'status',
        'parent_id',
    ];

    function products(){
        $this->hasMany(Product::class);
    }
}
