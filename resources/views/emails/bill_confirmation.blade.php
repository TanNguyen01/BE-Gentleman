<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận hóa đơn</title>
</head>
<body>
<h1>Xin chào, {{ $user->name }}</h1>
<p>Cảm ơn bạn đã mua hàng. Dưới đây là hóa đơn của bạn:</p>
<h2>Thông tin hóa đơn</h2>
<p>Mã hóa đơn: {{ $bill->id }}</p> <br>
<p>Địa chỉ : {{ $bill->Recipient_address }}</p> <br>
<p> Số điện thoại : {{ $bill->Recipient_phone }}</p><br>
<p> Thanh toán : {{ $bill->pay }}</p><br>
<p>Ngày tạo: {{ $bill->created_at }}</p><br>
<p>Tổng tiền: {{ $bill->total_amount }}</p><br>

<h2>Chi tiết hóa đơn</h2>
<ul>
    @foreach ($billDetails as $detail)
        <li>
            Sản phẩm: {{ $detail['product_name'] }}<br>
            Chất liệu: {{ $detail['attribute'] }}<br>
            Giá: {{ $detail['price'] }}<br>
            Số lượng: {{ $detail['quantity'] }}<br>
            Voucher: {{ $detail['voucher'] }}<br>
            Hình ảnh: <img src="{{ $detail['image'] }}" alt="Hình ảnh sản phẩm"><br>
            {{-- Thêm các thông tin khác nếu cần --}}
        </li>
    @endforeach
</ul>
<p>Cảm ơn bạn đã mua hàng!</p>
</body>
</html>
