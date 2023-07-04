<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product_cat;
use Illuminate\Http\Request;


class PageController extends Controller
{
    /**
     * Hiển thị nội dung trang 
     * 
     */
    function show($slug){
        $list_product_cat = Product_cat::where('status', 2)->get();
        $page = Page::where('url', 'LIKE', "{$slug}")->first();
        return view('pages.show', compact('list_product_cat', 'page'));
    }
}
