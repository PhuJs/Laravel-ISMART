@extends('layouts.apps')
@section('title', 'Chi tiết sản phẩm')
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
                                <a href="{{ request()->url() }}">Chi tiết sản phẩm</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-none d-lg-block col-lg-3">
                    <!-- ==== SIDEBAR LEFT ==== -->
                    @include('layouts.sidebar-sp-detail')
                </div>
                <div class="gx-0 col-lg-9 gx-lg-5">
                    <section id="product_detail">
                        <div class="section_detail_head">
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div id="wp-slider" class="px-4 py-2 p-lg-0">
                                        <div class="show-picture">
                                            <img src="" alt="">
                                            <button class="prev-btn">
                                                <i class="fa-solid fa-angle-left"></i>
                                            </button>
                                            <button class="next-btn">
                                                <i class="fa-solid fa-angle-right"></i>
                                            </button>
                                        </div>
                                        <ul class="list-thumb">
                                            {{-- Item 1 là của thumb --}}
                                            <li class="thumb-item"><img src="{{ $product->thumbnail }}" alt=""></li>
                                            @foreach ($list_image as $image)
                                                <li class="thumb-item"><img src="{{ $image->url }}" alt=""></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-8">
                                    <div id="product-infor">
                                        <h3 class="product-name">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="product-desc">
                                            {{ $product->desc }}
                                        </p>
                                        <p class="num-stoken">Sản phẩm: <span>còn hàng</span></p>
                                        <p class="product-price">
                                            {{ currency_format($product->price) }}
                                        </p>
                                        <form action="{{ route('add.carts', $product->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <span class="btn_minus">
                                                    <i class="fa-solid fa-minus"></i>
                                                </span>
                                                <input type="number" name="num-order" class="input_num_order"
                                                    min="1" value="1">
                                                <span class="btn_plus">
                                                    <i class="fa-solid fa-plus"></i>
                                                </span>
                                            </div>
                                            <input type="submit" name="order_submit" class="btn btn-primary"
                                                value="Thêm giỏ hàng" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="product-detail_title p-2 p-sm-0">Mô tả sản phẩm</h3>
                                    <div class="product-content">
                                        {!! $product->product_detail !!}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h3 class="product-detail_title p-2 p-sm-0">cùng chuyên mục</h3>
                                    <div class="list_product">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="owl-carousel">
                                                    @foreach ($list_product as $item)
                                                        <div class="product_item">
                                                            <a href="chi-tiet-san-pham/{{ Str::slug($item->name) }}/{{ $item->id }}"
                                                                class="product_img">
                                                                <img src="{{ $item->thumbnail }}" alt=""
                                                                    class="img-fluid">
                                                            </a>
                                                            <div class="product_detail">
                                                                <a href="chi-tiet-san-pham/{{ Str::slug($item->name) }}/{{ $item->id }}"
                                                                    class="product_name">{{ $item->name }}</a>
                                                                <p class="product_price">
                                                                    {{ currency_format($item->price) }}</p>
                                                                <p class="product_discount">
                                                                    {{ currency_format($item->discount) }}</p>
                                                                <div class="product_button">
                                                                    <a href="" class="btn_add_cart"
                                                                        data-id="{{ $item->id }}"
                                                                        onclick="return false">Thêm giỏ
                                                                        hàng</a>
                                                                    <a href="{{ route('add.cart', ['name' => Str::slug($item->name), 'id' => $item->id]) }}"
                                                                        class="btn_buy_now">Mua ngay</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
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
