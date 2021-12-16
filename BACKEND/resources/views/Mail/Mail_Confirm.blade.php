<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Xác Nhận Đơn Hàng</title>
</head>
<body>
    <p>Hi {{$data['name']}}</p>
    <p>Bạn đã đăng ký thành công tại Website của chúng tôi!</p>
    <br>
    <table style="width: 600px; text-align:right;">
    <thead>
    <tr>
            <th>Shop name</th>
            <th>Address</th>
            <th>Hotline</th>
            <th>Taxcode</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$data['shopname']}}</td>
                <td>{{$data['address']}}</td>
                <td>{{$data['hotline']}}</td>
                <td>{{$data['taxcode']}}</td>
            </tr>
        </tbody>
    </table>
    <a href="{{route('confim_mail',['token'=>$data['token'], 'email' =>$data['email'] ])}}" class="btn">Confirm</a>
</body>
</html>
