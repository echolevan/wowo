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
    <link rel="stylesheet" href="{{ asset('/js/admin/style.css') }}">
    <style>
        body{height:100%;background:#16a085;overflow:hidden;}
        canvas{z-index:-1;position:absolute;}
    </style>
</head>
<body>
<div>
    <canvas class="pg-canvas"></canvas>
    <form action="/admin/login" method="post">
        {{csrf_field()}}
        <dl class="admin_login">
            <dt>
                <strong>熊猫人后台管理系统</strong>
                <em>Management System</em>
            </dt>
            @if ($errors->has('login'))
                <p>{{ $errors->first('login') }}</p>
            @endif
            <dd class="user_icon">
                <input type="text" placeholder="邮箱" name="email" class="login_txtbx">
            </dd>
            @if ($errors->has('email'))
                <p>{{ $errors->first('email') }}</p>
            @endif
            <dd class="pwd_icon">
                <input type="password" placeholder="密码" name="password" class="login_txtbx">
            </dd>
            @if ($errors->has('password'))
                <p>{{ $errors->first('password') }}</p>
            @endif
            <dd class="val_icon">
                <div class="checkcode">
                    <input type="text" name="captcha" id="J_codetext" style="width: 50%" placeholder="验证码" maxlength="4" class="login_txtbx">
                    <img style="width: 45%;height: 45px" class="captcha" src="{{captcha_src()}}" alt="">
                </div>
            </dd>
            @if ($errors->has('captcha'))
                <p>{{ $errors->first('captcha') }}</p>
            @endif
            <dd>
                <input type="submit" value="立即登陆" class="submit_btn">
            </dd>
            <dd>
                <p>© 2015-2017 熊猫人 版权所有</p>
            </dd>
        </dl>
    </form>
</div>
<script src="{{ asset('/js/jq.js') }}"></script>
<script src="{{ asset('/js/admin/Particleground.js') }}"></script>
<script>
    $(document).ready(function() {
        //粒子背景特效
        $('body').particleground({
            dotColor: '#5cbdaa',
            lineColor: '#5cbdaa'
        });

        $(".captcha").click(function(){
            let url = '{{captcha_src()}}' + Math.random();
            $('.captcha').attr('src',url)
        })
    });
</script>
</body>
</html>