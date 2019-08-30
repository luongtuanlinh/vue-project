<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @foreach($cssFiles as $css)
    <link rel="stylesheet" href="{{ asset($css) }}">
    @endforeach
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
@routes
</head>
<body class="hold-transition {{ $theme_skin }} sidebar-mini fixed {{ $collapse }}">
<div class="wrapper" id="app">

@include('partials.header')
@include('partials.left-sidebar')

    <aside class="content-wrapper">
        <router-view></router-view>
    </aside>


</div>
@foreach($jsFiles as $js)
<script src="{{ asset($js) }}"></script>
@endforeach
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
