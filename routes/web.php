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
//    $thumb = [
//        'http://pic.58pic.com/58pic/12/57/43/21f58PICRkp.jpg',
//        'http://img4.imgtn.bdimg.com/it/u=541094027,2507810915&fm=26&gp=0.jpg',
//        'http://img4.imgtn.bdimg.com/it/u=1995986088,2555682542&fm=27&gp=0.jpg',
//        'http://wow.tgbus.com/UploadFiles_2396/201402/20140212110258533.jpg'
//    ];
//
//
//    for ($i = 1 ; $i <=153 ; $i++){
//        \App\Thumb::create([
//            'plug_id' => $i,
//            'thumb' => collect($thumb)->random(),
//        ]);
//    }

//    $res = \App\Plug::first();
//
//    for ($i = 0 ; $i< 10; $i ++){
//        \App\Plug::create([
//            'user_id' => 1,
//            'plug_id' => str_random(50),
//            'title' => str_random(20),
//            'simple_info' => str_random(rand(50,100)),
//            'info' => $res->info,
//            'type' => rand(1,2),
//            'type_one' => 11,
//            'type_two' => 15,
//            'content' => str_random(50),
//            'is_new' => 1,
//            'version' => '1.0.1',
//            'game_version' => '7.2',
//            'download_num' => rand(55,9000),
//            'like_num' => rand(55,9000),
//            'collect_num' => rand(55,9000)
//        ]);
//    }

    return view('welcome');
})->name('index');


Route::get('/sign', function () {
    return view('sign');
});





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('user/check_email/{token}' , 'UserController@check_email')->name('user.check_email');

Route::get('user/info' , 'UserController@index')->name('user.info');
Route::get('user/logout' , 'UserController@logout')->name('user.logout');

Route::get('tag/{type}' , 'TagController@index')->name('tag.index');
Route::get('plug/{type}' , 'PlugController@index')->name('plug.index');
Route::post('plug/{type}' , 'PlugController@index')->name('plug.search');

Route::get('plugRank/{type?}' , 'PlugController@plugRank')->name('plug.plugRank'); // 插件排行榜
Route::get('plugInfo/{id}' , 'PlugController@plugInfo')->name('plug.plugInfo'); // 插件详情


Route::get('download/plug/{id}' , 'PlugController@download')->name('plug.download'); // 下载插件
//collect_this
Route::group(['middleware' => ['user.login']], function () {
    Route::get("collect_this/{id}",'PlugController@collect_this'); //收藏插件
    Route::get("like_this/{id}",'PlugController@like_this'); //点赞插件
    Route::post("upload_plug_info_img",'UploadController@upload_plug_info_img'); //上传插件详情图片
    Route::post("upload_plug_screen_img",'UploadController@upload_plug_screen_img'); //上传插件截图插件
    Route::post("upload_plug_info_plug",'UploadController@upload_plug_info_plug'); //上传插件包
    Route::get("plug_all_info",'PlugController@plug_all_info'); //上传界面 获取 所有的type
    Route::put("upload_plug",'PlugController@upload_plug'); //上传插件
});

