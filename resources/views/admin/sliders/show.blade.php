@extends('layouts.admin')
@section('title', 'Danh sách Slider')
@section('content')
    <div class="content" class="mh-full pt-4">
        <div class="add_posts p-16" style="height:100vh">
            @if (session('alert'))
                <div class="alert alert-warning">
                    {{ session('alert') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header fs-5 fw-semibold">
                    Danh sách Slider
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        @php
                            $status = isset($_GET['status'])?$_GET['status']:'all';
                        @endphp
                        <div class="card-status">
                            <a href="{{ route('admin.slider.show', ['status' => 'all']) }}" class="text-decoration-none {{ ($status == 'all')?'text-warning':'' }}">
                                Tất cả<span>({{ $total['total_slider'] }})</span></a>|
                            <a href="{{ route('admin.slider.show', ['status' => 'trash']) }}"
                                class="text-decoration-none {{ ($status == 'trash')?'text-warning':'' }}">Vô hiệu hóa
                                <span>({{ $total['total_slider_trash'] }})</span></a>
                        </div>
                        <div>
                            <a href="{{ route('admin.slider.add') }}" class="btn btn-primary">Thêm mới</a>
                        </div>
                    </div>
                    <div class="mt-3">
                        <table class="table text-center">
                            <thead class="table-light">
                                <th scope="col">STT</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tiêu đề & Mô tả</th>
                                <th scope="col">URL</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Người tạo & Thời gian</th>
                                <th scope="col">Tác vụ</th>
                            </thead>
                            <tbody>
                                @if ($list_slider->total() > 0)
                                    @php
                                        $k = 0;
                                    @endphp
                                    @foreach ($list_slider as $slider)
                                        @php
                                            $k++;
                                        @endphp
                                        <tr class="align-middle">
                                            <td>{{ $k }}</td>
                                            <td>
                                                <img src="{{ $slider->thumb }}" alt=""
                                                    class="img-fluid img-thumbnail" style="width:150px; height:auto;">
                                            </td>
                                            <td>
                                                <span>{{ $slider->title }}</span>
                                                <hr class="m-0">
                                                <span>{{ $slider->desc }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ $slider->url }}"
                                                    style="font-size:15px;">{{ $slider->url }}</a>
                                            </td>
                                            <td>
                                                @if (request()->input('status') == 'trash')
                                                    <span class="badge bg-secondary">Thùng rác</span>
                                                @else
                                                    @if ($slider->status == 1)
                                                        <span class="badge bg-danger">Chờ duyệt</span>
                                                    @else
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span>{{ $slider->user_create }}</span>
                                                <hr class="m-0">
                                                <span>{{ $slider->created_at }}</span>
                                            </td>
                                            <td>
                                                @if (request()->input('status') == 'trash')
                                                    <a href="{{ route('admin.slider.restore', $slider->id) }}"
                                                        class="btn btn-success rounded-0 border-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Khôi phục"><i
                                                            class="fa-solid fa-trash-can-arrow-up"></i></a>
                                                    <a href="{{ route('admin.slider.forceDelete', $slider->id) }}"
                                                        class="btn btn-danger rounded-0 border-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"
                                                        onclick="confirm('Bạn muốn xóa vĩnh viễn Slider ra khỏi hệ thống')"><i
                                                            class="fa-solid fa-square-minus"></i></a>
                                                @else
                                                    <a href="{{ route('admin.slider.edit', $slider->id) }}"
                                                        class="btn btn-success rounded-0 border-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Chỉnh sủa"><i
                                                            class="text-white fa-solid fa-pen-to-square"></i></a>
                                                    <a href="{{ route('admin.slider.delete', $slider->id) }}"
                                                        class="btn btn-danger rounded-0 border-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Xóa tạm thời"><i
                                                            class="text-white fa-solid fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-danger">Không có Slider tồn tại</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
