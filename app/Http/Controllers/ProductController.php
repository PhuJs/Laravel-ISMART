<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Models\Product_cat;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


class ProductController extends Controller
{

    /**
     * Hiển thị danh sách sản phẩm của từng danh mục 
     * - Dựa vào Id danh mục cha 
     * - Tìm tất cả id danh mục con
     * - Lấy dữ liệu theo chuỗi danh mục
     * 
     */

    function list_product(Request $request, $name, $id)
    {

        $product_cat = Product_cat::find($id);
        $cat_name = $product_cat->name;

        $list_product_cat = Product_cat::where('status', 2)->get();
        $string_id = data_tree_id($list_product_cat, $id);
        $string_id = explode(',', $string_id);

        $total = Product::where([
            ['status', 2],
            ['number_stock', '>', 0],
        ])->whereIn('cat_id', $string_id)->count();

        if ($request->input('price')) {
            $value = $request->input('price');
            if ($value == 1) {
                $list_product = Product::where([
                    ['status', 2],
                    ['number_stock', '>', 0],
                    ['price', '<=', 3000000],
                ])->whereIn('cat_id', $string_id)->paginate(15);
            } elseif ($value == 2) {
                $list_product = Product::where([
                    ['status', 2],
                    ['number_stock', '>', 0],
                    ['price', '>=', 3000000],
                    ['price', '<=', 8000000],
                ])->whereIn('cat_id', $string_id)->paginate(15);
            } elseif ($value == 3) {
                $list_product = Product::where([
                    ['status', 2],
                    ['number_stock', '>', 0],
                    ['price', '>=', 8000000],
                    ['price', '<=', 15000000],
                ])->whereIn('cat_id', $string_id)->paginate(15);
            } else {
                $list_product = Product::where([
                    ['status', 2],
                    ['number_stock', '>', 0],
                    ['price', '>=', 15000000],
                ])->whereIn('cat_id', $string_id)->paginate(15);
            }
        } elseif ($request->input('action')) {
            $action = $request->input('action');
            if ($action == 'az') {
                $list_product = Product::where([
                    ['status', 2],
                    ['number_stock', '>', 0],
                ])->whereIn('cat_id', $string_id)->orderBy('name', 'asc')->paginate(15);
            } elseif ($action == 'za') {
                $list_product = Product::where([
                    ['status', 2],
                    ['number_stock', '>', 0],
                ])->whereIn('cat_id', $string_id)->orderBy('name', 'desc')->paginate(15);
            } elseif ($action == 'maxmin') {
                $list_product = Product::where([
                    ['status', 2],
                    ['number_stock', '>', 0],
                ])->whereIn('cat_id', $string_id)->orderBy('price', 'desc')->paginate(15);
            } elseif ($action == 'minmax') {
                $list_product = Product::where([
                    ['status', 2],
                    ['number_stock', '>', 0],
                ])->whereIn('cat_id', $string_id)->orderBy('price', 'asc')->paginate(15);
            }
        } else {
            $list_product = Product::where([
                ['status', 2],
                ['number_stock', '>', 0],
            ])->whereIn('cat_id', $string_id)->paginate(15);
        }

        return view('products.list', compact('list_product_cat', 'cat_name', 'list_product', 'total'));
    }


    /**
     * Chi tiết sản phẩm 
     * - Lấy đối tượng sản phẩm theo ID
     * - Lấy danh sách hình ảnh chi tiết sản phẩm
     * - Lấy danh sách sản phẩm cùng chuyên mục
     * 
     */
    function detail($name, $id)
    {
        $list_product_cat = Product_cat::where('status', 2)->get();
        $product = Product::find($id);
        $string_id = data_tree_id($list_product_cat, $product->cat_id);
        $string_id = explode(',', $string_id);
        $list_product = Product::where([
            ['status', 2],
            ['number_stock', '>', 0],
        ])->whereIn('cat_id', $string_id)->limit(6)->get();
        $list_image = Image::where('product_id', $id)->get();
        return view('products.detail', compact('list_product_cat', 'product', 'list_image', 'list_product'));
    }
}
