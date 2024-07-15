<!DOCTYPE html>
<html>

<head>
    <title>Xác nhận hóa đơn</title>
</head>

<body>
    <h1>Xin chào, {{ $user->name }}</h1>
    <p style="font-size: 16px;">Tình trạng đơn hàng của bạn: {{ $bill->status }}</p>
    <p style="font-size: 16px;">Ngày đặt hàng: {{ $bill->created_at }}</p>
    <p>Thông tin sản phẩm: </p>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">Sản phẩm
                </th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">Giá</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">Kích
                    thước/Màu</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">Số lượng
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($billDetails as $detail)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $detail['product_name'] }}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">
                    {{ $detail['price'] }}
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $detail['attribute'] }} </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $detail['quantity'] }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <p style="font-size: 16px;">Địa chỉ giao hàng:
    <h3>{{ $bill->Recipient_address }}</h3>
    </p>
    <p style="font-size: 16px;">Thông tin liên hệ:
    <h3>{{ $bill->Recipient_phone }}</h3>
    </p>
    <p style="font-size: 16px;">Tổng tiền:
    <h3> {{ $bill->total_amount }}VND</h3>
    </p>
</body>

</html>