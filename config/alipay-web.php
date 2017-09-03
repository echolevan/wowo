<?php
/**
 * Created by PhpStorm.
 * User: luojinyi
 * Date: 2017/6/26
 * Time: 下午5:21
 */

return [
    //应用ID,您的APPID。
    'app_id' => env('ALIPAY_ID'),

    //商户私钥 不能用pkcs8.pem中的密钥！！！！！
    'merchant_private_key' => env('ALIPAY_MERCHANT_PRIVATE_KEY'),

    //异步通知地址
//    'notify_url' => "http://abc.echohany.com/aplipay_notify",
    'notify_url' => env('ALIPAY_NOTIFY_URL'),

    //同步跳转
//    'return_url' => "http://abc.echohany.com/return",
    'return_url' => env('ALIPAY_RETURN_URL'),

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type' => "RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => env('ALIPAY_PUBLIC_KEY'),

    //支付时提交方式 true 为表单提交方式成功后跳转到return_url,
    //false 时为Curl方式 返回支付宝支付页面址址 自己跳转上去 支付成功不会跳转到return_url上， 我也不知道为什么，有人发现可以跳转请告诉 我一下 谢谢
    // email: 40281612@qq.com
    'trade_pay_type' => true,
];