<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>窝窝来了</title>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?96b359efcd429833c49f99e3b00484b6";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/login2.css') }}">
    <style>

    </style>
</head>
<body>
<div>
    <div class="left"  id="slider">
        <img src="{{asset('images/default.png')}}" alt="" />
        <img src="{{asset('images/bac2.png')}}" alt="" />
        <img src="{{asset('images/bac3.png')}}" alt="" />
    </div>
    <div class="filter">

    </div>
    <div class="right">
        <div class="right_div">
            {{--<h2>登录</h2>--}}
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
        <a href="{{route('register')}}">点我注册</a>  OR <a href="{{route('index')}}">返回首页</a>
        </span>
        </div>
    </div>
</div>
<script src="{{ asset('/js/jq.js') }}"></script>
<script src="{{ asset('js/login2.js') }}"></script>
<script>
    $(function(){
        window.myFlux = new flux.slider('#slider', {
            autoplay: false,
            pagination: false,
            transitions: ['warp']
        });
        $(document).on("click","#ccc",function(){
            window.myFlux.next();
        })
    })
</script>
</body>
</html>