@extends('layouts.admin')
@section('title', 'Danh sách bài viết')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="list_post p-16">
            <div class="card">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('status_error'))
                    <div class="alert alert-danger">
                        {{ session('status_error') }}
                    </div>
                @endif
                <div class="card-header fw-semibold fs-5">
                    DANH SÁCH BÀI VIẾT
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="card-status">
                            @php
                                if (request()->input('status') == 'trash') {
                                    $status = 'trash';
                                } elseif (request()->input('status') == 'approval') {
                                    $status = 'approval';
                                } else {
                                    $status = 'action';
                                }
                            @endphp
                            @if (request()->input('keyword'))
                                <a href="" class="text-decoration-none text-warning" onclick="return false">Kết
                                    quả<span>({{ $count[0] }})</span></a>
                            @else
                                <a href="{{ route('admin.post.list', ['status' => 'action']) }}"
                                    class="text-decoration-none {{ $status == 'action' ? 'text-warning' : '' }}">Đã
                                    đăng<span>({{ $count[0] }})</span></a>|
                                <a href="{{ route('admin.post.list', ['status' => 'approval']) }}"
                                    class="text-decoration-none {{ $status == 'approval' ? 'text-warning' : '' }}">Xét
                                    duyệt<span>({{ $count[1] }})</span></a>|
                                <a href="{{ route('admin.post.list', ['status' => 'trash']) }}"
                                    class="text-decoration-none {{ $status == 'trash' ? 'text-warning' : '' }}">Vô
                                    hiệu hóa<span>({{ $count[2] }})</span></a>
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
                                        <input type="submit" class="btn btn-primary rounded-0" value="Tìm kiếm"
                                            name="submit-form">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('admin.post.action') }}" method="POST">
                        @csrf
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-3">

                                    <div class="d-flex align-items-center">
                                        <div class="form-group">
                                            <select class="form-select rounded-0" name="action"
                                                aria-label="Default select example">
                                                <option value="0">--- Chọn ---</option>
                                                @foreach ($list_act as $key => $action)
                                                    <option value="{{ $key }}">{{ $action }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="form-submit" class="btn btn-primary rounded-0"
                                                value="Áp dụng">
                                            @php
                                                if (request()->input('keyword')) {
                                                    $status = request()->input('keyword');
                                                } else {
                                                    if (request()->input('status')) {
                                                        $status = request()->input('status');
                                                    } else {
                                                        $status = 'action';
                                                    }
                                                }
                                                
                                                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                            @endphp
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <input type="hidden" name="page" value="{{ $page }}">
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
                                        <th scope="col">Tiêu đề</th>
                                        <th scope="col">Danh mục</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Người tạo</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $index = ($page - 1) * 10;
                                    @endphp
                                    @if ($list_post->total() > 0)
                                        @foreach ($list_post as $post)
                                            @php
                                                $index++;
                                            @endphp
                                            <tr class="vertical-center text-center">
                                                <td>
                                                    <input type="checkbox" name="list_check[]" aria-label="Checkitem"
                                                        value="{{ $post->id }}">
                                                </td>
                                                <td scope="row">{{ $index }}</td>
                                                <td>
                                                    <a href="{{ route('admin.post.detail', ['id' => $post->id, 'status' => $status, 'page' => $page]) }}"
                                                        class="text-decoration-none">{{ strlen($post->post_title) > 52 ? substr($post->post_title, 0, 52) . '...' : $post->post_title }}</a>
                                                </td>
                                                <td>
                                                    <span class="">{{ $post->cat_name }}</span>
                                                </td>
                                                <td>
                                                    @if (request()->input('keyword'))
                                                        @if ($post->status == 1)
                                                            <span class="badge bg-danger">Chờ xét duyệt</span>
                                                        @else
                                                            <span class="badge bg-success">Hoạt động</span>
                                                        @endif
                                                    @else
                                                        @if (request()->input('status') == 'approval')
                                                            <span class="badge bg-danger">Chờ xét duyệt</span>
                                                        @elseif(request()->input('status') == 'trash')
                                                            <span class="badge bg-secondary">Xóa tạm thời</span>
                                                        @else
                                                            <span class="badge bg-success">Hoạt động</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <span>{{ $post->user_create }}</span>
                                                </td>
                                                <td>
                                                    {{ $post->created_at }}
                                                </td>
                                                <td>
                                                    @if (request()->input('status') == 'trash')
                                                        <a href="{{ route('admin.post.restore', ['id' => $post->id, 'page' => $page]) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Khôi phục"><i
                                                                class="fa-solid fa-trash-can-arrow-up"></i></a>
                                                        <a href="{{ route('admin.post.forceDelete', ['id' => $post->id, 'page' => $page]) }}"
                                                            class="btn btn-danger rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"
                                                            onclick="confirm('Bạn muốn xóa vĩnh viễn bài viết ra khỏi hệ thống')"><i
                                                                class="fa-solid fa-square-minus"></i></a>
                                                    @else
                                                        <a href="{{ route('admin.post.edit', ['id' => $post->id, 'status' => $status, 'page' => $page]) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                                class="text-white fa-solid fa-pen-to-square"></i></a>
                                                        <a href="{{ route('admin.post.delete', ['id' => $post->id, 'status' => $status, 'page' => $page])}}" class="btn btn-danger rounded-0 border-0"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            title="Delete"><i
                                                                class="text-white fa-solid fa-trash"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-danger">Không có bài viết tồn tại</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if (request()->input('keyword'))
                                @php
                                    $keyword = request()->input('keyword');
                                @endphp
                                {{ $list_post->appends(['keyword' => $keyword])->links('pagination::bootstrap-5') }}
                            @else
                                @php
                                    $status = request()->input('status');
                                @endphp
                                {{ $list_post->appends(['status' => $status])->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    </form>
                    @if (request()->input('keyword'))
                        <a href="{{ route('admin.post.list') }}" class="btn btn-success">Thoát tìm kiếm</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
