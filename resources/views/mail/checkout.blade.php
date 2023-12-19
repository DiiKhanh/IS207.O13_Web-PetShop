<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PetShop</title>
</head>
    <body style = "margin:0;
                    padding:0;
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color:darkblue">
        <div>
            <h1 style="text-align:center;
                        margin-top: 50px;
                        color:darkblue;
                    font-size:24px">Thông tin đơn đặt hàng</h1>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Xin chào <strong>{{$name}}</strong></p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Cửa hàng Petshop chúng tôi xin phép gửi thông tin về đơn đặt hàng như sau:</p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Tổng hóa đơn: </p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:30px"><strong>{{$total}}</strong>VND</p>
<p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Địa chỉ: </p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:30px"><strong>{{$address}}</strong></p>
                           <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Số điện thoại: </p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:30px"><strong>{{$phone}}</strong></p>
                         <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Thông tin đơn hàng quý khách vui lòng kiểm tra trong lịch sử mua hàng, nếu có sai sót vui lòng liên hệ cho cửa hàng Petshop chúng tôi nhất nhé</p>
        </div>
</body>
</html>