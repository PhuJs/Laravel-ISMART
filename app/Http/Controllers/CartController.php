<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_detail as OrderDetail;
use App\Models\Product;
use App\Models\Product_cat;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\OrderMail;



class CartController extends Controller
{
    /**
     * Thêm sản phẩm vào giỏ hàng
     * - Lấy sản phẩm theo ID 
     * - Thêm vào giỏ hàng với Cart Object 
     * 
     */
    function add_cart($name, $id)
    {
        $product = Product::find($id);
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'options' => [
                'thumbnail' => $product->thumbnail,
                'code' => $product->product_code,
            ],
        ]);
        return redirect()->route('cart.show');
    }

    /**
     * Thêm sản phẩm với Ajax
     * 
     */
    function add_cart_ajax(Request $request)
    {
        $id = $request->input('productId');
        $product = Product::find($id);
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'options' => [
                'thumbnail' => $product->thumbnail,
                'code' => $product->product_code,
            ],
        ]);
        return 1;
    }


    /**
     * Danh sách trong giỏ hàng
     * 
     */
    function show_cart()
    {
        $list_product_cat = Product_cat::where('status', 2)->get();
        return view('carts.show', compact('list_product_cat'));
    }

    /**
     * Xóa sản phẩm trong giỏ hàng
     * 
     */
    function delete_cart($rowId)
    {
        Cart::remove($rowId);
        return redirect()->route('cart.show');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng 
     * - Dữ liệu trả về:
     * + Tổng tiền sản phẩm
     * + Tổng tiền giỏ hàng
     */
    function update_cart(Request $request)
    {
        $rowId = $request->input('rowId');
        $num_order = $request->input('num_order');
        Cart::update($rowId, $num_order);
        $product = Cart::get($rowId);

        $sub_total = $product->subtotal() . "đ";
        $total = Cart::total() . "đ";
        $countCart = Cart::count() . " sản phẩm ";
        $data = [
            'totalPrice' => $sub_total,
            'totalCart' => $total,
            'countCart' => $countCart,
            'total' => Cart::count(),
        ];
        return response()->json($data);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     * 
     */
    function delete_all()
    {
        Cart::destroy();
        return redirect()->route('cart.show');
    }

    /**
     * Thêm sản phẩm với số lượng nhiều
     * 
     */
    function add_carts(Request $request, $id)
    {
        $product = Product::find($id);
        $qty = $request->input('num-order');
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->price,
            'options' => [
                'thumbnail' => $product->thumbnail,
                'code' => $product->product_code,
            ],
        ]);
        return redirect()->route('cart.show');
    }

    /**
     * Thanh toán đơn hàng
     * - Hiển thị giao diện 
     */
    function payment()
    {
        $list_product_cat = Product_cat::where('status', 2)->get();
        $list_province = DB::table('tbl_province')->get();
        return view('carts.payment', compact('list_product_cat', 'list_province'));
    }

    /**
     * Thanh toán đơn hàng
     * - Xử lí thêm dữ liệu 
     * 
     */
    function payment_cart(Request $request)
    {
        $request->validate(
            [
                'customer_name' => ['required'],
                'email' => ['nullable', 'email'],
                'phone_number' => ['required', 'regex:/^(07|09|08|02|01[2,6,8,4])+([0-9]){8}$/'],
                'province' => [Rule::notIn(['0'])],
                'district' => [Rule::notIn(['0'])],
                'wards' => [Rule::notIn(['0'])],
                'address' => ['required'],
            ],
            [
                'required' => 'Vui lòng nhập :attribute',
                'email.email' => 'Vui lòng nhập đúng định dạng Email',
                'phone_number.regex' => 'Số điện thoại không hợp lệ',
                'province.not_in' => 'Chọn Tỉnh Thành Phố cụ thể',
                'district.not_in' => 'Chọn Quận Huyện cụ thể',
                'wards.not_in' => 'Chọn Phường Xã cụ thể',
            ],
            [
                'customer_name' => 'tên khách hàng',
                'address' => 'địa chỉ khách hàng',
                'phone_number' => 'số điện thoại khách hàng'
            ]
        );

        $province = DB::table('tbl_province')->where('province_id', $request->input('province'))->first();
        $district = DB::table('tbl_district')->where('district_id', $request->input('district'))->first();
        $wards = DB::table('tbl_wards')->where('wards_id', $request->input('wards'))->first();
        $address = $request->input('address');
        $address_customer = $address . ", " . $wards->name . ", " . $district->name . ", " . $province->name;

        // Dữ liệu đơn hàng. 
        $data_order = [
            'order_code' => '#ISMART' . time(),
            'customer_name' => $request->input('customer_name'),
            'email' => $request->input('email'),
            'address' => $address_customer,
            'phone' => $request->input('phone_number'),
            'note' => $request->input('note'),
            'total_order' => Cart::total(),
            'num_order' => Cart::count(),
            'payment' => $request->input('payment'),
        ];

        $order = Order::create($data_order);

        // Thêm dữ liệu chi tiết đơn hàng 
        foreach (Cart::content() as $row) {
            $data_order_detail = [
                'order_id' => $order->id,
                'product_id' => $row->id,
                'qty' => $row->qty,
                'sub_total' => $row->subtotal(),
            ];
            OrderDetail::create($data_order_detail);
        }

        /**
         * Khi đặt hàng thành công tiến hành xóa giỏ hàng và trở về khu vực thông báo
         * Cart::destroy();
         */

        $list_order = Cart::content();
        Cart::destroy();

        /**
         * Kiểm tra gửi mail 
         * 
         */
        if (!empty($request->input('email'))) {
            Mail::to($request->input('email'))->send(new OrderMail($data_order, $list_order));
        }

        $list_product_cat = Product_cat::where('status', 2)->get();
        return view('carts.success', compact('list_product_cat', 'data_order', 'list_order'));
    }

    /**
     * Lấy danh sách Quận Huyện
     * 
     */
    function get_district(Request $request)
    {
        $province_id = $request->input('province_id');
        $list_district = DB::table('tbl_district')->where('province_id', $province_id)->get();
        $data[0] = [
            'id' => 0,
            'name' => 'Chọn Quận Huyện'
        ];
        foreach ($list_district as $item) {
            $data[] = [
                'id' => $item->district_id,
                'name' => $item->name,
            ];
        }
        return response()->json($data);
    }

    /**
     * Lấy danh sách Phường Xã
     * 
     */
    function get_wards(Request $request)
    {
        $district_id = $request->input('district_id');
        $list_wards = DB::table('tbl_wards')->where('district_id', $district_id)->get();
        $data[0] = [
            'id' => 0,
            'name' => 'Chọn Phường Xã',
        ];
        foreach ($list_wards as $wards) {
            $data[] = [
                'id' => $wards->wards_id,
                'name' => $wards->name,
            ];
        }

        return response()->json($data);
    }


    /**
     * Cách chuyển chuỗi thành số 
     */
    // function get_total_order(){
    //     $total = 0;
    //     $list_order = Order::all();
    //     foreach($list_order as $order){
    //         $totalString = str_replace('.', '', $order->total_order);
    //         $totalString = intval($totalString);
    //         $total += $totalString; 
    //     }
    //     return $total;
    // }
}
