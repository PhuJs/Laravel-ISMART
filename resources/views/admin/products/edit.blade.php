@extends('layouts.admin')
@section('title', 'Cập nhật sản phẩm')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="add_product p-16">
            <div class="card">
                <div class="card-header fs-5 fw-semibold">
                    Cập nhật sản phẩm
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.product.update', ['id' => $product->id, 'status' => request()->input('status'), 'page' => request()->input('page')]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control" name="product_name" aria-label="First name"
                                        id="product_name" value="{{ $product->name }}">
                                    @error('product_name')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb3">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="product_price" class="form-label">Giá sản
                                                    phẩm</label>
                                                <input type="number" class="form-control" aria-label="First name"
                                                    id="product_price" min=0 name="price" value="{{ $product->price }}">
                                                @error('price')
                                                    <span class="text-danger d-block mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="discount" class="form-label">Giá cũ</label>
                                                <input type="number" class="form-control" aria-label="First name"
                                                    id="discount" min=0 name="discount" value="{{ $product->discount }}">
                                                @error('discount')
                                                    <span class="text-danger d-block mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="desc" class="form-label">Mô tả sản phẩm</label>
                                <textarea name="desc" class="form-control" id="desc" style="height:72%;">{{ $product->desc }}</textarea>
                                @error('desc')
                                    <span class="text-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="product_code" class="form-label">Mã sản phẩm</label>
                                    <input type="text" name="product_code" class="form-control" id="product_code" value="{{ $product->product_code }}">
                                    @error('product_code')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                    @if(session('error_code'))
                                       <span class="text-danger d-block mt-1">{{ session('error_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="num_repository" class="form-label">Số lượng trong kho</label>
                                    <input type="number" name="num_repository" class="form-control" id="num_repository"
                                        min=0 value="{{ $product->number_stock }}">
                                    @error('num_repository')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="product_detail" class="form-label">Chi tiết sản phẩm</label>
                                    <textarea class="form-control" name="product_detail" id="product_detail" cols="30" rows="10">{{ $product->product_detail }}</textarea>
                                    @error('product_detail')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Loại sản phẩm <span class="text-danger">(nếu có)</span></label> 
                                    <input type="text" id="tags" name="tags" class="form-control" placeholder="VD: nổi bật, bán chạy,..." value="{{$product->tags}}">
                                </div>
                            </div>
                            <div class="col-7">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Hình ảnh sản phẩm</label>
                                    <input class="form-control" type="file" id="formFile" name="file">
                                    <img id="formImage" class="img-fluid img-thumbnail"src="{{ $product->thumbnail }}"  alt="Ảnh Lukaku">
                                    @error('file')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formFiles" class="form-label">Hình ảnh chi tiết</label>
                                    <input class="form-control" type="file" name="files[]" id="formFiles" multiple>
                                    @error('files.*')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="product-cart">Danh mục</label>
                                    <select name="product_cat" id="product-cart" class="form-select">
                                        <option value="0">--- Chọn danh mục ---</option>
                                        @foreach ($list_cat as $item)
                                            <option value="{{ $item->id }}" {{ ($item->id == $product->cat_id)?"selected":"" }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_cat')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="">Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radio_status"
                                            id="flexRadioDefault1" value="1" {{ ($product->status == 1)?"checked":"" }}>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radio_status"
                                            id="flexRadioDefault2" value="2" {{ ($product->status == 2)?"checked":"" }}>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Công khai
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <!-- Trống -->
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Cập nhật" name="submit_form">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
