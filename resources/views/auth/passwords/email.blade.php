@extends('layouts.app')
@section('css')
    <style>
        .captcha img {
            height: 36px;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">密码重置</div>
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form-horizontal" method="POST" action="{{ route('user.password.email') }}">
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

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">安全邮箱</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}"  placeholder="请输入邮箱地址">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">验证码</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="captcha" placeholder="请输入验证码"
                                           style="width: 70%;float: left">
                                    <div style=" float: right;" class="captcha">{!! captcha_img() !!}</div>
                                    <div style="clear: both"></div>
                                    @if ($errors->has('captcha'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary pull-right">
                                        发送邮件
                                    </button>
                                </div>
                                <div class="col-md-6 col-md-offset-4">
                                    <span class="pull-right">
                                        <br>
                                           <a  href="/password/sms" class="btn ivu-btn-ghost pull-right">
                                        通过手机找回
                                            </a>
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
        $(".captcha").click(function () {
            let url = '{{captcha_src()}}' + Math.random();
            $(this).find('img').attr('src', url)
        })
    </script>
@endsection
