@extends('layouts.admin')
@section('title', 'Danh sách đơn hàng')
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

        <div class="list_post p-16">
            @if (session('alert'))
                <div class="alert alert-primary">
                    {{ session('alert') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header fw-semibold fs-5">
                    DANH SÁCH ĐƠN HÀNG
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="card-status">
                            @if (request()->input('keyword'))
                                <a href="" class="text-decoration-none text-warning" onclick="return false">
                                    Kết quả <span>({{ $list_order->total() }})</span></a>
                            @else
                                @php
                                    $status = isset($_GET['status']) ? $_GET['status'] : 'confirm';
                                @endphp
                                <a href="{{ route('admin.order.show', ['status' => 'confirm']) }}"
                                    class="text-decoration-none {{ $status == 'confirm' ? 'text-warning' : '' }}">Chờ xác
                                    nhận
                                    <span>({{ $total['confirm'] }})</span></a>|
                                <a href="{{ route('admin.order.show', ['status' => 'processing']) }}"
                                    class="text-decoration-none {{ $status == 'processing' ? 'text-warning' : '' }}">Đang
                                    xử
                                    lí
                                    <span>({{ $total['processing'] }})</span></a>|
                                <a href="{{ route('admin.order.show', ['status' => 'success']) }}"
                                    class="text-decoration-none {{ $status == 'success' ? 'text-warning' : '' }}">Hoàn
                                    thành
                                    <span>({{ $total['success'] }})</span></a>|
                                <a href="{{ route('admin.order.show', ['status' => 'cancel']) }}"
                                    class="text-decoration-none {{ $status == 'cancel' ? 'text-warning' : '' }}">Hủy
                                    <span>({{ $total['cancel'] }})</span></a>|
                                <a href="{{ route('admin.order.show', ['status' => 'error']) }}"
                                    class="text-decoration-none {{ $status == 'error' ? 'text-warning' : '' }}">Lỗi
                                    <span>({{ $total['error'] }})</span></a>|
                                <a href="{{ route('admin.order.show', ['status' => 'trash']) }}"
                                    class="text-decoration-none {{ $status == 'trash' ? 'text-warning' : '' }}">Thùng rác
                                    <span>({{ $total['trash'] }})</span></a>
                            @endif
                        </div>
                        <div class="form-search">
                            <form action="" method="GET">
                                <div class="d-flex align-items-center">
                                    <div class="form-group">
                                        <input type="text" class="form-control rounded-0" placeholder="Nhập từ khóa"
                                            name="keyword">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary rounded-0" value="Tìm kiếm">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('admin.order.action') }}" method="POST">
                        @csrf
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-3">
                                    <div class="d-flex align-items-center">
                                        <div class="form-group">
                                            <select class="form-select rounded-0" aria-label="Default select example"
                                                name="action">
                                                <option value="0">--- Chọn ---</option>
                                                @foreach ($list_act as $key => $act)
                                                    <option value="{{ $key }}">{{ $act }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary rounded-0" value="Áp dụng"
                                                name="sumit-form">

                                            @if (request()->input('keyword'))
                                                <input type="hidden" name="status"
                                                    value="{{ request()->input('keyword') }}">
                                            @else
                                                @if (request()->input('status'))
                                                    <input type="hidden" name="status"
                                                        value="{{ request()->input('status') }}">
                                                @else
                                                    <input type="hidden" name="status" value="confirm">
                                                @endif
                                            @endif
                                            {{-- Vị trí trang  --}}
                                            @if (request()->input('page'))
                                                <input type="hidden" name="page"
                                                    value="{{ request()->input('page') }}">
                                            @else
                                                <input type="hidden" name="page" value="1">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <!-- Trống -->
                                </div>
                            </div>
                        </div>

                        <div class="table_post mt-3">
                            <table class="table table-striped table-checkall">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th scope="col">
                                            <input type="checkbox" name="checkall" id="checkAll"
                                                aria-label="Input-checkAll">
                                        </th>
                                        <th scope="col">STT</th>
                                        <th scope="col">Mã đơn hàng</th>
                                        <th scope="col">Khách hàng</th>
                                        <th scope="col">Giá trị đơn hàng</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Thời Gian</th>
                                        <th scope="col">Chi tiết</th>
                                        <th scope="col">Tác Vụ</th>
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
                                                <td>
                                                    <input type="checkbox" name="list_check[]" aria-label="Checkitem"
                                                        value="{{ $order->id }}">
                                                </td>
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
                                                    @if (request()->input('status') == 'trash')
                                                        <a href="{{ route('admin.order.restore', ['id' => $order->id, 'page' => $page]) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Khôi phục"><i
                                                                class="fa-solid fa-trash-can-arrow-up"></i></a>
                                                        <a href="{{ route('admin.order.forceDelete', ['id' => $order->id, 'page' => $page]) }}"
                                                            class="btn btn-danger rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Xóa vĩnh viễn"
                                                            onclick="confirm('Bạn muốn xóa vĩnh viễn đơn hàng ra khỏi hệ thống')"><i
                                                                class="fa-solid fa-square-minus"></i></a>
                                                    @else
                                                        <a href="{{ route('admin.order.edit', ['id' => $order->id, 'status' => $status, 'page' => $page]) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Chỉnh sửa"><i
                                                                class="text-white fa-solid fa-pen-to-square"></i></a>
                                                        <a href="{{ route('admin.order.delete', ['id' => $order->id, 'status' => $status, 'page' => $page]) }}"
                                                            class="btn btn-danger rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Xóa tạm thời"><i
                                                                class="text-white fa-solid fa-trash"></i></a>
                                                    @endif
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
                        @if (request()->input('status'))
                            {{ $list_order->appends(['status' => request()->input('status')])->links('vendor.pagination.bootstrap-5') }}
                        @elseif(request()->input('keyword'))
                            {{ $list_order->appends(['keyword' => request()->input('keyword')])->links('vendor.pagination.bootstrap-5') }}
                        @else
                            {{ $list_order->links('vendor.pagination.bootstrap-5') }}
                        @endif
                    </form>
                    @if (request()->input('keyword'))
                        <a href="{{ route('admin.order.show') }}" class="btn btn-primary">Thoát tìm kiếm</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
