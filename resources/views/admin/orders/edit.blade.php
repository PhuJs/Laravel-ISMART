@extends('layouts.admin')
@section('title', 'Danh sách đơn hàng')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="list_post p-16">
            <div class="card">
                <div class="card-header fw-semibold fs-5">
                    Cập Nhật Thông Tin Khách Hàng
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="section_cart pe-3">
                                <div class="section_cart_content">
                                    <div class="container-fluid">
                                        <form action="{{route('admin.order.update',['id' => $order->id, 'status' => $status, 'page' => $page])}}" method="POST">
                                            @csrf 
                                            <div class="row">
                                                <div class="col-6 gx-0">
                                                    <div class="mb-3 pe-2">
                                                        <label for="" class="form-label">Họ tên <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" class="form-control" name="customer_name"
                                                            value="{{ $order->customer_name }}">
                                                        @error('customer_name')
                                                            <span class="d-block text-danger mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-6 gx-0">
                                                    <div class="mb-3 ps-2">
                                                        <label for="" class="form-label">Email</label>
                                                        <input type="text" class="form-control" name="email"
                                                            value="{{ $order->email }}">
                                                        @error('email')
                                                            <span class="d-block text-danger mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-6 gx-0">
                                                    <div class="mb-3 pe-2">
                                                        <label for="" class="form-label">Số điện thoại <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" class="form-control" name="phone_number"
                                                            value="{{ $order->phone }}">
                                                        @error('phone_number')
                                                            <span class="d-block text-danger mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 gx-0">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Địa chỉ khách hàng<span
                                                                class="text-danger"></span></label>
                                                        <input type="text" placeholder=""
                                                            class="form-control" name="address"
                                                            value="{{ $order->address }}">
                                                        @error('address')
                                                            <span class="d-block text-danger mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12 gx-0">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Ghi chú <span
                                                                class="text-danger"></span></label>
                                                        <textarea name="note" id="" rows="5" class="form-control">{{ $order->note }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 gx-0">
                                                    <div class="mb-3">
                                                        <input type="submit" name="update-submit" value="Cập nhật"
                                                            class="btn btn-primary">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
