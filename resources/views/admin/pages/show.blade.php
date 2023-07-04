@extends('layouts.admin')
@section('title', 'Danh sách trang')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="list_post p-16">
            @if (session('alert'))
                <div class="alert alert-warning">
                    {{ session('alert') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header fw-semibold fs-5">
                    DANH SÁCH TRANG
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        @php
                            $status = isset($_GET['status']) ? $_GET['status'] : 'all';
                        @endphp
                        <div class="card-status">
                            <a href="{{ route('admin.page.show', ['status' => 'all']) }}"
                                class="text-decoration-none {{ $status == 'all' ? 'text-warning' : '' }}">Tất
                                cả<span>({{ $total['total_page'] }})</span></a>|
                            <a href="{{ route('admin.page.show', ['status' => 'trash']) }}"
                                class="text-decoration-none {{ $status == 'trash' ? 'text-warning' : '' }}">Vô
                                hiệu hóa<span>({{ $total['total_page_trash'] }})</span></a>
                        </div>
                        <div class="form-search">
                            <a href="{{ route('admin.page.add') }}" class="btn btn-primary">Thêm mới</a>
                        </div>
                    </div>
                    <div class="table_post mt-3">
                        <table class="table table-striped table-checkall">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th scope="col">STT</th>
                                    <th scope="col">Tiêu đề</th>
                                    <th scope="col">URL</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Người tạo & Ngày tạo</th>
                                    <th scope="col">Nội dung</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($list_page->count() > 0)
                                    @php
                                        $stt = 0;
                                    @endphp
                                    @foreach ($list_page as $page)
                                        @php
                                            $stt++;
                                        @endphp
                                        <tr class="vertical-center text-center">
                                            <td>
                                                <span>{{ $stt }}</span>
                                            </td>
                                            <td scope="row">{{ $page->title }}</td>
                                            <td>
                                                <span>{{ $page->url }}</span>
                                            </td>
                                            <td>
                                                @if (request()->input('status') == 'trash')
                                                    <span class="badge bg-secondary">Vô hiệu hóa</span>
                                                @else
                                                    @if ($page->status == 1)
                                                        <span class="badge bg-danger">Chờ duyệt</span>
                                                    @else
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span>{{ $page->user_create }}</span>
                                                <hr class="m-0">
                                                <span>{{ $page->created_at }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.page.content', ['id' => $page->id, 'status' => $status]) }}"
                                                    class="">Nội dung</a>
                                            </td>
                                            <td>
                                                @if (request()->input('status') == 'trash')
                                                    <a href="{{ route('admin.page.restore', $page->id) }}"
                                                        class="btn btn-success rounded-0 border-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Khôi phục trang"><i
                                                            class="fa-solid fa-trash-can-arrow-up"></i></a>
                                                    <a href="{{ route('admin.page.forceDelete', $page->id) }}"
                                                        class="btn btn-danger rounded-0 border-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"
                                                        onclick="confirm('Bạn muốn xóa vĩnh viễn trang ra khỏi hệ thống')"><i
                                                            class="fa-solid fa-square-minus"></i></a>
                                                @else
                                                    <a href="{{ route('admin.page.edit', $page->id) }}"
                                                        class="btn btn-success rounded-0 border-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                            class="text-white fa-solid fa-pen-to-square"></i></a>
                                                    <a href="{{ route('admin.page.delete', $page->id) }}"
                                                        class="btn btn-danger rounded-0 border-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                            class="text-white fa-solid fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-danger">Không có trang tồn tại</td>
                                    </tr>
                                @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
