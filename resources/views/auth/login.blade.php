<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <title>嘿市 - 分享快乐 愉悦他人</title>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script>
        var _hmt = _hmt || [];
        (function () {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?96b359efcd429833c49f99e3b00484b6";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/login2.css') }}">
    <style>
        .lm_button {
            background: #266ec1;
        }

        .bl_button {
            background: #d13030;
        }

        body {
            font-family: Small Head !important;
        }

        button, input, select, textarea {
            font-family: Small Head !important;
        }
    </style>
</head>
<body>
<div>
    <div class="left" id="slider">
        <img src="{{asset('images/01.jpg')}}" alt=""/>
        <img src="{{asset('images/bac2.jpg')}}" alt=""/>
        <img src="{{asset('images/bac3.jpg')}}" alt=""/>
        <img src="{{asset('images/02.jpg')}}" alt=""/>
    </div>
    <div class="filter">

    </div>
    <div class="right">
        <div class="right_div">
            {{--<h2>登录</h2>--}}
            <div>
                <form method="POST" action="{{ route('login') }}" class="cont_form_login">
                    {{ csrf_field() }}
                    <input type="text" name="email" class="input_e" placeholder="用户名/嘿市ID">
                    @if ($errors->has('email'))
                        <p>{{ $errors->first('email') }}</p>
                    @endif
                    <br>
                    <input type="password" name="password" class="input_p" placeholder="密码">
                    @if ($errors->has('password'))
                        <p>{{ $errors->first('password') }}</p>
                    @endif
                    <br>
                    <input type="text" name="captcha" placeholder="请输入验证码" style="width: 50%;float: left">
                    <div style="margin: 15px 0 15px 20px; float: left;" class="captcha">{!! captcha_img() !!}</div>
                    <div style="clear: both"></div>
                    @if ($errors->has('captcha'))
                        <p>{{ $errors->first('captcha') }}</p>
                    @endif
                    @if ($errors->has('login_error'))
                        <button class="btn_login my-button">{{ $errors->first('login_error') }}</button>
                    @else
                        <button class="btn_login my-button" onclick="cambiar_login()">登录</button>
                    @endif
                </form>
                <span>没有帐号？
        <a href="{{route('register')}}" style="color: #fff">点击注册</a>
            <p>
                <a href="/password/reset" style="color: #fff">忘记密码</a>
                <span>|</span>
                <a href="{{route('index')}}"
                   style="color: #fff">返回首页</a></p>
        </span>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/jq.js') }}"></script>
<script src="{{ asset('js/login2.js') }}"></script>
<script>
    $(function () {
        var this_camp = 0;
        let camp = $("select[name='camp']").val();
        if (camp) {
            window.myFlux.showImage(camp);
        }

        window.myFlux = new flux.slider('#slider', {
            autoplay: false,
            pagination: false,
            transitions: ['zip'],
            delay: 20
        });

        $(".captcha").click(function () {
            let url = '{{captcha_src()}}' + Math.random();
            $(this).find('img').attr('src', url)
        })

        $(".input_e").focus(function () {
            if (this_camp === 1 || this_camp === 2 || this_camp === 0) {

            } else {
                $(".my-button").removeClass('lm_button bl_button');
                this_camp = 0
                window.myFlux.showImage(0);
            }
        })

        $(".input_p").focus(function () {
            if (this_camp === 1 || this_camp === 2 || this_camp === 3) {

            } else {
                $(".my-button").removeClass('lm_button bl_button');
                this_camp = 3
                window.myFlux.showImage(3);
            }
        })

        $(".input_e").blur(function () {
            $.ajax({
                url: "/check_login_email",
                type: 'post',
                data: {email: $("input[name='email']").val(), '_token': "{{csrf_token()}}"},
                success: function (res) {
                    console.log(res)
                    if (res.sta === 1) {
                        if (this_camp === res.camp) {
                            return false
                        } else {
                            this_camp = res.camp;
                        }
                        $(".my-button").removeClass('lm_button bl_button');
                        $(".my-button").addClass(res.camp === 1 ? 'lm_button' : 'bl_button');
                        window.myFlux.showImage(res.camp);
                    } else {
                        if (this_camp === 0 || this_camp === 3) {

                        } else {
                            this_camp = 0
                            window.myFlux.showImage(0);
                        }

                    }
                }
            })
        })
    })
</script>
</body>
</html>