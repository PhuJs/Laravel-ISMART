@extends('layouts.admin')
@section('title', 'Cập nhật danh mục sản phẩm')
@section('content')
<div id="content" class="mh-full pt-4">
    <div class="p-16">
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-header fs-5 fw-semibold">
                        Cập nhật danh mục
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product.cat.update',  $product_cat->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="input_text" class="form-label">Tên danh mục</label>
                                <input type="text" name="name" id="input_text" class="form-control" value="{{ $product_cat->name }}">
                                @error('name')
                                <span class="text-danger d-block mt-2 ">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select-check" class="form-label">Danh mục cha</label>
                                <select class="form-select" id="select-check" name="cat">
                                    <option value="0">--- Chọn danh mục ---</option>
                                    @foreach($list_cat_active as $item)
                                    <option value="{{$item->id}}" {{ $item->id==$product_cat->parent_id?"selected='selected'":"" }}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="submit" name="submit-update" value="Cập nhật" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card bg-dark-subtle">
                    <div class="card-header fs-5 fw-semibold">
                        Danh mục
                    </div>
                    <div class="card-body">
                        <div class="card-status mb-1">
                            <a href="" class="text-secondary text-decoration-none" onclick="return false">Hoạt động<span>({{ $count[0] }})</span></a>|
                            <a href="" class="text-secondary text-decoration-none" onclick="return false">Chờ xét duyệt<span>({{ $count[1] }})</span></a>|
                            <a href="" class="text-secondary text-decoration-none" onclick="return false">Vô hiệu hóa<span>({{ $count[2] }})</span></a>
                        </div>
                        <table class="table disabled">
                            <thead class="">
                                <tr class="">
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên danh mục</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Người tạo</th>
                                    <th scope="col">Thời gian</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($list_cat))
                                @php
                                $k = 0;
                                @endphp
                                @foreach($list_cat as $item)
                                @php
                                $k++;
                                @endphp
                                <tr class="vertical-center">
                                    <th scope="row">{{ $k }}</th>
                                    <td>{{ str_repeat('|-- ', $item->level).$item->name }}</td>
                                    <td>
                                        <span class="badge bg-success">Hoạt động</span>
                                    <td>{{ $item->user_create }}</td>
                                    <td>
                                        {{ $item->created_at }}
                                    </td>
                                    <td>
                                        <button class="btn btn-success rounded-0 border-0 me-1" type="button" title="Edit" disabled><i class="text-white fa-solid fa-pen-to-square"></i></a>
                                        <button class="btn btn-danger rounded-0 border-0" type="button" title="Delete" disabled><i class="text-white fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6">
                                        <span class="text-secondary">Không có danh mục tồn tại</span>
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