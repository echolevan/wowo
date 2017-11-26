<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HomeController@index')->name('index');
Route::get('/getAgentInfo', 'ChartController@getAgentInfo');


Route::get('/aoao', function (){


    $plugs = \App\Plug::where('type',3)->select('id','type','content')->get();
    foreach ($plugs as $k => $v){
        $new_t = env("UPLOAD_URL").$v->content;
        \App\Plug::where('id',$v->id)->update(['content'=>$new_t]);
    }

    echo "ok";

});

Route::get('/aoao2', function (){

    $thumbs = \App\Thumb::select('id','type','content')->get();
    foreach ($thumbs as $k => $v){
        $old_t = parse_url($v->thumb);
        $new_t = env("UPLOAD_URL")."/images".$old_t['path'];
        \App\Thumb::where('id',$v->id)->update(['thumb'=>$new_t]);
    }

    $tags = \App\Tag::all();
    foreach ($tags as $k => $v){
        $old_t = parse_url($v->thumb);
        $new_t = env("UPLOAD_URL")."/images".$old_t['path'];
        \App\Thumb::where('id',$v->id)->update(['thumb'=>$new_t]);
    }

    $bms = \App\Bm::all();
    foreach ($bms as $k => $v){
        $new_t = env("UPLOAD_URL").$v->url;
        \App\Thumb::where('id',$v->id)->update(['url'=>$new_t]);
    }

    echo "ok";

});

//
Route::get('/ban', function () {
    $ban = file_get_contents('../public/upload/ban.json');
    $ban = json_decode(trim($ban,chr(239).chr(187).chr(191)),true);
    foreach ($ban as $v){
        $count = \App\Tool::where('name','nickname')->where('value',$v)->count();
        if($count === 0){
            \App\Tool::create([
                'name' => 'nickname',
                'value' => $v,
            ]);
        }
    }
    return 'ok';
});

Route::get('/password/sms', function (){
    return view('auth.passwords.sms');
});
Route::post('/send_rest_sms', 'UserController@send_rest_sms');

Route::any('/aplipay_notify', 'PayController@alipay_notify');
Route::any('/wechat_notify', 'PayController@wechat_notify');
Route::any('/return', 'PayController@return_url');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get("ads",'AdController@ads');
Route::post("plug_search",'PlugController@search');

Route::post('/user/password/email', 'UserController@password_email')->name('user.password.email');
Route::post('/user/password/tel', 'UserController@password_tel')->name('user.password.tel');
Route::post('/user/password/request_sms', 'UserController@request_sms')->name('password.request_sms');


Route::get('user/check_email/{token}' , 'UserController@check_email')->name('user.check_email');
Route::post('check_login_email' , 'UserController@check_login_email')->name('user.check_login_email');

Route::get('user/info' , 'UserController@index')->name('user.info');
Route::get('user/logout' , 'UserController@logout')->name('user.logout');
Route::put('feedback' , 'ToolController@feedback')->name('tool.feedback');

Route::get('tag/{type}' , 'TagController@index')->name('tag.index');
Route::get('plug/{type}' , 'PlugController@index')->name('plug.index');
Route::post('plug/{type}' , 'PlugController@index')->name('plug.search');
Route::get('plug_index' , 'PlugController@plug_index')->name('plug.plug_index');

Route::get('plugRank/{type?}' , 'PlugController@plugRank')->name('plug.plugRank'); // 插件排行榜
Route::get('plugInfo/{id}' , 'PlugController@plugInfo')->name('plug.plugInfo'); // 插件详情
Route::get("plug_all_info_no_login",'PlugController@plug_all_info_no_login'); //上传界面 获取 所有的type
Route::get("plug_all_info_nav",'PlugController@plug_all_info_nav'); //上传界面 获取 所有的type


Route::get('download/plug/{id}' , 'PlugController@download')->name('plug.download'); // 下载插件
Route::get('quick_download/plug/{id}' , 'PlugController@quick_download')->name('plug.quick_download'); // 下载插件
//collect_this
Route::group(['middleware' => ['user.login']], function () {
    Route::get("collect_this/{id}",'PlugController@collect_this'); //收藏插件
    Route::get("no_collect_this/{id}",'PlugController@no_collect_this'); //取消收藏插件
    Route::get("like_this/{id}",'PlugController@like_this'); //点赞插件
    Route::post("upload_plug_info_img",'UploadController@upload_plug_info_img'); //上传插件详情图片
    Route::post("upload_plug_screen_img",'UploadController@upload_plug_screen_img'); //上传插件截图插件
    Route::post("upload_plug_info_plug",'UploadController@upload_plug_info_plug'); //上传插件包
    Route::get("plug_all_info",'PlugController@plug_all_info'); //上传界面 获取 所有的type
    Route::get("plug_all_info_type/{type?}/{name?}",'PlugController@plug_all_info_type'); //上传界面 获取 所有的type
    Route::put("upload_plug/{plug_id}",'PlugController@upload_plug'); //上传插件
    Route::post("user/recharge",'UserController@recharge'); //用户充值
    Route::get("user/to_pay/{id}",'UserController@go_to_pay')->name('user.go_to_pay'); //用户充值
    Route::get("user/is_pay_ok/{id}",'PayController@tradePayQuery')->name('user.is_pay_ok'); //用户充值
    Route::post("to_pay_plug",'PlugController@to_pay'); //用户购买插件
    Route::post("user/upload_avatar",'UserController@upload_avatar'); //用户上传头像
    Route::post("user/update",'UserController@update'); //用户更新资料
    Route::get("user/check_is_camp",'UserController@check_is_camp'); //检查是否能修改阵营
    Route::post("user/orders/pay/{page}/{size}",'UserController@orders_pay'); //我购买的插件
    Route::post("user/orders/collect/{page}/{size}",'UserController@orders_collect'); //我搜藏的插件
    Route::post("user/orders/upload/{page}/{size}",'UserController@orders_upload'); //我上传的插件
    Route::post("user/get_orders_history/{page}/{size}",'UserController@get_orders_history'); //充值记录
    Route::post("user/get_withdraws/{page}/{size}",'UserController@get_withdraws'); //提现记录
    Route::get("user/send_mail",'UserController@send_mail'); //发送激活邮件
    Route::get("user/send_email/{email}",'UserController@send_email'); //发送验证码邮件
    Route::get("user/send_msg/{tel}/{type}/{alipay?}",'UserController@send_msg'); //发送短信
    Route::post("user/update_tel",'UserController@update_tel'); //更新手机号
    Route::post("user/update_email",'UserController@update_email'); //更新手机号
    Route::post("user/update_Alipay",'UserController@update_Alipay'); //更新update_Alipay
    Route::post("user/check_password",'UserController@check_password'); // 修改密码时检查密码
    Route::post("user/check_password_gs",'UserController@check_password_gs'); // 修改密码时检查密码
    Route::post("user/update_password",'UserController@update_password'); // 更改密码
    Route::get("user/lv",'UserController@get_user_lv'); // 更改密码
    Route::get('update_plugInfo/{id}' , 'PlugController@update_plugInfo')->name('plug.update_plugInfo'); // 编辑插件详情
    Route::put("update_plug/{id}",'PlugController@update_plug'); //编辑插件
    Route::get("check_plug_id/{id}",'PlugController@check_plug_id'); //升级插件 检查ID 是否正确
    Route::post("check_version/{plug_id?}",'PlugController@check_version'); //检查版本号是否正确
    Route::post("rate_score/{plug_id?}",'PlugController@rate_score'); //插件评分
    Route::get("user/check_nickname/{name}/{user_id?}",'UserController@check_nickname'); //检查昵称是否存在


    Route::post("check/user_name",'UserController@user_name'); //检查用户名是否重复
    Route::post("check/user_email",'UserController@user_email'); //检查email是否重复
    Route::post("check/user_tel",'UserController@user_tel'); //检查tel是否重复

    Route::post("bm/list/{page}/{size}",'BmController@bm_list'); //bm列表
    Route::get("bm/check_download/{id}",'BmController@check_download'); //下载bm
    Route::get("bm/download/{id}",'BmController@download'); //下载bm
    Route::get("user/check_withdraw",'UserController@check_withdraw'); //检查
    Route::post("withdraws",'WithdrawController@index'); //提现
    Route::get("find_wechat/{id}",'PayController@find_wechat'); //检查微信支付状态

    Route::get("p_del/{id}",'PlugController@p_del'); //
    Route::get("p_no_del/{id}",'PlugController@p_no_del'); //

});


Route::get("/admin/login",'AdminController@loign');
Route::post("/admin/login",'AdminController@do_loign');

Route::group(['prefix' => 'admin'], function () {
    Route::get("/",'AdminController@index');
    Route::post("tag/list/{page}/{size}",'TagController@tag_list')->name('admin.tag.list'); // 获取tag列表
    Route::get("tag/change_status/{id}/{v}",'TagController@change_status')->name('admin.tag.change.status'); // 更改tag的status
    Route::get("tag/change_is_for_user/{id}/{v}",'TagController@change_is_for_user')->name('admin.tag.change.is_for_user'); // 更改tag的is_for_user
    Route::put("tag/update/{id}",'TagController@update')->name('admin.tag.update'); // 编辑插件信息
    Route::get("plug_all_info",'PlugController@plug_all_info_for_admin')->name('admin.tag.plug_all_info_for_admin'); //上传界面 获取 所有的type
    Route::put("tag/create",'TagController@create')->name('admin.tag.create');
    Route::post("upload_tag_img",'UploadController@upload_plug_screen_img')->name('admin.tag.upload_plug_screen_img'); //上传插件详情图片
    Route::get("tag/change_rank/{id}/{rank}",'TagController@change_rank')->name('admin.tag.change_rank'); // 更换分类排序

    Route::post("user/list/{page}/{size}",'UserController@user_list')->name('admin.user.list'); // 获取用户列表
    Route::get("user/change_status/{id}/{v}",'UserController@change_status')->name('admin.user.change.status'); // 禁止或者允许用户登录
    Route::get("user/change_is_admin/{id}/{v}",'UserController@change_is_admin')->name('admin.user.change.change_is_admin'); // 更改user是否为管理员
    Route::put("user/update/{id}",'UserController@admin_update')->name('admin.user.update'); // 更新用户信息

    Route::post("plug/list/{page}/{size}",'PlugController@plug_list')->name('admin.plug.list'); // 获取插件列表
    Route::get("plug/change_rank/{id}/{rank}",'PlugController@change_rank')->name('admin.plug.change_rank'); // 更换插件排序
    Route::get("plug/change_status/{id}/{v}",'PlugController@change_status')->name('admin.plug.change.status'); // 更改plug的status
    Route::get("plug/change_is_check/{id}/{v}",'PlugController@change_is_check')->name('admin.plug.change.is_check'); // 更改plug的is_check

    Route::post("bm/list/{page}/{size}",'BmController@bm_list')->name('admin.bm.list'); // 获取黑市列表
    Route::post("upload_bm_plug",'UploadController@upload_bm_plug')->name('admin.bm.upload'); // 上传BT
    Route::put("bm/create",'BmController@create')->name('admin.bm.create'); // 新增黑市BT
    Route::put("bm/update/{id}",'BmController@update')->name('admin.bm.update'); // 编辑黑市BT
    Route::get("bm/change_rank/{id}/{rank}",'BmController@change_rank')->name('admin.bm.change_rank'); // 更换bm排序
    Route::get("bm/change_status/{id}/{v}",'BmController@change_status')->name('admin.bm.change.status'); // 更改bm的status

    Route::get('tool/index','ToolController@index')->name('admin.tool.index'); // 获取配置信息
    Route::put('tool/create','ToolController@create')->name('admin.tool.create'); // 更新配置信息
    Route::post('tool/lv/list/{page}/{size}','ToolController@lv_list')->name('admin.lv.list'); // 等级配置
    Route::put('tool/lv/create','ToolController@lv_create')->name('admin.lv.create'); // 添加等级
    Route::put('tool/lv/update/{id}','ToolController@lv_update')->name('admin.lv.update'); // 更新等级
    Route::get('/tool/lv/check_name/{name}/{id}','ToolController@check_name')->name('admin.lv.check_name'); // 验证等级名称
    Route::get('/tool/lv/check_money/{money}/{id}','ToolController@check_money')->name('admin.lv.check_money'); // 验证等级金额
    Route::delete('/tool/lv/del_lv/{id}','ToolController@del_lv')->name('admin.lv.del_lv'); // 删除等级
    Route::get('/tool/nickname/list','ToolController@nickname')->name('admin.nickname.list'); // 昵称管理
    Route::get('/tool/nickname/create_nickname/{name}','ToolController@create_nickname')->name('admin.nickname.create'); // 添加违规昵称
    Route::delete('/tool/nickname/del_nickname/{id}','ToolController@del_nickname')->name('admin.nickname.del_nickname'); //删除违规昵称

    Route::get('/tool/game_version/list','ToolController@game_version')->name('admin.game_version.list'); // 游戏版本号管理
    Route::get('/tool/game_version/create_game_version/{name}','ToolController@create_game_version')->name('admin.game_version.create'); // 添加游戏版本号
    Route::delete('/tool/game_version/del_game_version/{id}','ToolController@del_game_version')->name('admin.game_version.del'); //删除游戏版本号

    Route::post('recharge/list/{page}/{size}','RechargeController@r_list')->name('admin.recharge.list'); // 充值列表
    Route::post('withdraws/list/{page}/{size}','WithdrawController@r_list')->name('admin.withdraws.list'); // 提现列表
    Route::delete("plugs/{id}",'PlugController@delete'); //删除
    Route::get("to_draw/{id}",'WithdrawController@to_draw'); //转账

    Route::post("check_tag_name/{id?}",'TagController@check_tag_name'); //检查tag name
    Route::delete("tag/{id}",'TagController@delete'); //删除 tag
    Route::delete("bms/{id}",'BmController@delete'); //删除 bm

    // charts
    Route::post("charts/userCreated",'ChartController@userCreated');
    Route::post("charts/plugCreated",'ChartController@plugCreated');
    Route::post("charts/orderCharts",'ChartController@orderCharts');
    Route::post("charts/recharge",'ChartController@recharge');
    Route::post("charts/login",'ChartController@login');
    Route::post("charts/draws",'ChartController@draws');
    Route::get("charts/index",'ChartController@index');
    Route::get("charts/service_info",'ChartController@service_info');
    Route::get("charts/GetBrowser",'ChartController@GetBrowser');
    Route::get("charts/service_info",'ChartController@service_info');
    Route::get("charts/bro_charts",'ChartController@bro_charts');
    Route::get("charts/sys_charts",'ChartController@sys_charts');


    Route::get("ad/create_info",'AdController@create_info');
    Route::post("ad/create",'AdController@create');
    Route::post("ad/update",'AdController@update');
    Route::get("ad/index",'AdController@index');
    Route::get("ad/change_status/{id}/{v}",'AdController@change_status');
    Route::get("ad/update_info/{id}",'AdController@update_info');
    Route::delete("ad/delete/{id}",'AdController@delete');
});
