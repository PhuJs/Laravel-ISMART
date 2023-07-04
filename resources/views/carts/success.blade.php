@extends('layouts.apps')
@section('title', 'Đặt hàng thành công')
@section('content')
    <div id="wapper" class="">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- ===== CONTENT ==== -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div id="wp_cart_success">
                                    <div id="cart_success">
                                        <h3 id="cart_success_icon">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </h3>
                                        <h4 class="cart_success_title">
                                            Đặt hàng thành công
                                        </h4>
                                        <p class="mb-2">
                                            Cảm ơn quý khách đã đăng kí mua hàng tại <span
                                                class="fw-bolder text-primary">ISMART</span>
                                        </p>
                                        <p class="mb-2">
                                            Bộ phận phụ trách sẽ chủ động liên hệ quý khách trong thời gian sớm nhất có thể.
                                            Mọi
                                            thắc mắc quý khách vui lòng gọi đến <span
                                                class="fw-semibold text-danger">Hotline: 1900 86 86 86</span>
                                        </p>
                                        <p>Xin chân thành cảm ơn!</p>
                                    </div>
                                    <div id="cart_success_infor" class="overflow-scroll">
                                        <h6>Thông tin đặt hàng</h6>
                                        <table class="table text-center table-hover">
                                            <thead class="">
                                                <th scope="col">Mã đơn hàng</th>
                                                <th scope="col">Tên khách hàng</th>
                                                <th scope="col">Địa chỉ</th>
                                                <th scope="col">Số điện thoại</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Số lượng</th>
                                                <th scope="col">Tổng tiền</th>
                                            </thead>
                                            <tbody>
                                                <tr class="align-middle">
                                                    <td>{{ $data_order['order_code'] }}</td>
                                                    <td>{{ $data_order['customer_name'] }}</td>
                                                    <td>{{ $data_order['address'] }}</td>
                                                    <td>{{ $data_order['phone'] }}</td>
                                                    <td>{{ $data_order['email'] }}</td>
                                                    <td>{{ $data_order['num_order'] }}</td>
                                                    <td>{{ $data_order['total_order'] }}đ</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h6 class="mt-4">Sản phẩm đặt mua</h6>
                                        <table class="table text-center table-hover">
                                            <thead>
                                                <th>Tên sản phẩm</th>
                                                <th>Hình ảnh sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                                <th>Tổng tiền</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($list_order as $order)
                                                    <tr class="align-middle">
                                                        <td>{{ $order->name }}</td>
                                                        <td>
                                                            <img src="{{ $order->options->thumbnail }}" alt=""
                                                                class="img-fluid img-thumbnail"
                                                                style="width:60px; height:auto;">
                                                        </td>
                                                        <td>{{ currency_format($order->price) }}</td>
                                                        <td>{{ $order->qty }}</td>
                                                        <td>{{ $order->subtotal() }}đ</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-center pt-2">Quý khách vui lòng kiểm tra lại Email để xác
                                        nhận thông tin đăng kí mua hàng. Xin
                                        cảm ơn!</p>
                                    <p class="text-center"><a href="?" class="text-primary text-decoration-none">Trở về trang chủ</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
