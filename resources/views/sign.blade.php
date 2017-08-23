<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>嘿市</title>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/login2.css') }}">
    <style>

    </style>
</head>
<body>
<div>
    <div class="left">
        <div class="filter">
        </div>
    </div>
    <div class="right">
        <h2>LOGIN</h2>
        <form method="POST" action="{{ route('login') }}" class="cont_form_login">
            {{ csrf_field() }}
            <input type="text" name="email" placeholder="邮箱">
            @if ($errors->has('email'))
                <p>{{ $errors->first('email') }}</p>
            @endif
            <br>
            <input type="password" name="password" placeholder="密码">
            @if ($errors->has('password'))
                <p>{{ $errors->first('password') }}</p>
            @endif
            <br>
            <button class="btn_login my-button" onclick="cambiar_login()">登录</button>
        </form>
        <span>没有帐号？
        <a href="">点我注册</a>
        </span>
    </div>
</div>
{{--<script src="{{ mix('/js/app.js') }}"></script>--}}
</body>
</html>


{{--<!DOCTYPE html>--}}
{{--<html lang="{{ config('app.locale') }}">--}}
{{--<head>--}}
{{--<meta charset="utf-8">--}}
{{--<meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
{{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
{{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
{{--<title>窝窝来了</title>--}}
{{--<script>--}}
{{--window.Laravel = {!! json_encode([--}}
{{--'csrfToken' => csrf_token(),--}}
{{--]) !!};--}}
{{--</script>--}}

{{--<link rel="stylesheet" href="{{ asset('/css/login.css') }}">--}}
{{--<style>--}}
{{--.cont_form_login p , .cont_form_sign_up p{--}}
{{--margin-bottom: 0;--}}
{{--color: #a94442;--}}
{{--text-align: left;--}}
{{--padding-left: 30px;--}}
{{--}--}}
{{--.my-button{--}}
{{--background:#44844a;--}}
{{--border-color:#44844a;--}}
{{--border-radius: 0;--}}
{{--}--}}

{{--</style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div id="app">--}}

{{--<div class="cotn_principal">--}}
{{--<div class="cont_centrar">--}}
{{--<div class="cont_login">--}}
{{--<div class="cont_info_log_sign_up">--}}
{{--<div class="col_md_login">--}}
{{--<div class="cont_ba_opcitiy">--}}
{{--<h2>登录</h2>--}}
{{--<p style="font-size: 12px">我的货物都是最好的</p>--}}
{{--<button class="btn_login" onclick="cambiar_login()">登录</button>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="col_md_sign_up">--}}
{{--<div class="cont_ba_opcitiy">--}}
{{--<h2>注册</h2>--}}
{{--<p  style="font-size: 12px">英雄，那是你的过去</p>--}}
{{--<button class="btn_sign_up" onclick="cambiar_sign_up()">注册</button>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="cont_back_info">--}}
{{--<div class="cont_img_back_grey"> <img src="{{asset('images/s.jpg')}}" alt=""> </div>--}}
{{--</div>--}}
{{--<div class="cont_forms">--}}
{{--<div class="cont_img_back_"> <img src="{{asset('images/s.jpg')}}" alt=""> </div>--}}
{{--<form method="POST" action="{{ route('login') }}" class="cont_form_login">--}}
{{--{{ csrf_field() }}--}}
{{--<a href="javascript:void(0)" onclick="ocultar_login_sign_up()"  style="font-size: 12px">返回</a>--}}
{{--<input type="text" name="email" placeholder="邮箱">--}}
{{--@if ($errors->has('email'))--}}
{{--<p>{{ $errors->first('email') }}</p>--}}
{{--@endif--}}
{{--<input type="password" name="password" placeholder="密码">--}}
{{--@if ($errors->has('password'))--}}
{{--<p>{{ $errors->first('password') }}</p>--}}
{{--@endif--}}
{{--<button class="btn_login" onclick="cambiar_login()">登录</button>--}}
{{--</form>--}}
{{--<form method="POST" action="{{ route('register') }}" class="cont_form_sign_up">--}}
{{--{{csrf_field()}}--}}
{{--<a href="javascript:void(0)" onclick="ocultar_login_sign_up()"  style="font-size: 12px">返回</a>--}}
{{--<input type="text" name="email" value="{{ old('email') }}" placeholder="请输入邮箱">--}}
{{--@if ($errors->has('email'))--}}
{{--<p>{{ $errors->first('email') }}</p>--}}
{{--@endif--}}
{{--<input type="text"  name="name" value="{{ old('name') }}" placeholder="请输入用户名">--}}
{{--@if ($errors->has('name'))--}}
{{--<p>{{ $errors->first('name') }}</p>--}}
{{--@endif--}}
{{--<input type="password" name="password" placeholder="请输入密码">--}}
{{--@if ($errors->has('password'))--}}
{{--<p>{{ $errors->first('password') }}</p>--}}
{{--@endif--}}
{{--<input type="password" name="password_confirmation" placeholder="请确认密码">--}}
{{--<button type="submit" class="btn_sign_up">注册</button>--}}
{{--</form>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<button class="btn_login my-button" style="margin-top: 80px" onclick="go_to_index()">--}}
{{--首页--}}
{{--</button>--}}
{{--</div>--}}
{{--</div>--}}
{{--<script src="{{ mix('/js/app.js') }}"></script>--}}
{{--<script src="{{ asset('/js/login.js') }}"></script>--}}
{{--<script>--}}
{{--$(function(){--}}
{{--var type = "{{isset($_GET['type']) ? $_GET['type'] : ''}}";--}}
{{--if(type === 'reg') {--}}
{{--cambiar_sign_up();--}}
{{--}--}}
{{--if(type === 'login') {--}}
{{--cambiar_login();--}}
{{--}--}}

{{--})--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}
