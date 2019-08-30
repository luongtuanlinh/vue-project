<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('adminLte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>Admin</b>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        @if(session()->has('error_msg'))
            <p class="login-box-msg text-danger">{{ session('error_msg') }}</p>
        @endif

        <form action="" method="post">
            {!! csrf_field() !!}
            <div class="form-group has-feedback @if($errors->has('usr'))has-error @endif">
                <input type="text" class="form-control" name="usr" placeholder="tài khoản / email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if($errors->has('usr'))
                    <span class="help-block">{{ $errors->first('usr') }}</span>
                @endif
            </div>
            <div class="form-group has-feedback @if($errors->has('pwd'))has-error @endif">
                <input type="password" class="form-control" name="pwd" placeholder="mật khẩu">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if($errors->has('pwd'))
                    <span class="help-block">{{ $errors->first('pwd') }}</span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Đăng nhập</button>
                </div>
            </div>
        </form>

        <a href="#">Quên mật khẩu</a><br>

    </div>
</div>
<script src="{{ asset('adminLte/bower_components/jquery/dist/jquery.min.js') }}"></script>
</body>
</html>