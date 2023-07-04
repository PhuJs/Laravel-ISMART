@extends('layouts.apps')

<!-- ====== CONTENT ===== -->
@section('content')
    <div id="wapper">
        <div class="container">
            <div class="row">
                <div class="d-none d-lg-block col-lg-3">
                    <!-- ==== SIDEBAR LEFT ==== -->
                    @include('layouts.sidebar')
                </div>
                <div class="gx-0 col-lg-9 gx-lg-4">
                    <!-- ===== CONTENT ==== -->
                    <section id="content">
                        <!-- == BANNER == -->
                        <div id="section_banner">
                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                                        class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                                        aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                                        aria-label="Slide 3"></button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <a href="" class="d-block">
                                            <img src="./public/images/slider-01.png" class="d-block w-100" alt="...">
                                        </a>
                                    </div>
                                    <div class="carousel-item">
                                        <a href="" class="d-block">
                                            <img src="./public/images/slider-02.png" class="d-block w-100" alt="...">
                                        </a>
                                    </div>
                                    <div class="carousel-item">
                                        <a href="" class="d-block">
                                            <img src="./public/images/slider-03.png" class="d-block w-100" alt="...">
                                        </a>
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>

                        <!-- === ICON ==  -->
                        <div id="section_policy" class="mt-25">
                            <ul>
                                <li>
                                    <div class="fonts">
                                        <img src="./public/images/icon-1.png" alt="">
                                    </div>
                                    <h3 class="title">Miễn phí vận chuyển</h3>
                                    <p class="slogan">Tới tận tay khách hàng</p>
                                </li>
                                <li>
                                    <div class="fonts">
                                        <img src="./public/images/icon-2.png" alt="">
                                    </div>
                                    <h3 class="title">Tư vấn 24/7</h3>
                                    <p class="slogan">19006079</p>
                                </li>
                                <li>
                                    <div class="fonts">
                                        <img src="./public/images/icon-3.png" alt="">
                                    </div>
                                    <h3 class="title">Tiết kiệm hơn</h3>
                                    <p class="slogan">Với nhiều ưu đãi cực lớn</p>
                                </li>
                                <li>
                                    <div class="fonts">
                                        <img src="./public/images/icon-4.png" alt="">
                                    </div>
                                    <h3 class="title">Thanh toán nhanh</h3>
                                    <p class="slogan">Hỗ trợ nhiều hình thức</p>
                                </li>
                                <li>
                                    <div class="fonts">
                                        <img src="./public/images/icon-5.png" alt="">
                                    </div>
                                    <h3 class="title">Đặt hàng online</h3>
                                    <p class="slogan">Thao tác đơn giản</p>
                                </li>
                            </ul>
                        </div>

                        <!-- == PRODUCT HOT == -->
                        <div class="section_product mt-25">
                            <div class="section_product_head">
                                <span class="title">Sản phẩm nổi bật</span>
                            </div>
                            <div class="section_product_content">
                                @if ($list_product_hot)
                                    <div class="owl-carousel">
                                        @foreach ($list_product_hot as $product)
                                            <div class="product_item">
                                                <a href="chi-tiet-san-pham/{{ Str::slug($product->name) }}/{{ $product->id }}"
                                                    class="product_img">
                                                    <img src="{{ $product->thumbnail }}" alt="" class="img-fluid">
                                                </a>
                                                <div class="product_detail">
                                                    <a href="chi-tiet-san-pham/{{ Str::slug($product->name) }}/{{ $product->id }}"
                                                        class="product_name">{{ $product->name }}</a>
                                                    <p class="product_price">{{ currency_format($product->price) }}</p>
                                                    <p class="product_discount">{{ currency_format($product->discount) }}
                                                    </p>
                                                    <div class="product_button">
                                                        <a href="" class="btn_add_cart" onclick="return false"
                                                            data-id="{{ $product->id }}">Thêm giỏ
                                                            hàng</a>
                                                        <a href="{{ route('add.cart', ['name' => Str::slug($product->name), 'id' => $product->id]) }}"
                                                            class="btn_buy_now">Mua ngay</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-primary">
                                        Không có sản phẩm tồn tại
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Danh sách sản phẩm theo danh mục -->
                        @foreach ($list_product as $key => $products)
                            <div class="section_product mt-25">
                                <div class="section_product_head">
                                    <span class="title">{{ $key }}</span>
                                </div>
                                <div class="section_product_content">
                                    <div class="list_product">
                                        <div class="container-fluid">
                                            <div class="row">
                                                @foreach ($products as $item)
                                                    <div class="col-6 col-sm-4 col-md-3 gx-3 mb-3">
                                                        <div class="product_item">
                                                            <a href="chi-tiet-san-pham/{{ Str::slug($item['name']) }}/{{ $item['id'] }}"
                                                                class="product_img">
                                                                <img src="{{ $item['thumbnail'] }}" alt=""
                                                                    class="img-fluid">
                                                            </a>
                                                            <div class="product_detail">
                                                                <a href="chi-tiet-san-pham/{{ Str::slug($item['name']) }}/{{ $item['id'] }}"
                                                                    class="product_name">{{ $item['name'] }}</a>
                                                                <p class="product_price">
                                                                    {{ currency_format($item['price']) }}</p>
                                                                <p class="product_discount">
                                                                    {{ currency_format($item['discount']) }}</p>
                                                                <div class="product_button">
                                                                    <a href="" class="btn_add_cart"
                                                                        onclick="return false"
                                                                        data-id="{{ $item['id'] }}">Thêm
                                                                        giỏ
                                                                        hàng</a>
                                                                    <a href="{{ route('add.cart', ['name' => Str::slug($item['name']), 'id' => $item['id']]) }}"
                                                                        class="btn_buy_now">Mua ngay</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
