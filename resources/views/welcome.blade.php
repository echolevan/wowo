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
    <link rel="stylesheet" href="{{ asset('/css/dialog.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/simple.slide.css') }}">
    <style>
        body {
            background-color:#f5f5f5;
        }
        .show_is_active{
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(55, 58, 71, 0.9);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999999;
        }

        .show_is_active .center_is_active{
            width: 500px;
            height: 150px;
            background: #fff;
            padding: 3em;
            text-align: center;
            border-radius: 5px;
        }
        .show_is_active .center_is_active p{
            color: #333;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div id="app">
    @if ($errors->has('is_active_ok'))
        <div class="show_is_active">
            <div class="center_is_active">
                <p>邮箱激活成功</p>
                <br>
                <button style="border-radius: 2px;" class="close_dialog  ivu-btn-primary {{$errors->first('is_active_ok') == 2 ? 'bl_button_color' : ''}}" onclick="$('.show_is_active').remove()">确定</button>
            </div>
        </div>
    @endif
    <v-index></v-index>
</div>
<script src="{{ mix('/js/app.js') }}"></script>

<script>
</script>
@if($tool === 1)
    <script>
        var xlm_wid = "{{$xlm_wid}}";
        var xlm_url = 'https://www.xianliao.me/';
        var xlm_uid = "{{$xlm_uid}}"
        var xlm_name = "{{$xlm_name}}"; //登录用户的用户名，游客使用空字符
        var xlm_avatar = "{{$xlm_avatar}}";//登录用户的头像URL，游客使用空字符
        var xlm_time = "{{$time}}"; //现在服务器的Linux timestamp, 如：1481673726
        var xlm_hash = "{{$hash}}"; //为保障用户的登录安全，xlm_hash须在后台生成，见下附的xlm_hash的生成方法
    </script>
    <script type='text/javascript' charset='UTF-8' src='https://www.xianliao.me/embed.js'></script>
@endif
</body>
</html>
