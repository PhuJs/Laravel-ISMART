@extends('layouts.admin')
@section('title', 'Danh sách sản phẩm')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="list_post p-16">
            <div class="card">
                @if (session('alert'))
                    <div class="alert alert-primary">
                        {{ session('alert') }}
                    </div>
                @endif
                <div class="card-header fw-semibold fs-5">
                    DANH SÁCH SẢN PHẨM
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="card-status">
                            @if (request()->input('keyword'))
                                <a href="" onclick="return false" class="text-decoration-none text-warning">Kết quả
                                    <span>({{ $count[0] }})</span> </a>
                            @else
                                <a href="{{ route('admin.product.list', ['status' => 'active']) }}"
                                    class="text-decoration-none {{ (request()->input('status') == 'active' or empty(request()->input('status'))) ? 'text-warning' : '' }}">Đã
                                    đăng<span>({{ $count[0] }})</span></a>|
                                <a href="{{ route('admin.product.list', ['status' => 'approval']) }}"
                                    class="text-decoration-none {{ request()->input('status') == 'approval' ? 'text-warning' : '' }}">Chờ
                                    duyệt<span>({{ $count[1] }})</span></a>|
                                <a href="{{ route('admin.product.list', ['status' => 'empty']) }}"
                                    class="text-decoration-none {{ request()->input('status') == 'empty' ? 'text-warning' : '' }}">Hết
                                    hàng<span>({{ $count[2] }})</span></a>|
                                <a href="{{ route('admin.product.list', ['status' => 'trash']) }}"
                                    class="text-decoration-none {{ request()->input('status') == 'trash' ? 'text-warning' : '' }}">Vô
                                    hiệu hóa<span>({{ $count[3] }})</span></a>
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
                    <form action="{{ route('admin.product.action') }}" method="POST">
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
                                                    <input type="hidden" name="status" value="active">
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
                                        <th scope="col">Ảnh</th>
                                        <th scope="col">Tên & Mã Sản Phẩm</th>
                                        <th scope="col">Danh Mục</th>
                                        <th scope="col">Giá & Số Lượng</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Người Tạo & Thời Gian</th>
                                        <th scope="col">Tác Vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $stt = ($page - 1) * 15;
                                    @endphp
                                    @if ($list_product->total() > 0)
                                        @foreach ($list_product as $product)
                                            @php
                                                $stt++;
                                            @endphp
                                            <tr class="vertical-center text-center">
                                                <td>
                                                    <input type="checkbox" name="list_check[]" aria-label="Checkitem"
                                                        value="{{ $product->id }}">
                                                </td>
                                                <td scope="row">{{ $stt }}</td>
                                                <td>
                                                    <a href="" onclick="return false">
                                                        <img src="{{ $product->thumbnail }}" alt=""
                                                            class="img-fluid img-thumbnail"
                                                            style="width:75px; padding:6px;">
                                                    </a>
                                                </td>

                                                <td>
                                                    @php
                                                        if (request()->input('keyword')) {
                                                            $status = request()->input('keyword');
                                                        } else {
                                                            if (request()->input('status')) {
                                                                $status = request()->input('status');
                                                            } else {
                                                                $status = 'active';
                                                            }
                                                        }
                                                        
                                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                                    @endphp
                                                    <a href="{{ route('admin.product.product_detail', ['id' => $product->id, 'status' => $status, 'page' => $page]) }}"
                                                        class="text-decoration-none">
                                                        <span>{{ $product->name }}</span>
                                                        <hr class="m-0">
                                                        <span>{{ $product->product_code }}</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="">{{ $product->cat_name }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ number_format($product->price, 0, ',', '.') . 'đ' }}</span>
                                                    <hr class="m-0">
                                                    <span>{{ $product->number_stock }} sản phẩm</span>
                                                </td>
                                                <td>
                                                    @if (request()->input('keyword'))
                                                        @if ($product->status == 1 && $product->number_stock > 0)
                                                            <span class="badge bg-warning">Chờ duyệt</span>
                                                        @elseif($product->status == 2 && $product->number_stock > 0)
                                                            <span class="badge bg-success">Đã đăng</span>
                                                        @else
                                                            <span class="badge bg-danger">Hết hàng</span>
                                                        @endif
                                                    @else
                                                        @if (request()->input('status') == 'approval')
                                                            <span class="badge bg-warning">Chờ duyệt</span>
                                                        @elseif(request()->input('status') == 'empty')
                                                            <span class="badge bg-danger">Hết hàng</span>
                                                        @elseif(request()->input('status') == 'trash')
                                                            <span class="badge bg-secondary">Xóa tạm thời</span>
                                                        @else
                                                            <span class="badge bg-success">Đã đăng</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <span>{{ $product->user_create }}</span>
                                                    <hr class="m-0"><span>{{ $product->created_at }}</span>
                                                </td>
                                                <td>
                                                    @if (request()->input('status') == 'trash')
                                                        @php
                                                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                                        @endphp
                                                        <a href="{{ route('admin.product.restore', ['id' => $product->id, 'page' => $page]) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Khôi phục"><i
                                                                class="fa-solid fa-trash-can-arrow-up"></i></a>
                                                        <a href="{{ route('admin.product.forceDelete', ['id' => $product->id, 'page' => $page]) }}"
                                                            class="btn btn-danger rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Xóa vĩnh viễn"
                                                            onclick="confirm('Bạn muốn xóa vĩnh viễn sản phẩm ra khỏi hệ thống')"><i
                                                                class="fa-solid fa-square-minus"></i></a>
                                                    @else
                                                        @php
                                                            if (request()->input('keyword')) {
                                                                $status = request()->input('keyword');
                                                            } else {
                                                                if (request()->input('status')) {
                                                                    $status = request()->input('status');
                                                                } else {
                                                                    $status = 'active';
                                                                }
                                                            }
                                                            
                                                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                                        @endphp
                                                        <a href="{{ route('admin.product.edit', ['id' => $product->id, 'status' => $status, 'page' => $page]) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Chỉnh sủa"><i
                                                                class="text-white fa-solid fa-pen-to-square"></i></a>
                                                        <a href="{{ route('admin.product.delete', ['id' => $product->id, 'status' => $status, 'page' => $page]) }}"
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
                                            <td colspan="9" class="text-danger">Không có sản phẩm tồn tại</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                    @if (request()->input('keyword'))
                        @php
                            $keyword = request()->input('keyword');
                        @endphp
                        {{ $list_product->appends(['keyword' => $keyword])->links('pagination::bootstrap-5') }}
                    @else
                        @php
                            $key = request()->input('status');
                        @endphp
                        {{ $list_product->appends(['status' => $key])->links('pagination::bootstrap-5') }}
                    @endif

                    @if (request()->input('keyword'))
                        <a href="{{ url('admin/product/list') }}" class="btn btn-warning text-white">Thoát tìm
                            kiếm</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
