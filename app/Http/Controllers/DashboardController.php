<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;




class DashboardController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
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
     * - Số lượng đơn hàng đang xử lí 
     * 
     */
    function total_orders_processing()
    {
        $order_processing = Order::where('status', 2)->count();
        return $order_processing;
    }

    /**
     * Doanh số 
     * - Tổng giá trị các đơn hàng thành công
     * 
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
     * 
     */
    function total_orders_cancel()
    {
        $order_cancel = Order::where('status', 4)->count();
        return $order_cancel;
    }


    function show()
    {
        $total = [];
        $total['success'] = $this->total_orders_success();
        $total['processing'] = $this->total_orders_processing();
        $total['sales'] = $this->sales();
        $total['cancel'] = $this->total_orders_cancel();

        $list_order = Order::where('status', 1)->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.dashboard', compact('total', 'list_order'));

        // $threeDaysAgo = Carbon::now()->subDays(3);
        // $list_order = Order::where('created_at', '>=', $threeDaysAgo)->get()->toArray();
    }

    /**
     * Xác nhận đơn hàng
     * 
     */
    function confirm($id){
        Order::where('id', $id)->update([
            'status' => 2
        ]);
        return redirect('dashboard')->with('alert', 'Xác nhận đơn hàng thành công');
    }
}
