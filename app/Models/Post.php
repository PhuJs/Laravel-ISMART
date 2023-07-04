<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'posts';
    protected $fillable = [
        'post_title',
        'post_desc',
        'post_cat', 
        'user_create',
        'post_content',
        'thumbnail',
        'status',
        'slug',
    ];
}
