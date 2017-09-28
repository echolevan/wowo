<?php

return [

    'plug_type' => [
        'wa' => 1,
        'tmw' => 2,
        'addons' => 3
    ],

    'plug_name' => [
        '1' => 'WA',
        '2' => 'TMW',
        '3' => '游戏插件'
    ],

    'tag_type' => [
        'wa' => 1,
        'tmw' => 1,
        'addons' => 2
    ],

    'img_type' => [
        'image/jpeg' , 'image/png' , 'image/gif'
    ],

    'upload_plug_type' => [
        'zip' , 'rar' , '7z'
    ],

    'recharge_type' => [
        '1' => '支付宝',
        '2' => '微信'
    ],

    'upload_bm_type' => [
        '1' => 'torrent',
		'2' => 'txt',
        '3' => 'zip',
        '4' => 'rar',
        '5' => '7z',
    ],

    'down_url' => 'https://down.iwowcn.com/',

    'msg_template' => [
        '1' => 'SMS_91010005',  // 手机绑定
        '2' => 'SMS_91030011',  // 重置密码
        '3' => 'SMS_93560007',  // 绑定支付宝
        '4' => 'SMS_93475010'  // 提现
    ],

    'ad_position' => [
        '1' => '首页导航菜单下方',
        '2' => '首页捐赠支持下方',
        '3' => '全站内容底部位置',
        '4' => '全站左侧边栏位置',
        '5' => '全站右侧边栏位置',
        '6' => '资源主题顶部位置',
    ],

    'ad_default_wh' => [
        '1' => [1240 , 90],
        '2' => [250 , 217],
        '3' => [1210 , 90],
        '4' => [250 , 400],
        '5' => [250 , 400],
        '6' => [675 , 90],
    ]

];