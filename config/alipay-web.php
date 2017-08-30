<?php
/**
 * Created by PhpStorm.
 * User: luojinyi
 * Date: 2017/6/26
 * Time: 下午5:21
 */

return [
    //应用ID,您的APPID。
    'app_id' => "2017083008458039",

    //商户私钥 不能用pkcs8.pem中的密钥！！！！！
    'merchant_private_key' => "MIIEowIBAAKCAQEAuIDCvlQsTHY8p1gt4XN07lnF98ca1GUDHDZzMTTmDrGhBumb5QowE23eXwtu+LukdyjnpYjsnh5a5Yi8yTdBlHbk2m+cl+cxLciCI0BGItuwtDNbKbGQCfK1VXwaDYCUCJu/nePK4VPS835tD3+P5miyadCdjN2tCoxZBcK3hOPLZJ/O7YqbkCI0BhnDiIKPjPYS3UuuCdcccREYDn9K/EKStqmtbDDULFp3b2ZfLWpqktmQ/XUkGWhmaibdY1hmycgyXsE9aIoLGvIHs/61eyBzSGj1uTS8EospsomIkp2+xHDrx+c+hCfBMjzkyV7mQJkyY1dpA7LZJiqsB+LKlwIDAQABAoIBAH5w94UaCFXKPdztSFwWFM93xHz+/TsLkqsWAbF6MCNnxhQHqqe3aKUYCWoXWtCpP5fN95pxEL93zBE7uS0oahAFvuaX0LglX4WhwNXJqYcgCaQlAde78RtwMqdH6k7QjCKuIg5hWts65/mo7bLOV96uZ36u0hoEnR76edPB0qUFkLKp1VAYPUc1VZUi6w3G0k6LVchF+HrgA04//+SOIkUEKgnua9rlQkSYvFL8jymsNVUjseKEyg9OmeWRhEmX3rPqmmTaDLUt8yv9IFGHJum7+lnaLV+Kb5wvCYjQM16BQlW8LL/4KlE5sAOxE6wlraCNkRsUR/XKqIET9TgOJYkCgYEA68GAMq2t+WwkqSbQFtUD2/7Z/wkHQ6Ebq20nEsJUPqFItIR1GqQpRstEMbJwkeBvETacfUPZQvFlMe1KfR1dGtSk04Zj31M60+VFihG5PQn5NctzF5oiVM+vmNlxbPukfNcPyYIMBdun2UfCoAhCPesq8+sLdvuf4s2nnlpbttUCgYEAyFiYGdGgz4mt0ax+OJKs8bu72YhhTGrFG4PhjMCw9QwvCVC8thFUZcKPpY+Vd4nfMQUjW+J5MTjtE6qsomuQ4LKqAZp9W6AR6UCawa/iwccV8jjH4p1QhptsxFMi7Nc8jqjS329XO4u7bzTw0aNA+olWrrtMAJDeTJTHtnx7ybsCgYAp4h+fYUGmLTM6Jd4UIFXmMJmT+Iuk1StwQh0N9R44XjjncRzI1d9LZ3M/DP94uxGgUj5s9q/Td8VNoGV6YswWpy59McPuef/ag0HebJWnJW1zxCg3SjfNL3R3yDGhdfdcAm0YlJUEOWjRbjFCcxUpoOzplplb0wotpfGgbVcb3QKBgAM9i5g/T76Ll4ggphn8JVfzlWI7VneJ8o4zX96K71HHVKtt5t97AEZ1EyW2YNCCJl4Am0XNL/Cf3xXGWOx3mOxJd353JTfqKt/+74Haemxv7+bvDCyaJsdGlEfIoHP8NzqHeIGsp1iLNgjiblK1tUNYHVLOLLiFn1LVxTUqZkYpAoGBAJzbFnEz/tUNp3GBd68vUMV5AoIVLri/Tzi219WRNbd3T6M6tFrxDQz413oXRM79hv+JBjbk7VaspO8hf/fRj4rXpT/xDLp8bHvzgmOMl5tDSc6Pe9LzWHmIpZOxf1nqPsaC1fvDw4NNrYzC7Q6Cr/IAgcc9Epb/rJpIHR9vrkxM",

    //异步通知地址
    'notify_url' => "http://wowo.dev/notify",

    //同步跳转
    'return_url' => "http://wowo.dev/return",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type' => "RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsChdp5zw97Fsph7vxQ7iAGVM6LQqVRvUpxIAVFYkR1boLFn9hHJCM8V33IQJ6WxBkZWMaWUpqrqQ52j3PQeJEBxmw5HxmcP7U9MDIHkHb5Uxd8c964m4vwf337WIRAB9YieAKHjnV6Y2EniJuEltQNKrJ7ydx8F6eqJdmRiyvP7dzzSb2xM2sfWMARZQAfcqPqwPAnxaVaBa8J2duqz4Y8LYN2BIz5/ax2RstMTE0tFNZ8FV/JV+de8m0ifKy5djZUHcRtWwFv2I5es4u0fBmPRdZqV1qc25xbHctiGJ9pzxFSsZszwkIzWpPaqbUGFgFCDspDRVc60LoBZI2MDm+QIDAQAB",

    //支付时提交方式 true 为表单提交方式成功后跳转到return_url,
    //false 时为Curl方式 返回支付宝支付页面址址 自己跳转上去 支付成功不会跳转到return_url上， 我也不知道为什么，有人发现可以跳转请告诉 我一下 谢谢
    // email: 40281612@qq.com
    'trade_pay_type' => false,
];