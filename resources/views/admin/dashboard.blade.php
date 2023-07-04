@extends('layouts.admin')

@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="business p-16">
            <div class="row">
                <div class="col-3">
                    <div class="card bg-primary text-white">
                        <div class="card-header">
                            ĐƠN HÀNG THÀNH CÔNG
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $total['success'] }}</h5>
                            <p class="card-text">Đơn hàng giao dịch thành công</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card bg-danger text-white">
                        <div class="card-header">
                            ĐANG XỬ LÝ
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $total['processing'] }}</h5>
                            <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card bg-success text-white">
                        <div class="card-header">
                            DOANH SỐ
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $total['sales'] }}</h5>
                            <p class="card-text">Doanh số hệ thống</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-header">
                            ĐƠN HÀNG HỦY
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $total['cancel'] }}</h5>
                            <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-16">
            @if (session('alert'))
            <div class="alert alert-warning">
                {{session('alert')}}
            </div>
            @endif
            <div class="card bg-white">
                <div class="card-header fs-5 fw-semibold">
                    ĐƠN HÀNG MỚI
                </div>
                <div class="card-body">
                    <div class="table_post mt-3">
                        <table class="table table-striped table-checkall">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th scope="col">STT</th>
                                    <th scope="col">Mã đơn hàng</th>
                                    <th scope="col">Khách hàng</th>
                                    <th scope="col">Giá trị đơn hàng</th>
                                    <th scope="col">Trạng Thái</th>
                                    <th scope="col">Thời Gian</th>
                                    <th scope="col">Chi tiết</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($list_order->total() > 0)
                                    @php
                                        if (request()->input('keyword')) {
                                            $status = request()->input('keyword');
                                        } else {
                                            $status = isset($_GET['status']) ? $_GET['status'] : 'confirm';
                                        }
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $stt = ($page - 1) * 15;
                                    @endphp
                                    @foreach ($list_order as $order)
                                        @php
                                            $stt++;
                                        @endphp
                                        <tr class="vertical-center text-center">
                                            <td scope="row">{{ $stt }}</td>
                                            <td>
                                                <a href="{{ route('admin.order.detail', ['id' => $order->id, 'status' => $status, 'page' => $page]) }}"
                                                    class="text-decoration-none">
                                                    {{ $order->order_code }}
                                                </a>
                                            </td>
                                            <td>
                                                <span>{{ $order->customer_name }}</span>
                                                <hr class="m-0">
                                                <span>{{ $order->phone }}</span>
                                            </td>
                                            <td>
                                                <span>{{ $order->total_order }}đ</span>
                                                <hr class="m-0">
                                                <span>{{ $order->num_order }} sản phẩm</span>
                                            </td>
                                            <td>
                                                @if (request()->input('status') == 'trash')
                                                    <span class="badge bg-secondary">Vô hiệu hóa</span>
                                                @else
                                                    @if ($order->status == 2)
                                                        <span class="badge bg-danger">Đang xử lí</span>
                                                    @elseif($order->status == 3)
                                                        <span class="badge bg-success">Hoàn thành</span>
                                                    @elseif($order->status == 4)
                                                        <span class="badge bg-secondary">Đơn hàng hủy</span>
                                                    @elseif($order->status == 5)
                                                        <span class="badge bg-secondary">Đơn hàng lỗi</span>
                                                    @else
                                                        <span class="badge bg-primary">Chờ xác nhận</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span>{{ $order->created_at }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.order.detail', ['id' => $order->id, 'status' => $status, 'page' => $page]) }}"
                                                    class="text-decoration-none">Chi tiết</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('dashboard.order.confirm', $order->id) }}" class="text-decoration-none">Xác nhận</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            <span class="text-danger">Không có đơn hàng tồn tại</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $list_order->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
