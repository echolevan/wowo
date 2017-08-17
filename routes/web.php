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

Route::get('/', function () {
    return view('welcome');
})->name('index');

//Route::get('/abc', function () {
//    \Illuminate\Support\Facades\DB::update('ALTER TABLE USERS AUTO_INCREMENT = 100001');
//});

//Route::get('/sign', function () {
//    return view('sign');
//});


Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');


Route::get('user/check_email/{token}' , 'UserController@check_email')->name('user.check_email');

Route::get('user/info' , 'UserController@index')->name('user.info');
Route::get('user/logout' , 'UserController@logout')->name('user.logout');

Route::get('tag/{type}' , 'TagController@index')->name('tag.index');
Route::get('plug/{type}' , 'PlugController@index')->name('plug.index');
Route::post('plug/{type}' , 'PlugController@index')->name('plug.search');
Route::get('plug_index' , 'PlugController@plug_index')->name('plug.plug_index');

Route::get('plugRank/{type?}' , 'PlugController@plugRank')->name('plug.plugRank'); // 插件排行榜
Route::get('plugInfo/{id}' , 'PlugController@plugInfo')->name('plug.plugInfo'); // 插件详情
Route::get("plug_all_info_no_login",'PlugController@plug_all_info_no_login'); //上传界面 获取 所有的type


Route::get('download/plug/{id}' , 'PlugController@download')->name('plug.download'); // 下载插件
//collect_this
Route::group(['middleware' => ['user.login']], function () {
    Route::get("collect_this/{id}",'PlugController@collect_this'); //收藏插件
    Route::get("like_this/{id}",'PlugController@like_this'); //点赞插件
    Route::post("upload_plug_info_img",'UploadController@upload_plug_info_img'); //上传插件详情图片
    Route::post("upload_plug_screen_img",'UploadController@upload_plug_screen_img'); //上传插件截图插件
    Route::post("upload_plug_info_plug",'UploadController@upload_plug_info_plug'); //上传插件包
    Route::get("plug_all_info",'PlugController@plug_all_info'); //上传界面 获取 所有的type
    Route::put("upload_plug/{plug_id}",'PlugController@upload_plug'); //上传插件
    Route::post("user/recharge",'UserController@recharge'); //用户充值
    Route::post("to_pay_plug",'PlugController@to_pay'); //用户购买插件
    Route::post("user/upload_avatar",'UserController@upload_avatar'); //用户上传头像
    Route::post("user/update",'UserController@update'); //用户更新资料
    Route::get("user/check_is_camp",'UserController@check_is_camp'); //检查是否能修改阵营
    Route::post("user/orders/pay/{page}/{size}",'UserController@orders_pay'); //我购买的插件
    Route::post("user/orders/collect/{page}/{size}",'UserController@orders_collect'); //我搜藏的插件
    Route::post("user/orders/upload/{page}/{size}",'UserController@orders_upload'); //我上传的插件
    Route::post("user/get_orders_history/{page}/{size}",'UserController@get_orders_history'); //充值记录
    Route::get("user/send_mail",'UserController@send_mail'); //发送激活邮件
    Route::post("user/check_password",'UserController@check_password'); // 修改密码时检查密码
    Route::post("user/update_password",'UserController@update_password'); // 更改密码
    Route::get("user/lv",'UserController@get_user_lv'); // 更改密码
    Route::get('update_plugInfo/{id}' , 'PlugController@update_plugInfo')->name('plug.update_plugInfo'); // 编辑插件详情
    Route::put("update_plug/{id}",'PlugController@update_plug'); //编辑插件
    Route::get("check_plug_id/{id}",'PlugController@check_plug_id'); //升级插件 检查ID 是否正确
    Route::post("check_version/{plug_id?}",'PlugController@check_version'); //检查版本号是否正确
    Route::post("rate_score/{plug_id?}",'PlugController@rate_score'); //插件评分
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

    Route::post("user/list/{page}/{size}",'UserController@user_list')->name('admin.user.list'); // 获取用户列表
    Route::get("user/change_status/{id}/{v}",'UserController@change_status')->name('admin.user.change.status'); // 禁止或者允许用户登录
    Route::get("user/change_is_admin/{id}/{v}",'UserController@change_is_admin')->name('admin.user.change.change_is_admin'); // 更改user是否为管理员
});
