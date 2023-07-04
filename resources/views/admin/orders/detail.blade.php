@extends('layouts.admin')
@section('title', 'Danh sách đơn hàng')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="list_post p-16">
            <div class="card">
                <div class="card-header fw-semibold fs-5">
                    THÔNG TIN ĐƠN HÀNG
                </div>
                <div class="card-body">
                    <ul id="infor_order" class="mb-0">
                        <li>
                            <strong style="color:#555">Mã đơn hàng:</strong>
                            <span class="text-danger ms-4" style="font-weight:500;">{{ $order->order_code }}
                            </span>
                        </li>
                        <li>
                            <strong style="color:#555">Khách hàng:</strong>
                            <span class="text-danger ms-4" style="font-weight:500;">{{ $order->customer_name }}
                            </span>
                        </li>
                        <li>
                            <strong style="color:#555">Số điện thoại:</strong>
                            <span class="text-danger ms-4" style="font-weight:500;">{{ $order->phone }}
                            </span>
                        </li>
                        <li>
                            <strong style="color:#555">Email liên hệ:</strong>
                            <span class="text-danger ms-4" style="font-weight:500;">{{ $order->email }}
                            </span>
                        </li>
                        <li>
                            <strong style="color:#555">Địa chỉ giao hàng:</strong>
                            <span class="text-danger ms-4" style="font-weight:500;">{{ $order->address }}
                            </span>
                        </li>
                        <li>
                            <strong style="color:#555">Tổng tiền đơn hàng:</strong>
                            <span class="text-danger ms-4" style="font-weight:500;">{{ $order->total_order }}đ
                            </span>
                        </li>
                        <li>
                            <strong style="color:#555">Phương thức thanh toán:</strong>
                            <span class="text-danger ms-4" style="font-weight:500;">
                                @if ($order->payment == 'PayHome')
                                    {{ __('Thanh toán khi giao hàng') }}
                                @else
                                    {{ __('Thanh toán qua thẻ') }}
                                @endif
                            </span>
                        </li>
                        <li>
                            <form action="{{ route('admin.order.update_status', ['id' => $order->id, 'status' => request()->input('status'), 'page' => request()->input('page') ]) }}" method="POST">
                                @csrf
                                <label for="">
                                    <strong style="color:#555">Trạng thái đơn hàng:</strong>
                                </label><br>
                                <select name="status_order" id="" class="" style="padding:4px 12px; border:1px solid #ccc; color:#555; outline:none;">
                                    <option value="1" {{ ($order->status == 1)?'selected':'' }}>Chờ xác nhận</option>
                                    <option value="2" {{ ($order->status == 2)?'selected':'' }}>Đang xử lí</option>
                                    <option value="3" {{ ($order->status == 3)?'selected':'' }}>Hoàn thành</option>
                                    <option value="4" {{ ($order->status == 4)?'selected':'' }}>Bị hủy</option>
                                    <option value="5" {{ ($order->status == 5)?'selected':'' }}>Đơn hàng lỗi</option>
                                    <option value="trash" {{ (is_null($order->deleted_at)?'':'selected') }}>Vô hiệu hóa</option>
                                </select>
                                <input type="submit" name="submit_form" value="Cập nhật" style="padding:4px 10px; border:1px solid #ccc; color:#555;" class="hover">
                            </form>
                        </li>
                    </ul>
                    <div class="note">
                        <p class="mb-0"><strong style="color:#555;">Ghi chú:</strong></p>
                        <p class="text-danger" style="font-weight:500;">{{ $order->note }}</p>
                    </div>
                    <hr>
                    <p class="infor_order_detail">Sản Phẩm Đã Mua</p>
                    <table class="table text-center">
                        <thead class="table-light">
                            <th scope="col">STT</th>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Số lượng mua</th>
                            <th scope="col">Tổng tiền</th>
                        </thead>
                        <tbody>
                            @php
                                $stt = 0;
                            @endphp
                            @foreach ($list_order_detail as $item)
                                @php
                                    $stt++;
                                @endphp
                                <tr class="align-middle">
                                    <td>{{ $stt }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <img src="{{ $item->thumbnail }}" alt="" style="width:60px; height:auto;"
                                            class="img-fluid img-thumbnail">
                                    </td>
                                    <td>
                                        {{ currency_format($item->price) }}
                                    </td>
                                    <td>{{ $item->qty }}</td>
                                    <td>
                                        {{ $item->sub_total }}đ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ route('admin.order.show', $data_redirect) }}" class="btn btn-primary mt-4 float-end">Trở lại danh
                sách</a>
        </div>
    </div>
    </div>
    </div>
@endsection
