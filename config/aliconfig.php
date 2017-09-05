<?php
return [
    'app_id'                    => env('ALIPAY_ID'),
    'sign_type'                 => 'RSA2',// RSA  RSA2
    // 可以填写文件路径，或者密钥字符串  当前字符串是 rsa2 的支付宝公钥(开放平台获取)
    'ali_public_key'            => env('ALIPAY_PUBLIC_KEY'),

    // 可以填写文件路径，或者密钥字符串  我的沙箱模式，rsa与rsa2的私钥相同，为了方便测试
    'rsa_private_key'           =>  env('ALIPAY_MERCHANT_PRIVATE_KEY'),

    //异步通知地址
//    'notify_url' => "http://abc.echohany.com/aplipay_notify",
    'notify_url' => env('ALIPAY_NOTIFY_URL'),

    //同步跳转
//    'return_url' => "http://abc.echohany.com/return",
    'return_url' => env('ALIPAY_RETURN_URL'),

    'return_raw'                => true,// 在处理回调时，是否直接返回原始数据，默认为 true
];
