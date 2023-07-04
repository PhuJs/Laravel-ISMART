@extends('layouts.apps')
@section('title', 'Danh Mục Sản Phẩm')
@section('content')
    <div id="wapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb">
                        <i class="fa-solid fa-house-chimney"></i>
                        <ul class="list_item">
                            <li class="">
                                <i class="fa-solid fa-angle-right"></i>
                                <a href="?">Trang chủ</a>
                            </li>
                            <li class="">
                                <i class="fa-solid fa-angle-right"></i>
                                <a href="{{ request()->url() }}">{{ $cat_name }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-none d-lg-block col-lg-3">
                    <!-- ==== SIDEBAR LEFT ==== -->
                    @include('layouts.sidebar-sp')
                </div>
                <div class="gx-0 col-lg-9 gx-lg-4">
                    <!-- ===== CONTENT ==== -->
                    <section id="content">
                        <div class="section_product">
                            <div class="section_product_head mb-0 pb-0">
                                <span class="title">{{ $cat_name }}</span>
                            </div>
                            <div class="form_select">
                                <form action="" class="form_select_product" method="GET">
                                    <label for="" class="label_total">Hiển thị {{ $list_product->total() }} trên
                                        {{ $total }} sản phẩm</label>
                                    <select name="action" class="select_action">
                                        <option value="">Sắp xếp</option>
                                        <option value="az">Từ A - Z</option>
                                        <option value="za">Từ Z - A</option>
                                        <option value="maxmin">Giá cao đến thấp</option>
                                        <option value="minmax">Giá thấp lên cao</option>
                                    </select>
                                    <input type="submit" name="submit-form" value="Lọc">
                                </form>
                            </div>
                            <div class="section_product_content">
                                <div class="list_product">
                                    <div class="container-fluid">
                                        <div class="row">
                                            @if ($list_product->total() > 0)
                                                @foreach ($list_product as $product)
                                                    <div class="col-6 col-sm-4 col-md-3 gx-3 mb-3">
                                                        <div class="product_item">
                                                            <a href="chi-tiet-san-pham/{{ Str::slug($product->name) }}/{{ $product->id }}"
                                                                class="product_img">
                                                                <img src="{{ $product->thumbnail }}" alt=""
                                                                    class="img-fluid">
                                                            </a>
                                                            <div class="product_detail">
                                                                <a href="chi-tiet-san-pham/{{ Str::slug($product->name) }}/{{ $product->id }}"
                                                                    class="product_name">{{ $product->name }}</a>
                                                                <p class="product_price">
                                                                    {{ currency_format($product->price) }}</p>
                                                                <p class="product_discount">
                                                                    {{ currency_format($product->discount) }}</p>
                                                                <div class="product_button">
                                                                    <a href="" class="btn_add_cart"
                                                                        data-id="{{ $product->id }}"
                                                                        onclick="return false">Thêm giỏ
                                                                        hàng</a>
                                                                    <a href="{{ route('add.cart', ['name' => Str::slug($product->name), 'id' => $product->id]) }}"
                                                                        class="btn_buy_now">Mua ngay</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-12">
                                                    <div class="alert alert-success">
                                                        Không có sản phẩm tồn tại
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-5 text-center">
                                            {{ $list_product->links('vendor.pagination.bootstrap-5') }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
