<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrdersController extends Controller
{

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    /**
     * ------------------------------
     * THỐNG KÊ SỐ LƯỢNG CÁC ĐƠN HÀNG 
     * - Số lượng đơn hàng thành công
     * 
     */
    function total_orders_success()
    {
        $order_success = Order::where('status', 3)->count();
        return $order_success;
    }

    /**
     * - Số lượng đơn hàng chờ xác nhận
     * --- confirm ---
     */
    function total_orders_confirm()
    {
        $order_confirm = Order::where('status', 1)->count();
        return $order_confirm;
    }

    /**
     * - Số lượng đơn hàng đang xử lí 
     * --- processing ---
     */
    function total_orders_processing()
    {
        $order_processing = Order::where('status', 2)->count();
        return $order_processing;
    }

    /**
     * Doanh số 
     * - Tổng giá trị các đơn hàng thành công
     * --- sales ---
     */
    function sales()
    {
        $sales = 0;
        $list_order_success = Order::where('status', 3)->get();
        foreach ($list_order_success as $order) {
            $total = str_replace('.', '', $order->total_order);
            $total = intval($total);
            $sales += $total;
        }
        return currency_format($sales);
    }

    /**
     * Số lượng đơn hàng bị hủy
     * --- cancel ---
     */
    function total_orders_cancel()
    {
        $order_cancel = Order::where('status', 4)->count();
        return $order_cancel;
    }

    /**
     * Số lượng đơn hàng bị lỗi 
     * --- error ---
     */
    function total_orders_error()
    {
        $order_error = Order::where('status', 5)->count();
        return $order_error;
    }

    /**
     * Số lượng đơn hàng trong thùng rác
     * --- trash ---
     */
    function total_orders_trash()
    {
        $order_trash = Order::onlyTrashed()->count();
        return $order_trash;
    }


    /**
     * -----------------------------
     */


    /**
     * Hiển thị danh sách đơn hàng
     * 
     */
    function show(Request $request)
    {
        /**
         * Thống kê 
         */
        $total = [];
        $total['success'] = $this->total_orders_success();
        $total['confirm'] = $this->total_orders_confirm();
        $total['processing'] = $this->total_orders_processing();
        $total['cancel'] = $this->total_orders_cancel();
        $total['error'] = $this->total_orders_error();
        $total['trash'] = $this->total_orders_trash();
        $total['sales'] = $this->sales();

        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
            $list_order = Order::where('customer_name', 'LIKE', "%{$keyword}%")
                ->orWhere('address', 'LIKE', "%{$keyword}%")
                ->orWhere('order_code', 'LIKE', "{$keyword}")
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            $list_act = [
                'confirm' => 'Xác nhận',
                'success' => 'Hoàn thành',
                'cancel' => 'Hủy đơn hàng',
                'error' => 'Đơn hàng lỗi',
                'trash' => 'Vô hiệu hóa'
            ];
        } else {
            if ($request->input('status') == 'processing') {
                $list_order = Order::where('status', 2)->orderBy('created_at', 'desc')->paginate(15);
                $list_act = [
                    'success' => 'Hoàn thành',
                    'cancel' => 'Hủy đơn hàng',
                    'error' => 'Đơn hàng lỗi',
                    'trash' => 'Vô hiệu hóa'
                ];
            } elseif ($request->input('status') == 'success') {
                $list_order = Order::where('status', 3)->orderBy('created_at', 'desc')->paginate(15);
                $list_act = [
                    'trash' => 'Vô hiệu hóa'
                ];
            } elseif ($request->input('status') == 'cancel') {
                $list_order = Order::where('status', 4)->orderBy('created_at', 'desc')->paginate(15);
                $list_act = [
                    'trash' => 'Vô hiệu hóa'
                ];
            } elseif ($request->input('status') == 'error') {
                $list_order = Order::where('status', 5)->orderBy('created_at', 'desc')->paginate(15);
                $list_act = [
                    'trash' => 'Vô hiệu hóa'
                ];
            } elseif ($request->input('status') == 'trash') {
                $list_order = Order::onlyTrashed()->orderBy('created_at', 'desc')->paginate(15);
                $list_act = [
                    'restore' => 'Khôi phục',
                    'forceDelete' => 'Xóa vĩnh viễn',
                ];
            } else {
                $list_order = Order::where('status', 1)->orderBy('created_at', 'desc')->paginate(15);
                $list_act = [
                    'confirm' => 'Xác nhận',
                    'cancel' => 'Hủy đơn hàng',
                    'error' => 'Đơn hàng lỗi',
                    'trash' => 'Vô hiệu hóa'
                ];
            }
        }

        return view('admin.orders.show', compact('total', 'list_order', 'list_act'));
    }

    /**
     * Chi tiết đơn hàng
     * - Lấy dữ liệu đơn hàng theo ID 
     * - Lấy dữ liệu chi tiết đơn hàng 
     * 
     * 
     */
    function order_detail(Request $request, $id)
    {
        $list_status = [
            'confirm',
            'processing',
            'success',
            'cancel',
            'error',
            'trash',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }

        $order = Order::withTrashed()->find($id);
        $list_order_detail = Order_detail::join('products', 'order_details.product_id', '=', 'products.id')
            ->select('order_details.*', 'products.name', 'products.price', 'products.thumbnail')
            ->where('order_details.order_id', '=', $id)->get();

        return view('admin.orders.detail', compact('order', 'list_order_detail', 'data_redirect'));
    }

    /**
     * Cập nhật đơn hàng 
     * - Gọi view 
     * 
     */
    function edit(Request $request, $id)
    {
        $status = $request->input('status');
        $page = $request->input('page');
        $order = Order::find($id);
        return view('admin.orders.edit', compact('order', 'status', 'page'));
    }

    /**
     * Cập nhật đơn hàng 
     * - Xử lí dữ liệu 
     * 
     */
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'customer_name' => ['required'],
                'phone_number' => ['required', 'regex:/^(07|09|03|08|02|01[2,4,6,8,9])+([0-9]){8}$/'],
                'address' => ['required'],
            ],
            [
                'customer_name.required' => 'Không được để trống tên khách hàng',
                'phone_number.required' => 'Số điện thoại khách hàng không được để trống',
                'phone_number.regex' => 'Số điện thoại chưa đúng định dạng',
                'address.required' => 'Nhập địa chỉ khách hàng đầy đủ',
            ]
        );

        $list_status = [
            'confirm',
            'processing',
            'success',
            'cancel',
            'error',
            'trash',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }

        $data_update = [
            'customer_name' => $request->input('customer_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone_number'),
            'address' => $request->input('address'),
            'note' => $request->input('note'),
        ];

        Order::where('id', $id)->update($data_update);
        return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Cập nhật thành công');
    }

    /**
     * Cập nhật trạng thái đơn hàng
     * 
     */
    function update_status(Request $request, $id)
    {
        $list_status = [
            'confirm',
            'processing',
            'success',
            'cancel',
            'error',
            'trash',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }

        if ($request->input('status_order') == 1) {
            if (Order::withTrashed()->find($id)->exists) {
                Order::withTrashed()->find($id)->restore();
            }
            Order::withTrashed()->find($id)->update([
                'status' => 1,
            ]);
        } elseif ($request->input('status_order') == 2) {
            if (Order::withTrashed()->find($id)->exists) {
                Order::withTrashed()->find($id)->restore();
            }
            Order::withTrashed()->find($id)->update([
                'status' => 2,
            ]);
        } elseif ($request->input('status_order') == 3) {
            if (Order::withTrashed()->find($id)->exists) {
                Order::withTrashed()->find($id)->restore();
            }
            Order::withTrashed()->find($id)->update([
                'status' => 3,
            ]);
        } elseif ($request->input('status_order') == 4) {
            if (Order::withTrashed()->find($id)->exists) {
                Order::withTrashed()->find($id)->restore();
            }
            Order::withTrashed()->find($id)->update([
                'status' => 4,
            ]);
        } elseif ($request->input('status_order') == 5) {
            if (Order::withTrashed()->find($id)->exists) {
                Order::withTrashed()->find($id)->restore();
            }
            Order::withTrashed()->find($id)->update([
                'status' => 5,
            ]);
        } else {
            Order::destroy($id);
        }

        return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Cập nhật trạng thái thành công');
    }

    /**
     * Xóa đơn hàng tạm thời
     * 
     */
    function delete(Request $request, $id)
    {

        $list_status = [
            'confirm',
            'processing',
            'success',
            'cancel',
            'error',
            'trash',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }

        Order::destroy($id);
        return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Đã xóa tạm thời đơn hàng');
    }

    /**
     * Khôi phục đơn hàng
     * 
     */
    function restore(Request $request, $id)
    {
        $page = $request->input('page');
        Order::onlyTrashed()->where('id', $id)->restore();
        Order::where('id', $id)->update([
            'status' => 1,
        ]);
        return redirect()->route('admin.order.show', ['status' => 'trash', 'page' => $page])->with('alert', 'Khôi phục đơn hàng thành công');
    }

    /**
     * Xóa vĩnh viễn đơn hàng 
     * 
     */
    function force_delete(Request $request, $id)
    {
        $page = $request->input('page');
        Order::onlyTrashed()->where('id', $id)->forceDelete();
        return redirect()->route('admin.order.show', ['status' => 'trash', 'page' => $page])->with('alert', 'Đã xóa đơn hàng ra khỏi hệ thống');
    }


    /**
     * Xử lí tác vụ với danh sách đơn hàng 
     * 
     */
    function action_order(Request $request)
    {
        $list_status = [
            'confirm',
            'processing',
            'success',
            'cancel',
            'error',
            'trash',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }

        $list_check = $request->input('list_check');

        if (!empty($list_check)) {
            if ($request->input('action') == 'confirm') {
                foreach ($list_check as $item) {
                    Order::where('id', $item)->update([
                        'status' => 2
                    ]);
                };
                return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Các đơn hàng đã được xác nhận');
            } elseif ($request->input('action') == 'success') {
                foreach ($list_check as $item) {
                    Order::where('id', $item)->update([
                        'status' => 3
                    ]);
                }
                return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Các đơn hàng đã được cập nhật hoàn thành');
            } elseif ($request->input('action') == 'cancel') {
                foreach ($list_check as $item) {
                    Order::where('id', $item)->update([
                        'status' => 4
                    ]);
                }
                return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Các đơn hàng đã được cập nhật hủy');
            } elseif ($request->input('action') == 'error') {
                foreach ($list_check as $item) {
                    Order::where('id', $item)->update([
                        'status' => 5
                    ]);
                }
                return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Các đơn hàng đã được cập nhật bị lỗi ');
            } elseif ($request->input('action') == 'trash') {
                foreach ($list_check as $item) {
                    Order::destroy($item);
                }
                return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Các đơn hàng đã được cho vào thùng rác');
            } elseif ($request->input('action') == 'restore') {
                foreach ($list_check as $item) {
                    Order::onlyTrashed()->where('id', $item)->restore();
                    Order::where('id', $item)->update([
                        'status' => 1,
                    ]);
                }
                return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Các đơn hàng đã được khôi phục');
            } elseif ($request->input('action') == 'forceDelete') {
                foreach ($list_check as $item) {
                    Order::onlyTrashed()->where('id', $item)->forceDelete();
                }
                return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Các đơn hàng đã được xóa vĩnh viễn');
            } else {
                return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Chọn tác vụ cụ thể để thực hiện');
            }
        } else {
            return redirect()->route('admin.order.show', $data_redirect)->with('alert', 'Chọn đơn hàng cụ thể để thực hiện tác vụ');
        }
    }
}
