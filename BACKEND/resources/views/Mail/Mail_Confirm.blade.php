
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>JWT-Laravel</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
	<div class="container" style="background: #9bf8ff;border-radius: 12px;padding:15px;">
		<div class="col-md-12" >

			<p style="text-align: center;color: #e40909;font-size: 18px;font-weight: 700;">Thank you for using the service on our website. Please verify your account to start using the service.</p>
			<div class="row" style="background:#eef4f4de;padding: 15px">

				<div class="col-md-6" style="text-align: center;color: #1a1a1a;font-weight: bold;font-size: 30px">
					<h4 style="margin:0">IVS</h4>
				</div>

				<div class="col-md-6 logo"  style="color: #000">
					<p style="font-size: 30px;">Hi: <strong style="color: #ff2525;font-size: 18px;">{{$data['name']}}</strong></p>
				</div>

				<div class="col-md-12">
					<p style="color:#902020;font-size: 17px;">You have registered an account at our website with the following information </p>
					<p>Email: <strong style="text-transform: uppercase;color:#a22828">{{$data['email']}}</strong></p>
					<p>Phone: <strong style="text-transform: uppercase;color:#a22828">{{$data['phone']}}</strong></p>
					<p>Shop Name: <strong style="text-transform: uppercase;color:#a22828">{{$data['shopname']}}<strong></p>
					<p>Hot Line: <strong style="text-transform: uppercase;color:#a22828">{{$data['hotline']}}</strong></p>
					<p>Address: <strong style="text-transform: uppercase;color:#a22828">{{$data['address']}}</strong></p>
					<p>Tax Code: <strong style="text-transform: uppercase;color:#a22828">{{$data['taxcode']}}</strong></p>
                    </div>
                    <a align="right" style="color:#101010" href="{{route('confim_mail',['token'=>$data['token'], 'email' =>$data['email'] ])}}" class="btn">Confirm</a>
 </body>
</html>

