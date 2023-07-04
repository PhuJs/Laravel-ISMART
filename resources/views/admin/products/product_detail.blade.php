@extends('layouts.admin')
@section('title', 'Chi tiết sản phẩm')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="product_detail p-16">
            <div class="card">
                <div class="card-header fw-semibold fs-5">
                    CHI TIẾT SẢN PHẨM
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-3">
                                <div class="p-2">
                                    <img src="{{ $product->thumbnail }}" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-8">
                                <p class="font-monospace fs-3 fw-semibold text-primary-emphasis">{{ $product->name }}</p>
                                <p class="mb-1"><span class="text-danger-emphasis fs-6 fw-medium">Mã sản phẩm: </span> <span
                                        class="text-primary">{{ $product->product_code }}</span></p>
                                <p class="mb-1"><span class="text-danger-emphasis fs-6 fw-medium">Giá sản phẩm: </span> <span
                                        class="text-primary">{{ $product->price }}</span></p>
                                <p class="mb-1"><span class="text-danger-emphasis fs-6 fw-medium">Danh mục: </span> <span
                                        class="text-primary">{{ $product->cat_name }}</span></p>
                                <p class="mb-2"><span class="text-danger-emphasis fs-6 fw-medium">Số lượng: </span> <span
                                        class="text-primary">{{ $product->number_stock }} sản phẩm</span></p>
                                <p class="text-dark-emphasis">{{ $product->desc }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="py-2 px-3" style="overflow:hidden;">
                                    <hr>
                                    {!! $product->product_detail !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <a href="{{ route('admin.product.list', $data_redirect) }}" class="btn btn-primary">Trở về</a>
        </div>
    @endsection
