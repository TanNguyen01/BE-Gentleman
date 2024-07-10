

<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận hóa đơn</title>
</head>
<body>
<h1>Xin chào, {{ $user->name }}</h1>
<p>Cảm ơn bạn đã mua hàng. Dưới đây là chi tiết hóa đơn của bạn:</p>
<ul>
    @foreach ($billDetails as $detail)
        <li>

            San pham : {{ $detail['product_name'] }}<br>
            Chat lieu:  {{$detail['attribute']}}<br>
            Giá: {{ $detail['price'] }}<br>
            Số lượng: {{ $detail['quantity'] }}<br>
            Ma hoa don: {{$detail['bill_id']}}<br>
            Voucher: {{$detail['voucher']}}<br>
            Hinh anh: {{$detail['image']}}<br>
            {{-- Thêm các thông tin khác nếu cần --}}
        </li>
    @endforeach
</ul>
<p>Cảm ơn bạn đã mua hàng!</p>
</body>
</html>

