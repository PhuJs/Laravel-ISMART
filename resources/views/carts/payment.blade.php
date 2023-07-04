@extends('layouts.apps')
@section('title', 'Thanh toán đơn hàng')
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
                                <a href="{{ request()->url() }}">Thanh toán</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12">
                    <!-- ===== CONTENT ==== -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-4">
                                <form action="{{ route('cart.payment.order') }}" method="POST">
                                    @csrf
                                    <div class="section_cart pe-3">
                                        <div class="section_cart_head">
                                            <h3 class="section_cart_title">
                                                Thông tin khách hàng
                                            </h3>
                                        </div>
                                        <div class="section_cart_content">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-6 gx-0">
                                                        <div class="mb-3 pe-2">
                                                            <label for="" class="form-label">Họ tên <span
                                                                    class="text-danger">(*)</span></label>
                                                            <input type="text" class="form-control" name="customer_name" value="{{old('customer_name')}}">
                                                            @error('customer_name')
                                                                <span
                                                                    class="d-block text-danger mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-6 gx-0">
                                                        <div class="mb-3 ps-2">
                                                            <label for="" class="form-label">Email</label>
                                                            <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                                                            @error('email')
                                                                <span
                                                                    class="d-block text-danger mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-6 gx-0">
                                                        <div class="mb-3 pe-2">
                                                            <label for="" class="form-label">Số điện thoại <span
                                                                    class="text-danger">(*)</span></label>
                                                            <input type="text" class="form-control" name="phone_number" value="{{old('phone_number')}}">
                                                            @error('phone_number')
                                                                <span
                                                                    class="d-block text-danger mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-6 gx-0">
                                                        <div class="mb-3 ps-2">
                                                            <label for="" class="form-label">Tỉnh/Thành Phố <span
                                                                    class="text-danger">(*)</span></label>
                                                            <select class="form-select" name="province" id="province">
                                                                <option value="0">Chọn Tỉnh Thành Phố</option>
                                                                @foreach ($list_province as $province)
                                                                    <option value="{{ $province->province_id }}">
                                                                        {{ $province->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('province')
                                                                <span
                                                                    class="d-block text-danger mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-6 gx-0">
                                                        <div class="mb-3 pe-2">
                                                            <label for="" class="form-label">Quận/Huyện <span
                                                                    class="text-danger">(*)</span></label>
                                                            <select class="form-select" name="district" id="district">
                                                                <option value="0">Chọn Quận Huyện</option>
                                                                <option value="1">One</option>
                                                                <option value="2">Two</option>
                                                                <option value="3">Three</option>
                                                            </select>
                                                            @error('district')
                                                                <span
                                                                    class="d-block text-danger mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-6 gx-0">
                                                        <div class="mb-3 ps-2">
                                                            <label for="" class="form-label">Phường/Xã <span
                                                                    class="text-danger">(*)</span></label>
                                                            <select class="form-select" name="wards" id="wards">
                                                                <option value="0">Chọn Phường/Xã</option>
                                                                <option value="1">One</option>
                                                                <option value="2">Two</option>
                                                                <option value="3">Three</option>
                                                            </select>
                                                            @error('wards')
                                                                <span
                                                                    class="d-block text-danger mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12 gx-0">
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Địa chỉ <span
                                                                    class="text-danger">(*)</span></label>
                                                            <input type="text" placeholder="Ví dụ: 185A, Đ.Nguyễn Trãi"
                                                                class="form-control" name="address" value="{{old('address')}}">
                                                            @error('address')
                                                                <span
                                                                    class="d-block text-danger mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12 gx-0">
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Ghi chú <span
                                                                    class="text-danger">(nếu có)</span></label>
                                                            <textarea name="note" id="" rows="5" class="form-control">{{old('note')}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="section_cart pe-3">
                                    <div class="section_cart_head">
                                        <h3 class="section_cart_title">
                                            Thông tin đơn hàng
                                        </h3>
                                    </div>
                                    <div class="section_cart_content">
                                        <div class="box_order">
                                            @if (Cart::count() > 0)
                                                <ul class="list_order">
                                                    <li>
                                                        <p>
                                                            sản phẩm
                                                        </p>
                                                        <p>
                                                            tổng
                                                        </p>
                                                    </li>
                                                    <!-- -----------  -->

                                                    @foreach (Cart::content() as $row)
                                                        <li>
                                                            <p>
                                                                {{ $row->name }} <span
                                                                    class="text-danger ms-5 fw-semibold">
                                                                    x{{ $row->qty }}</span>
                                                            </p>
                                                            <p>
                                                                {{ currency_format($row->subtotal) }}
                                                            </p>
                                                        </li>
                                                    @endforeach

                                                    <!-- ----------  -->
                                                    <li>
                                                        <p>
                                                            Tổng đơn hàng
                                                        </p>
                                                        <p>
                                                            {{ Cart::total() }}đ
                                                        </p>
                                                    </li>
                                                </ul>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input" id="Input-2"
                                                            name="payment" checked value="PayHome">
                                                        <label for="Input-2" class="form-check-label">Thanh toán khi giao hàng
                                                            </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input" id="Input-1"
                                                            name="payment" value="PayCard">
                                                        <label for="Input-1" class="form-check-label">Thanh toán qua
                                                            thẻ</label>
                                                    </div>
                                                </div>
                                                <div class="pt-3 float-end">
                                                    <input type="submit" name="order_submit" class="btn_order"
                                                        value="Đặt Hàng">
                                                </div>
                                            @else
                                                <div class="alert alert-danger">
                                                    Không có sản phẩm trong giỏ hàng
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
