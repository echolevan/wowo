@extends('layouts.app')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">密码重置</div>
                    <div class="panel-body">
                        <div class="alert alert-danger show_msg" style="display: none">

                        </div>

                        <form class="form-horizontal" method="POST" action="{{ route('user.password.tel') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">用户名</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{ old('name') }}"  placeholder="请输入用户名">

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('tel') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">手机号</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="tel"
                                           value="{{ old('tel') }}"
                                           placeholder="请输入手机号">

                                    @if ($errors->has('tel'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('tel') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">验证码</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control code"  name="code" placeholder="请输入验证码"
                                           style="width: 60%;float: left">
                                    <button type="button" class="btn btn-default pull-right send_code">发送验证码</button>
                                    <div style="clear: both"></div>
                                    @if ($errors->has('code'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary pull-right">
                                        提交
                                    </button>
                                </div>
                                <div class="col-md-6 col-md-offset-4">
                                    <span class="pull-right">
                                        <br>
                                        <a href="/password/reset">通过邮箱号找回</a>
                                    </span>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        /*获取验证码*/
        var isPhone = 1;
        $(".send_code").click(function () {
            checkPhone(); //验证手机号码
            if(isPhone){
                $.ajax({
                    url: "/send_rest_sms",
                    type: 'post',
                    data: {tel: $('#phone').val(), name: $("#name").val(),  '_token': "{{csrf_token()}}"},
                    success: function (res) {
                        if(res.sta === 0){
                            $(".show_msg").text(res.msg).show();
                        }else{
                            $(".show_msg").hide()
                            resetCode(); //倒计时
                        }
                    }
                })
            }else{
                $('#phone').focus();
            }
        })
        //验证手机号码
        function checkPhone(){
            var phone = $('#phone').val();
            var pattern = /^1[34578]\d{9}$/;
            isPhone = 1;
            if(phone == '') {
                $(".show_msg").text('请输入手机号码').show();
                isPhone = 0;
                return;
            }
            if(!pattern.test(phone)){
                console.log(phone)
                $(".show_msg").text('请输入正确的手机号码').show();
                isPhone = 0;
                return;
            }

            $(".show_msg").hide()
        }
        //倒计时
        function resetCode(){
            $('.send_code').attr('disabled',true);
            var second = 60;
            var timer = null;
            timer = setInterval(function(){
                second -= 1;
                if(second > 0 ){
                    $('.send_code').attr('disabled',true).text(second + '秒后重发');
                }else{
                    $('.send_code').attr('disabled',false).text('发送验证码')
                    clearInterval(timer);
                }
            },1000);
        }
    </script>
@endsection
