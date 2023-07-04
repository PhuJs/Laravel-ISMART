<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ url('/') . '/' }}">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}" />
    <link rel="stylesheet" href="{{ asset('fonts/css/all.min.css') }}" />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/carousel/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/carousel/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style_cp.css') }}" />
    <title>@yield('title', 'Cửa Hàng Thương Mại Điện Tử ISMART')</title>
</head>

<body>
    <!-- ======== APPLICATION ========= -->
    <div id="apps">
        <!-- ====== HEADER ====== -->
        <header id="header">
            <section id="header_top" class="d-none d-md-block">
                <div class="container d-flex justify-content-between align-items-center">
                    <a href="?" id="payment_link">
                        <i class="fa-solid fa-link"></i>
                        Hình thức thanh toán
                    </a>
                    <nav>
                        <ul id="main_menu">
                            <li><a href="?">Trang chủ</a></li>
                            <li><a href="{{ route('post.show') }}">Tin công nghệ</a></li>
                            <li><a href="{{ route('page.show', ['slug' => 'gioi-thieu']) }}">Giới thiệu</a></li>
                            <li><a href="{{ route('page.show', ['slug' => 'lien-he']) }}">Liên hệ</a></li>
                        </ul>
                    </nav>
                </div>
            </section>
            <section id="header_body">
                <div class="container d-flex justify-content-between align-items-center">
                    <a href="?" id="logo">
                        <img src="public/images/logo.png" alt="">
                    </a>
                    <div id="form_search" class="d-none d-md-block">
                        <form action="">
                            <input type="text" name="input_search" placeholder="Nhập từ khóa tìm kiếm tại đây"
                                id="form_search_input">
                            <input type="submit" name="submit_form" value="Tìm kiếm" class="submit_form">
                        </form>
                        @php
                            $list_product_search = list_product_search();
                        @endphp
                        <div id="search_result">
                            @foreach ($list_product_search as $product)
                                <div class="search_product_item">
                                    <a href="chi-tiet-san-pham/{{ Str::slug($product->name) }}/{{ $product->id }}">
                                        <img src="{{ $product->thumbnail }}" alt="" class="search_product_img">
                                    </a>
                                    <div class="search_product_info">
                                        <a href="chi-tiet-san-pham/{{ Str::slug($product->name) }}/{{ $product->id }}"
                                            class="search_product_name">{{ $product->name }}</a>
                                        <p class="search_product_price">{{ currency_format($product->price) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="header_body_right">
                        <div id="contact" class="d-none d-lg-block">
                            <i class="fa-solid fa-phone-volume"></i>
                            <p class="title">Tư vấn</p>
                            <span class="phone">0702.988.414</span>
                        </div>
                        {{-- === Cart === --}}
                        <div id="cart">
                            <a href="?page=cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </a>
                            <span class="total_cart">
                                {{ Cart::count() > 0 ? Cart::count() : '' }}
                            </span>
                            <div id="dropdown">
                                @if (Cart::count() > 0)
                                    <p class="alert">Có <span class="text-danger">{{ Cart::count() }} sản phẩm
                                        </span>trong giỏ hàng</p>
                                    <ul class="list_cart" style="height:200px; overflow:auto;">
                                        @foreach (Cart::content() as $row)
                                            <li>
                                                <a href="chi-tiet-san-pham/{{ Str::slug($row->name) }}/{{ $row->id }}"
                                                    class="cart_thumb">
                                                    <img src="{{ $row->options->thumbnail }}" alt="">
                                                </a>
                                                <div>
                                                    <a href="chi-tiet-san-pham/{{ Str::slug($row->name) }}/{{ $row->id }}"
                                                        class="product_name">{{ $row->name }}</a>
                                                    <p class="price">{{ currency_format($row->price) }}</p>
                                                    <p class="qty">Số lượng <span>{{ $row->qty }}</span></p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="total_price text-danger fw-semibold">
                                        <p>Tổng:</p>
                                        <p class="p_last_child">{{ Cart::total() }}đ</p>
                                    </div>
                                    <div class="action_cart">
                                        <a href="{{ route('cart.show') }}" class="btn_cart">Giỏ hàng</a>
                                        <a href="{{ route('cart.payment') }}" class="btn_payment">Thanh toán</a>
                                    </div>
                                @else
                                    <div class="py-3 bg-light">
                                        <h6 class="text-danger text-center">
                                            Giỏ hàng trống
                                        </h6>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div id="navbar_responsive" class="d-lg-none">
                            <button class="toggle_menu_responsive">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </header>
        {{-- ================================= --}}
        @yield('content')
        {{-- ================================= --}}
        <footer id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3 mb-4">
                        <div class="box">
                            <div class="box_head">
                                <h3 class="box_logo">
                                    ismart
                                </h3>
                            </div>
                            <div class="box_body">
                                <p class="text">
                                    ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ ràng, chính sách ưu
                                    đãi cực lớn cho khách hàng có thẻ thành viên.
                                </p>
                                <div class="payment">
                                    <img src="public/images/img-foot.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3 mb-4">
                        <div class="box">
                            <div class="box_head">
                                <h3 class="box_title">
                                    Thông tin cửa hàng
                                </h3>
                            </div>
                            <div class="box_body">
                                <ul class="list_address">
                                    <li>
                                        <i class="fa-solid fa-location-dot"></i>
                                        <span> 06 Lữ Gia, P.9, Thành Phố Đà Lạt, Lâm Đồng</span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-phone"></i>
                                        <span> 0702.988.414 - 0825.678.978</span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-envelope"></i>
                                        <span>tranthanhphuhug15@gmail.com</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3 mb-4">
                        <div class="box">
                            <div class="box_head">
                                <h3 class="box_title">
                                    Chính sách mua hàng
                                </h3>
                            </div>
                            <div class="box_body">
                                <ul class="list_policy">
                                    <li>
                                        <a href="">Quy định - chính sách</a>
                                    </li>
                                    <li>
                                        <a href="">Chính sách bảo hành - đổi trả</a>
                                    </li>
                                    <li>
                                        <a href="">Chính sách hội viên</a>
                                    </li>
                                    <li>
                                        <a href="">Giao hàng lắp đặt</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3 mb-4">
                        <div class="box">
                            <div class="box_head">
                                <h3 class="box_title">
                                    Bảng tin
                                </h3>
                            </div>
                            <div class="box_body ps-3">
                                <p class="text">
                                    Đăng kí với chúng tôi để nhạn thông tin ưu đãi sớm nhất
                                </p>
                                <div class="box_form">
                                    <form action="">
                                        <input type="email" name="email" placeholder="Nhập email tại đây!">
                                        <input type="submit" name="submit-form" value="Đăng kí">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- ===== FOOTER BOOT === -->
        <div id="footer_boot">
            <p>© Bản quyền thuộc về PhuGroup</p>
        </div>

        <!-- ===== BACKTOP ==== -->
        <div id="back_top">
            <img src="public/images/icon-to-top.png" alt="" style="max-width:100%;">
        </div>
        <!-- ===== MODAL ===== -->
        <section id="modal">
            <div id="wp_menu_responsive">
                <h3>ISMART</h3>
                <button class="out_modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <ul id="menu_responsive">
                    <li>
                        <div class="d-flex align-items-center">
                            <a href="?">Trang chủ</a>
                            <span></span>
                        </div>
                    </li>
                    {{-- ---- Product Cat ---- --}}
                    {!! render_menu_responsive($list_product_cat) !!}

                    <li>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('post.show') }}">Tin công nghệ</a>
                            <span></span>
                        </div>
                    </li>
                    <!-- 6 -->
                    <li>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('page.show', ['slug' => 'gioi-thieu']) }}">Giới thiệu</a>
                            <span></span>
                        </div>
                    </li>
                    {{-- 7 --}}
                    <li>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('page.show', ['slug' => 'lien-he']) }}">Liên hệ</a>
                            <span></span>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        {{-- ======== POPUP ====== --}}
        <div class="popup-overlay" id="popupOverlay">
            <div class="popup">
                <h3 class="popup-title">
                    <i class="fa-regular fa-circle-check"></i>
                </h3>
                <p class="popup-message">Đặt hàng thành công</p>
                <div class="popup-buttons">
                    <button class="btn-popup" id="continue-shopping-btn">Tiếp tục mua sắm</button>
                    <button class="btn-popup" id="view-cart-btn">Xem giỏ hàng</button>
                </div>
            </div>
        </div>

    </div>
    <!-- ===== JAVASCRIPT ==== -->
    <script src="public/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="public/js/jquery.js"></script>
    <script src="public/js/carousel/owl.carousel.min.js"></script>
    <script src="public/js/detail.js"></script>
    <script src="public/js/main.js"></script>
</body>

</html>
