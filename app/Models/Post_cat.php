<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post_cat extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'post_cats';
    protected $fillable = ['cat_name', 'desc', 'status', 'parent_id', 'user_create'];
}
