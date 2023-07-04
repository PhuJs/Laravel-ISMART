@extends('layouts.admin')
@section('title', 'Danh mục bài viết')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="p-16">
            @if (session('alert'))
                <div class="alert alert-success">
                    {{ session('alert') }}
                </div>
            @endif
            @if (session('alert_error'))
                <div class="alert alert-danger">
                    {{ session('alert_error') }}
                </div>
            @endif
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header fs-5 fw-semibold">
                            Thêm Danh Mục
                        </div>
                        <div class="card-body">
                            <form action="{{ url('admin/post/cat/add') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="input_text" class="form-label">Tên danh mục</label>
                                    <input type="text" name="name" id="input_text" class="form-control">
                                    @error('name')
                                        <span class="d-block mt-1 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="select-check" class="form-label">Danh mục cha</label>
                                    <select class="form-select" id="select-check" name="cat">
                                        <option value="0">--- Chọn danh mục ---</option>
                                        @foreach ($list_cat_selected as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->cat_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="submit" name="submit-form" value="Thêm mới" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-header fs-5 fw-semibold">
                            Danh mục
                        </div>
                        <div class="card-body">
                            @php
                                if (request()->input('status') == 'approval') {
                                    $status = 'approval';
                                } elseif (request()->input('status') == 'trash') {
                                    $status = 'trash';
                                } else {
                                    $status = 'action';
                                }
                            @endphp
                            <div class="card-status mb-1">
                                <a href="{{ request()->fullUrlWithQuery(['status' => 'action']) }}"
                                    class="text-decoration-none {{ $status == 'action' ? 'text-warning' : '' }}">Hoạt
                                    động<span>({{ $count[0] }})</span></a>|
                                <a href="{{ request()->fullUrlWithQuery(['status' => 'approval']) }}"
                                    class="text-decoration-none {{ $status == 'approval' ? 'text-warning' : '' }}">Chờ xét
                                    duyệt<span>({{ $count[1] }})</span></a>|
                                <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}"
                                    class="text-decoration-none {{ $status == 'trash' ? 'text-warning' : '' }}">Vô hiệu
                                    hóa<span>({{ $count[2] }})</span></a>
                            </div>
                            <table class="table">
                                <thead class="table-light">
                                    <tr class="">
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên danh mục</th>
                                        @if (request()->input('status') == 'approval')
                                            <th scope="col">Danh mục cha</th>
                                        @else
                                            <th scope="col">Trạng thái</th>
                                        @endif
                                        <th scope="col">Người tạo</th>
                                        <th scope="col">Thời gian</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $stt = ($page-1)*15;
                                    @endphp
                                    @if (count($list_post_cat) > 0)
                                        @foreach ($list_post_cat as $post_cat)
                                            @php
                                                $stt++;
                                            @endphp
                                            <tr class="vertical-center">
                                                <th scope="row">{{ $stt }}</th>
                                                @if ($status == 'action')
                                                    <td>{{ str_repeat('|--- ', $post_cat->level) . $post_cat->cat_name }}
                                                    </td>
                                                @else
                                                    <td>{{ $post_cat->cat_name }}</td>
                                                @endif
                                                <td>
                                                    @if (request()->input('status') == 'trash')
                                                        <span class="badge bg-secondary">Xóa tạm thời</span>
                                                    @elseif(request()->input('status') == 'approval')
                                                        @foreach ($list_cat_selected as $cat_selected)
                                                            @if ($cat_selected->id == $post_cat->parent_id)
                                                                <span
                                                                    class="text-danger">{{ $cat_selected->cat_name }}</span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @endif
                                                </td>
                                                <td>{{ $post_cat->user_create }}</td>
                                                <td>
                                                    {{ $post_cat->created_at }}
                                                </td>
                                                <td>
                                                    @if (request()->input('status') == 'approval')
                                                        <a href="{{ route('admin.post.cat.approval', $post_cat->id) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Xét duyệt"
                                                            onclick="return confirm('Đảm bảo danh mục cha đã hoạt động')"><i
                                                                class="fa-solid fa-check"></i></a>
                                                        <a href="{{ route('admin.post.cat.delete', ['id' => $post_cat->id, 'status' => 'approval']) }}"
                                                            class="btn btn-danger rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Delete"
                                                            onclick="return confirm('Các danh mục con sẽ ẩn khỏi xét duyệt khi xóa')"><i
                                                                class="text-white fa-solid fa-trash"></i></a>
                                                    @elseif(request()->input('status') == 'trash')
                                                        <a href="{{ route('admin.post.cat.restore', $post_cat->id) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Khôi phục"><i
                                                                class="fa-solid fa-trash-arrow-up"></i></a>
                                                        <a href="{{ route('admin.post.cat.forceDelete', $post_cat->id) }}"
                                                            class="btn btn-danger rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"
                                                            onclick="return confirm('Bạn muốn xóa vĩnh viễn danh mục')"><i
                                                                class="fa-solid fa-xmark"></i></a>
                                                    @else
                                                        <a href="{{ route('admin.post.cat.edit', $post_cat->id) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                                class="text-white fa-solid fa-pen-to-square"></i></a>
                                                        <a href="{{ route('admin.post.cat.delete', ['id' => $post_cat->id, 'status' => 'action']) }}"
                                                            class="btn btn-danger rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Delete"
                                                            onclick="return confirm('Bạn muốn xóa danh mục')"><i
                                                                class="text-white fa-solid fa-trash"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">
                                                <span class="text-danger mt-1">Không có danh mục tồn tại</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
