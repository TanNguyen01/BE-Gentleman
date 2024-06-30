

<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đặt hàng</title>
</head>
<body>
    <h1>Cảm ơn bạn đã đặt hàng!</h1>
    <p>Mã đơn hàng: {{ $order->id }}</p>
<p>Người đặt: {{ $order->user->name }}</p>
<!-- Thông tin chi tiết khác về đơn hàng -->
</body>
</html>

