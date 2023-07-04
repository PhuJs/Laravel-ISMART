@extends('layouts.admin')
@section('title', 'Danh mục sản phẩm')
@section('content')
<div id="content" class="mh-full pt-4">
    <div class="p-16">
        @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-header fs-5 fw-semibold">
                        Thêm Danh Mục
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/product/cat/add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="input_text" class="form-label">Tên danh mục</label>
                                <input type="text" name="name" id="input_text" class="form-control">
                                @error('name')
                                <span class="text-danger d-block mt-2 ">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select-check" class="form-label">Danh mục cha</label>
                                <select class="form-select" id="select-check" name="cat">
                                    <option value="0">--- Chọn danh mục ---</option>
                                    @foreach($list_cat_active as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
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
                        <div class="card-status mb-1">
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-decoration-none">Hoạt động<span>({{ $count[0] }})</span></a>|
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'confirm']) }}" class="text-decoration-none">Chờ xét duyệt<span>({{ $count[1] }})</span></a>|
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-decoration-none">Vô hiệu hóa<span>({{ $count[2] }})</span></a>
                        </div>
                        <table class="table">
                            <thead class="table-light">
                                <tr class="">
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên danh mục</th>
                                    @if(request()->input('status') == 'confirm')
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
                                @if(!empty($list_cat))
                                @php
                                $k = 0
                                @endphp
                                @foreach($list_cat as $item)
                                @php
                                $k++;
                                @endphp
                                <tr class="vertical-center">
                                    <th scope="row">{{ $k }}</th>
                                    <td>{{ str_repeat('|-- ', $item->level).$item->name }}</td>
                                    <td>
                                        @if(request()->input('status') == 'trash')
                                        <span class="badge bg-secondary">Vô hiệu hóa</span>
                                        @elseif(request()->input('status') == 'confirm')
                                        <span class="text-danger">
                                            @foreach($list_cat_all as $cat_all)
                                            @if($cat_all->id == $item -> parent_id)
                                            {{ $cat_all -> name }}  
                                            @endif
                                            @endforeach 
                                        </span>
                                        @else
                                        <span class="badge bg-success">Hoạt động</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->user_create }}</td>
                                    <td>
                                        {{ $item->created_at }}
                                    </td>
                                    <td>
                                        @if(request()->input('status') == 'confirm')
                                        <a href="{{ route('admin.product.cat.approval', $item->id) }}" class="btn btn-success rounded-0 border-0" type="button" data-toggle="tooltip" data-placement="top" title="Xét duyệt" onclick="return confirm('Đảm bảo danh mục cha đã hoạt động')"><i class="fa-solid fa-check"></i></a>
                                        <a href="{{ route('admin.product.cat.delete', ['id' => $item->id, 'status' => 'confirm']) }}" class="btn btn-danger rounded-0 border-0" type="button" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Bạn muốn xóa danh mục')"><i class="text-white fa-solid fa-trash"></i></a>
                                        @elseif(request()->input('status') == 'trash')
                                        <a href="{{ route('admin.product.cat.restore', $item->id) }}" class="btn btn-success rounded-0 border-0" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-trash-arrow-up"></i></a>
                                        <a href="{{ route('admin.product.cat.forceDelete', $item->id) }}" class="btn btn-danger rounded-0 border-0" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn" onclick="return confirm('Bạn muốn xóa vĩnh viễn danh mục')"><i class="fa-solid fa-xmark"></i></a>
                                        @else 
                                        <a href="{{ route('admin.product.cat.edit', $item->id) }}" class="btn btn-success rounded-0 border-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="text-white fa-solid fa-pen-to-square"></i></a>
                                        <a href="{{ route('admin.product.cat.delete', ['id' => $item->id, 'status' => 'active']) }}" class="btn btn-danger rounded-0 border-0" type="button" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Bạn muốn xóa danh mục')"><i class="text-white fa-solid fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6">
                                        <span class="text-danger">Không có danh mục tồn tại</span>
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