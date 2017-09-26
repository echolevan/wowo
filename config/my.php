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
    ]

];