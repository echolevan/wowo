<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            padding: 15px;
        }

        .header , .foot{
            width: 100%;
            line-height: 80px;
            background-color: #f5f8fa;
            text-align: center;
        }

        .header a {
            color: #bbbfc3;
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
        }

        .foot a{
            color: #bbbfc3;
            font-size: 14px;
            text-decoration: none;
        }

        .content{
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 50px;
        }
        .content .main{
            max-width: 50%;
            margin: 0 auto;
        }
        .cent_div{
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
<div class="header">
    <a href="{{env('APP_URL')}}">陕西熊猫人网络科技有限公司</a>
</div>

<div class="cent_div">
    <div  style=" margin: 50px 0;">
        <p>
            <span>反馈内容：</span><span>{{$feedBack->feedback}}</span>
        </p>
        <p>
            <span>邮箱：</span><span style="color: #c41f3b">{{$feedBack->tel}}</span>
        </p>
    </div>
</div>

<div class="foot">
    <a href="{{env('APP_URL')}}">© 2017 陕西熊猫人网络科技有限公司. All rights reserved.</a>
</div>
</body>
</html>