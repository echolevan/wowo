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
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?96b359efcd429833c49f99e3b00484b6";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/login2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/login_index.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/dialog.css') }}">
    <style>
        .lm_button{
            background: #266ec1;
        }
        .bl_button{
            background: #d13030;
        }
        body{
            font-family: Small Head !important;
        }
        button, input, select, textarea{
            font-family: Small Head !important;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="left"  id="slider">
        <img src="{{asset('images/default.jpg')}}" alt="" />
        <img src="{{asset('images/bac1.jpg')}}" alt="" />
        <img src="{{asset('images/bac2.jpg')}}" alt="" />
    </div>
    <div class="filter">

    </div>
    <div class="right">
        <div class="right_div">
            {{--<h2>注册</h2>--}}
            <div>
                <form method="POST" action="{{ route('register') }}" class="cont_form_login">
                    {{csrf_field()}}
                    <select name="camp">
                        <option value="" >请选择阵营</option>
                        <option value="1" @if(old('camp') == 1) selected @endif>联盟</option>
                        <option value="2" @if(old('camp') == 2) selected @endif>部落</option>
                    </select>
                    @if ($errors->has('camp'))
                        <p>{{ $errors->first('camp') }}</p>
                    @endif
                    <input type="text"  name="name" value="{{ old('name') }}" placeholder="用户名(不能包含中文或纯数字)">
                    @if ($errors->has('name'))
                        <p>{{ $errors->first('name') }}</p>
                    @endif
                    <input type="text"  name="nickname" value="{{ old('nickname') }}" placeholder="昵称">
                    @if ($errors->has('nickname'))
                        <p>{{ $errors->first('nickname') }}</p>
                    @endif
                    <input type="password"  name="password" placeholder="密码(大小写字母+数字，不少于8位)" @input="p_len" v-model="password" />
                    <div class="lnu_container">
                        <p v-bind:class="{ uppercase_valid: contains_uppercase }"  style="padding-left: 0">大写字母</p>
                        <p v-bind:class="{ lovercase_valid: contains_lovercase }" style="padding-left: 0">小写字母</p>
                        <p v-bind:class="{ number_valid: contains_number }"  style="padding-left: 0">数字</p>
                        <p v-bind:class="{ uppercase_valid: valid_password_length }"  style="padding-left: 0">8位</p>
                    </div>

                    @if ($errors->has('password'))
                        <p>{{ $errors->first('password') }}</p>
                    @endif
                    <input type="password" name="password_confirmation" placeholder="请确认密码">
                    <input type="text" name="email" value="{{ old('email') }}" placeholder="请输入邮箱">
                    @if ($errors->has('email'))
                        <p>{{ $errors->first('email') }}</p>
                    @endif

                    <input type="text"  name="captcha" placeholder="请输入验证码" style="width: 50%;float: left">
                    <div style="margin: 15px 0 15px 20px; float: left;" class="captcha">{!! captcha_img() !!}</div>
                    <div style="clear: both"></div>
                    @if ($errors->has('captcha'))
                        <p>{{ $errors->first('captcha') }}</p>
                    @endif
                    <button type="submit" class="btn_login my-button">注册</button>
                </form>
                <span>
        <a href="{{route('login')}}" style="color: #fff">点击登录</a>  | <a href="{{route('index')}}" style="color: #fff">返回首页</a>
        </span>
            </div>
        </div>
    </div>

    <div class="dialog dialog--open" style="display: none">
        <div class="dialog__overlay"></div>
        <div class="dialog__content  animated fadeIn"  style="border-radius: 2px">
            <h2>阵营切换间隔时间为 <span style="color: #d13030">30</span> 天，请谨慎选择！</h2>
            <div>
                <button type="button" class="close_dialog" onclick="$('.dialog').hide()" style="border-radius: 2px;background: #d13030;color: #fff">确定</button>
            </div>
        </div>
    </div>

</div>
<script src="{{ asset('/js/jq.js') }}"></script>
<script src="{{ asset('js/login2.js') }}"></script>
<script src="{{ asset('js/jquery.transitions.js') }}"></script>
<script src="{{ asset('js/vue.js') }}"></script>
<script src="{{ asset('js/login_index.js') }}"></script>
<script>
    $(function(){
        var this_camp = ''
        var choice_time = 0
        window.myFlux = new flux.slider('#slider', {
            autoplay: false,
            pagination: false,
            transitions: ['zip'],
            delay: 20
        });
        $(document).on("change","select",function(){
            if(choice_time === 0){
                $(".dialog").fadeIn();
                choice_time++
            }
            if(this_camp === $("select[name='camp']").val()){
                return false
            }else{
                this_camp = $("select[name='camp']").val();
            }
            let camp = $("select[name='camp']").val();
            $(".my-button").removeClass('lm_button bl_button');
            if(camp === '1'){
                $(".my-button").addClass('lm_button');
            }else if(camp === '2'){
                $(".my-button").addClass('bl_button');
            }else{
                $(".my-button").removeClass('lm_button bl_button');
            }
            window.myFlux.showImage(camp);
        });
        $('select').niceSelect();

        let camp = $("select[name='camp']").val();
        if(camp){
            window.myFlux.showImage(camp);
            $(".my-button").addClass(camp === '1' ? 'lm_button' : 'bl_button');
        }

        $(".captcha").click(function(){
            let url = '{{captcha_src()}}' + Math.random();
            $(this).find('img').attr('src',url)
        })
    })
</script>
</body>
</html>

