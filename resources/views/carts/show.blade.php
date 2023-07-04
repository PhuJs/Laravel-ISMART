@extends('layouts.apps')
@section('title', 'Giỏ hàng')
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
                                <a href="{{ request()->url() }}">Giỏ hàng</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12">
                    <!-- ===== CONTENT ==== -->
                    <section id="content">
                        @if (Cart::count() > 0)
                            <div class="table_cart">
                                <table class="table">
                                    <thead class="text-center background_head">
                                        <th scope="col">Mã sản phẩm</th>
                                        <th scope="col">Ảnh sản phẩm</th>
                                        <th scope="col">Tên sản phẩm</th>
                                        <th scope="col">Giá sản phẩm</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Thành tiền</th>
                                        <th scope="col">Tác vụ</th>
                                    </thead>
                                    <tbody>
                                        @foreach (Cart::content() as $row)
                                            <tr class="text-center align-middle">
                                                <td>
                                                    <a href="chi-tiet-san-pham/{{ Str::slug($row->name) }}/{{ $row->id }}"
                                                        class="text-decoration-none">{{ $row->options->code }}</a>
                                                </td>
                                                <td>
                                                    <img src="{{ $row->options->thumbnail }}" alt=""
                                                        class="img-fluid img-thumbnail" style="width:100px; height:auto;">
                                                </td>
                                                <td>
                                                    <a href="chi-tiet-san-pham/{{ Str::slug($row->name) }}/{{ $row->id }}"
                                                        class="text-decoration-none">{{ $row->name }}</a>
                                                </td>
                                                <td>
                                                    <span>{{ currency_format($row->price) }}</span>
                                                </td>
                                                <td>
                                                    <input type="number" name="qty[]" value="{{ $row->qty }}"
                                                        style="width:40px;" class="num-order text-center outline_none"
                                                        data-rowId="{{ $row->rowId }}" data-id="{{ $row->id }}"
                                                        min="1" max="1000">
                                                </td>
                                                <td>
                                                    <span
                                                        class="total_product_cart_{{ $row->id }}">{{ currency_format($row->subtotal) }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('cart.delete', $row->rowId) }}"
                                                        class="d-block text-secondary hover" title="Xóa sản phẩm">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="total_cart">
                                <h3>Tổng giá: <span class="result_total_cart">{{ Cart::total() }}đ</span></h3>
                            </div>
                            <div class="payment_cart">
                                {{-- <a href="" class="btn btn-success fw-semibold me-2">Cập nhật giỏ hàng</a> --}}
                                <a href="gio-hang/thanh-toan" class="btn btn-primary fw-semibold">Thanh toán</a>
                            </div>
                            <div class="cart_text">
                                <p>Tùy chọn mua tiếp hay xóa giỏ hàng</p>
                                <a href="?">Mua tiếp</a><br>
                                <a href="{{ route('cart.delete.all') }}">Xóa giỏ hàng</a>
                            </div>
                        @else
                            <div class="alert alert-primary text-center mb-5 mt-3">
                                <h6>Không có sản phẩm trong giỏ hàng</h6>
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
