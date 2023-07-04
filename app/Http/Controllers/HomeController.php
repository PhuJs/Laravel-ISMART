<?php

namespace App\Http\Controllers;

use App\Models\Product_cat;
use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */



    /**
     * Đỗ dữ liệu lên trang chủ 
     * 1. Danh mục sản phẩm 
     * 
     */
    public function index()
    {
        $list_product_cat = Product_cat::where('status', 2)->get()->toArray();
        $list_product_cat_main = Product_cat::where([
            ['status', 2],
            ['parent_id', 0]
        ])->get()->toArray();

        $list_product = [];
        /**
         * Lặp qua từng danh mục 
         * Từ id của danh mục đó tìm ra các chuỗi id các danh mục là con của nó
         * Lấy dữ liệu lưu vào Mảng với key là tên danh mục
         */
        foreach ($list_product_cat_main as $item) {
            $string_id = data_tree_id($list_product_cat, $item['id']);
            $string = explode(',', $string_id);
            $list_product[$item['name']] = Product::where([
                ['status', 2],
                ['number_stock', '>', 0]
            ])
                ->whereIn('cat_id', $string)
                ->limit(8)->get()->toArray();
        }

        $keyword = "Nổi bật, bán chạy";
        $list_product_hot = Product::where([
            ['status', '2'],
            ['number_stock', '>', 0],
            ['tags', 'LIKE', "%{$keyword}%"],
        ])->limit(5)->get();
        return view('home', compact('list_product_cat', 'list_product_hot', 'list_product'));
    }
}


/**
 * - Task Module Home: (Nhiệm vụ của module home);
 * + Trang đầu tiên khi vào dự án
 * + Hiển thị các nội dung của trang chủ
 * + Chứa điều hướng vào các khu vực khác của trang web.
 * ----------------------
 * - Công việc tiếp theo
 * + Gọi view - xử lí hiển thị giao diện
 * + Đổ dữ liệu
 * + Xử lí điều hướng.
 */


/**
 * Xử lí module Product hiển thị danh sách sản phẩm theo danh mục 
 * - Tạo Controller xử lí 
 * - Xây dựng Database 
 * - Tạo View 
 * - Xây dựng từng chức năng hiển thị 
 * - 
 */
