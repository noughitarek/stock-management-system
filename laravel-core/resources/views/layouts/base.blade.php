<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @yield('head')
</head>
<body>
	{{--
	@if(App\Models\FacebookMessage::count()>1000000)
    <div class="banner">
		Congratulations! We've reached {{App\Models\FacebookMessage::count()/1000000}}M facebook messages on our website!
    </div>
	@endif
	--}}
    @yield('body')
</body>
</html>