<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product_cat;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Hiển thị danh sách bài viết công nghệ 
     * 
     */
    function show(){
        $list_product_cat = Product_cat::where('status', 2)->get();
        $list_post = Post::where('status', 2)->paginate(8);
        return view('posts.show', compact('list_product_cat', 'list_post'));
    }

    /**
     * Hiển thị chi tiết bài viết 
     * 
     */
    function post_detail($slug){
        $post = Post::where('slug', 'LIKE', "{$slug}")->first();
        $list_product_cat = Product_cat::where('status', 2)->get();
        return view('posts.detail', compact('list_product_cat', 'post'));
    }
}
