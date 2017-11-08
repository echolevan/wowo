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
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/login2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('water/normalize.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('water/demo.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('water/component.css')}}"/>
    <script>document.documentElement.className = 'js';</script>
    <style>
        canvas {
            transform: translate(-50%, -50%) scale(1) !important;
        }

        .lm_button {
            background: #266ec1;
        }

        .bl_button {
            background: #d13030;
        }

        .cont_form_login input {
            width: 280px;
        }
        body {
            font-family: Small Head !important;
        }

        button, input, select, textarea {
            font-family: Small Head !important;
        }

        .right_div{
            box-shadow: 0 20px 20px 5px rgba(6, 17, 47, 0.7);
        }
    </style>
</head>
<body>
<div>
    <div class="left" id="slider">
        <main class="site-wrapper">
            <div class="content">
                <div class="slide-wrapper">
                    <div class="slide-item">
                        <img src="images/01.jpg" class="slide-item__image">
                    </div>
                    <div class="slide-item">
                        <img src="images/bac2.jpg" class="slide-item__image">
                    </div>
                    <div class="slide-item">
                        <img src="images/bac3.jpg" class="slide-item__image">
                    </div>
                    <div class="slide-item">
                        <img src="images/02.jpg" class="slide-item__image">
                    </div>
                </div>
                <button class="scene-nav btn_click_back" data-nav="back"></button>
                <button class="scene-nav btn_click_lm" data-nav="lm"></button>
                <button class="scene-nav btn_click_bl" data-nav="bl"></button>
                <button class="scene-nav btn_click_default" data-nav="default"></button>
            </div>
        </main>
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
                    <div style="margin: 15px 0 15px 0; float: right;" class="captcha">{!! captcha_img() !!}</div>
                    <div style="clear: both"></div>
                    @if ($errors->has('captcha'))
                        <p>{{ $errors->first('captcha') }}</p>
                    @endif
                    @if ($errors->has('login_error'))
                        <button class="btn_login my-button">{{ $errors->first('login_error') }}</button>
                    @else
                        <button class="btn_login my-button" onclick="cambiar_login()">登录</button>
                    @endif

                    <div style="clear: both"></div>
                </form>
                <span>
                                    <a href="/password/reset" style="color: #fff">忘记密码</a>
                                    <span>|</span>
                            <a href="{{route('register')}}" style="color: #fff">点击注册</a>
                    <br>
                <a href="{{route('index')}}"
                   style="color: #fff">返回首页</a>
        </span>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/jq.js') }}"></script>
<script src="{{asset('water/demo.js')}}"></script>
<script src="{{asset('water/pixi.min.js')}}"></script>
<script src="{{asset('water/TweenMax.min.js')}}"></script>
<script src="{{asset('water/main3.js')}}"></script>
<script src="{{asset('water/imagesloaded.pkgd.min.js')}}"></script>
<script>
    $(function () {

        imagesLoaded(document.body, () => document.body.classList.remove('loading'));

        var spriteImages = document.querySelectorAll('.slide-item__image');
        var spriteImagesSrc = [];
        var texts = [];

        for (var i = 0; i < spriteImages.length; i++) {

            var img = spriteImages[i];
            // Set the texts you want to display to each slide
            // in a sibling element of your image and edit accordingly
            if (img.nextElementSibling) {
                texts.push(img.nextElementSibling.innerHTML);
            } else {
                texts.push('');
            }
            spriteImagesSrc.push(img.getAttribute('src'));

        }

        var initCanvasSlideshow = new CanvasSlideshow({
            sprites: spriteImagesSrc,
            displacementImage: '/water/clouds.jpg',
            autoPlay: true,
            autoPlaySpeed: [4, 3],
            displaceScale: [5000, 10000],
            interactive: true,
            interactionEvent: 'click', // 'click', 'hover', 'both'
            displaceAutoFit: false,
            dispatchPointerOver: true, // restarts pointerover event after click
            fullScreen: true,
            texts: texts,
            textColor: "#224d94",
            centerSprites: true,
            wacky: true
        });

        var this_camp = 0;


        function get_button(val) {
            if (val === 1) {
                return '.btn_click_lm';
            } else if (val === 2) {
                return '.btn_click_bl';
            } else {
                return '.btn_click_back';
            }
        }

        let camp = $("select[name='camp']").val();
        if (camp) {
            var who_click = get_button(camp);
            $(who_click).click();
        }

        $(".captcha").click(function () {
            let url = '{{captcha_src()}}' + Math.random();
            $(this).find('img').attr('src', url)
        })

        $(".input_e").focus(function () {
            setTimeout(function () {
                if (this_camp === 1 || this_camp === 2 || this_camp === 0) {

                } else {
                    $(".my-button").removeClass('lm_button bl_button');
                    this_camp = 0
                    $('.btn_click_back').click();
                }
            },300)
        })



        $(".input_e").blur(function () {
            $.ajax({
                url: "/check_login_email",
                type: 'post',
                data: {email: $("input[name='email']").val(), '_token': "{{csrf_token()}}"},
                success: function (res) {
                    if (res.sta === 1) {
                        if (this_camp === res.camp) {
                            return false
                        } else {
                            this_camp = res.camp;
                        }
                        $(".my-button").removeClass('lm_button bl_button');
                        $(".my-button").addClass(res.camp === 1 ? 'lm_button' : 'bl_button');
                        var who_click = get_button(res.camp);
                        console.log(who_click)
                        $(who_click).click();
                    } else {
                        if (this_camp === 0 || this_camp === 3) {

                        } else {
                            this_camp = 0
                            $('.btn_click_back').click();
                        }

                    }
                }
            })
        })


        $(".input_p").focus(function () {
            setTimeout(function () {
                if (this_camp === 1 || this_camp === 2 || this_camp === 3) {

                } else {
                    $(".my-button").removeClass('lm_button bl_button');
                    this_camp = 3
                    $('.btn_click_default').click();
                }
            },300)
        })
    })
</script>
</body>
</html>