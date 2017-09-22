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
    </style>
</head>
<body>
<div class="header">
    <a href="{{env('APP_URL')}}">陕西熊猫人网络科技有限公司</a>
</div>
<p style="            margin-top: 50px;
">
    <span>主题分类：</span><span>{{$plug->type === 1 ? 'WA':'TWM'}}/{{\App\Tag::where('id',$plug->type_one)->value('name') ? : ''}}/{{\App\Tag::where('id',$plug->type_two)->value('name') ? : ''}}</span>
</p>
<p>
    <span>主题名称：</span><span style="color: #c41f3b">{{$plug->title}}</span>
</p>
<div class="content">
   <div class="main">
       <p>
           <a href="{{env('APP_URL')}}/admin/login" style="    font-family: Avenir, Helvetica, sans-serif;
    box-sizing: border-box;
    border-radius: 3px;
    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
    color: #FFF;
    display: inline-block;
    text-decoration: none;
    -webkit-text-size-adjust: none;
    background-color: #3097D1;
    border-top: 10px solid #3097D1;
    border-right: 18px solid #3097D1;
    border-bottom: 10px solid #3097D1;
    border-left: 18px solid #3097D1;">登录审核
           </a>
       </p>
   </div>
</div>

<div class="foot">
    <a href="{{env('APP_URL')}}">© 2017 陕西熊猫人网络科技有限公司. All rights reserved.</a>
</div>
</body>
</html>