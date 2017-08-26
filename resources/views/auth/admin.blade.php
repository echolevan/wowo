<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>嘿市</title>
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
        canvas{z-index:-1;position:absolute;}
    </style>
</head>
<body>
<div class="main">
    <form action="/admin/login" class="login_admin" method="post">
        {{csrf_field()}}
        <dl class="admin_login">
            <dt>
                <strong>熊猫人后台管理系统</strong>
                <em>Management System</em>
            </dt>
            @if ($errors->has('login'))
                <p class="error_l">{{ $errors->first('login') }}</p>
            @endif
            <dd class="user_icon">
                <input type="text" placeholder="用户名/嘿市ID" name="email" class="login_txtbx" style="color: #fff">
            </dd>
            @if ($errors->has('email'))
                <p class="error_l">{{ $errors->first('email') }}</p>
            @endif
            <dd class="pwd_icon">
                <input type="password" placeholder="密码" name="password" class="login_txtbx"  style="color: #fff">
            </dd>
            @if ($errors->has('password'))
                <p class="error_l">{{ $errors->first('password') }}</p>
            @endif
            <dd class="val_icon">
                <div class="checkcode">
                    <input type="text" name="captcha" id="J_codetext" style="width: 50%;color: #fff" placeholder="验证码" maxlength="4" class="login_txtbx">
                    <img style="width: 45%;height: 45px;border-radius: 5px;" class="captcha" src="{{captcha_src()}}" alt="">
                </div>
            </dd>
            @if ($errors->has('captcha'))
                <p class="error_l">{{ $errors->first('captcha') }}</p>
            @endif
            <dd>
                @if ($errors->has('login_error'))
                    <input type="submit" value="{{ $errors->first('login_error') }}" disabled="disabled" class="submit_btn">
                @else
                    <input type="submit" value="立即登陆" class="submit_btn">
                @endif
            </dd>
            <dd>
                <p style="color: #333">Copyright © 2017 陕西熊猫人网络科技有限公司 版权所有</p>
            </dd>
        </dl>
    </form>
</div>
<script src="{{ asset('/js/jq.js') }}"></script>
<script src="{{ asset('/js/admin/three.js') }}"></script>
<script src="{{ asset('/js/admin/Projector.js') }}"></script>
<script src="{{ asset('/js/admin/CanvasRenderer.js') }}"></script>
<script src="{{ asset('/js/admin/stats.min.js') }}"></script>
<script>
    var SEPARATION = 100, AMOUNTX = 50, AMOUNTY = 50;
    var container, stats;
    var camera, scene, renderer;
    var particles, particle, count = 0;
    var mouseX = 0, mouseY = 0;
    var windowHalfX = window.innerWidth / 2;
    var windowHalfY = window.innerHeight / 2;
    init();
    animate();
    function init() {
        container = document.createElement( 'div' );
        container.style.position = 'relative';
        container.style.top = '100px';
        document.body.appendChild( container );
        camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 1, 10000 );
        camera.position.z = 1000;
        scene = new THREE.Scene();
        particles = new Array();
        var PI2 = Math.PI * 2;
        var material = new THREE.SpriteCanvasMaterial( {
            color: 0x1E90FF,
            program: function ( context ) {
                context.beginPath();
                context.arc( 0, 0, 0.5, 0, PI2, true );
                context.fill();
            }
        } );
        var i = 0;
        for ( var ix = 0; ix < AMOUNTX; ix ++ ) {
            for ( var iy = 0; iy < AMOUNTY; iy ++ ) {
                particle = particles[ i ++ ] = new THREE.Sprite( material );
                particle.position.x = ix * SEPARATION - ( ( AMOUNTX * SEPARATION ) / 2 );
                particle.position.z = iy * SEPARATION - ( ( AMOUNTY * SEPARATION ) / 2 );
                scene.add( particle );
            }
        }
        renderer = new THREE.CanvasRenderer();
        renderer.setPixelRatio( window.devicePixelRatio );
        renderer.setSize( window.innerWidth, window.innerHeight );
        container.appendChild( renderer.domElement );
        stats = new Stats();
        container.appendChild( stats.dom );
        document.addEventListener( 'mousemove', onDocumentMouseMove, false );
        document.addEventListener( 'touchstart', onDocumentTouchStart, false );
        document.addEventListener( 'touchmove', onDocumentTouchMove, false );
        //
        window.addEventListener( 'resize', onWindowResize, false );
    }
    function onWindowResize() {
        windowHalfX = window.innerWidth / 2;
        windowHalfY = window.innerHeight / 2;
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize( window.innerWidth, window.innerHeight );
    }
    //
    function onDocumentMouseMove( event ) {
        mouseX = event.clientX - windowHalfX;
        mouseY = event.clientY - windowHalfY;
    }
    function onDocumentTouchStart( event ) {
        if ( event.touches.length === 1 ) {
            event.preventDefault();
            mouseX = event.touches[ 0 ].pageX - windowHalfX;
            mouseY = event.touches[ 0 ].pageY - windowHalfY;
        }
    }
    function onDocumentTouchMove( event ) {
        if ( event.touches.length === 1 ) {
            event.preventDefault();
            mouseX = event.touches[ 0 ].pageX - windowHalfX;
            mouseY = event.touches[ 0 ].pageY - windowHalfY;
        }
    }
    //
    function animate() {
        requestAnimationFrame( animate );
        render();
        stats.update();
    }
    function render() {
        camera.position.x += ( mouseX - camera.position.x ) * .05;
//        camera.position.y += ( - mouseY - camera.position.y ) * .05;
        camera.lookAt( scene.position );
        camera.position.y = 364;

        var i = 0;
        for ( var ix = 0; ix < AMOUNTX; ix ++ ) {
            for ( var iy = 0; iy < AMOUNTY; iy ++ ) {
                particle = particles[ i++ ];
                particle.position.y = ( Math.sin( ( ix + count ) * 0.3 ) * 50 ) +
                    ( Math.sin( ( iy + count ) * 0.5 ) * 50 );
                particle.scale.x = particle.scale.y = ( Math.sin( ( ix + count ) * 0.3 ) + 1 ) * 4 +
                    ( Math.sin( ( iy + count ) * 0.5 ) + 1 ) * 4;
            }
        }
        renderer.render( scene, camera );
        count += 0.1;
    }
</script>
<script>
    $(".captcha").click(function(){
        let url = '{{captcha_src()}}' + Math.random();
        $('.captcha').attr('src',url)
    })
</script>
</body>
</html>