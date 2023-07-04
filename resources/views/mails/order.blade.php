<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>

<body>
    <div class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="py-5 px-2">
                        <p class="text-center text-uppercase" style="color:darkcyan; font-size:23px; font-weight:700; ">
                            Xác Nhận Đặt Hàng Thành Công Tại Hệ Thống ISMART</p>
                        <p class="mb-1">Chúc mừng khách hàng <span
                                style="font-weight:500; color:blue;">{{ $data_order['customer_name'] }}</span>
                            đã đặt hàng thành công. Đơn hàng của quý khách đang được chuyển giao cho bộ phận xử lí và sẽ
                            có phản hồi với quý khách trong thời gian sớm nhất.
                            Mọi thông tin liên hệ cần giải đáp xin quý khách vui lòng liên hệ về <span
                                style="color:red; font-weight:600;">Hotline: 1900 86 86 86</span> để được hỗ trợ.</p>
                        <h5 class="mt-4" style="font-size:20px;">Thông tin đơn hàng</h5>
                        <p class="mb-2">Mã đơn hàng: <span style="color:red;">{{ $data_order['order_code'] }}</span>
                        </p>
                        <p class="mb-2">Địa chỉ giao hàng: <span
                              style="color:red;">{{ $data_order['address'] }}</span></p>
                        <p class="mb-2">Số lượng sản phẩm: <span style="color:red">{{ $data_order['num_order'] }}
                                sản phẩm</span></p>
                        <p class="mb-2">Tổng tiền: <span style="color:red;">{{ $data_order['total_order'] }}đ</span>
                        </p>
                        @if ($data_order['payment'] == 'PayHome')
                            <p class="mb-2">Phương thức thanh toán: <span style="color:red;">Thanh toán khi giao
                                    hàng</span></p>
                        @else
                            <p class="mb-2">Phương thức thanh toán: <span style="color:red;">Thanh toán qua
                                    thẻ</span></p>
                        @endif
                        <h5 class="mt-4" style="font-size:20px;">Chi tiết đơn hàng</h5>
                        <table border="1" style="text-align:center;" cellspacing="0">
                            <thead>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Tổng tiền</th>
                            </thead>
                            <tbody>
                                @foreach ($list_order as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->subtotal() }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
        integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous">
    </script>
</body>

</html>
