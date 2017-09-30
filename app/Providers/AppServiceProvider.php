<?php

namespace App\Providers;

use App\Tool;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        Validator::extend('is_num', function ($attribute, $value, $parameters, $validator) {
            return !is_numeric($value);
        });

        Validator::extend('is_pass', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])/',$value);
        });

        Validator::extend('is_nickname', function ($attribute, $value, $parameters, $validator) {
            return !is_numeric($value);
        });

        Validator::extend('is_admin_name', function ($attribute, $value, $parameters, $validator) {
            $count = Tool::where('name','nickname')->where('value',$value)->count();
            if($count != 0){
                return false;
            }
            $ck = $this->sensitiveWordFilter($value);
            if($ck != 1){
                return false;
            }
            return true;
        });

        Validator::extend('is_name_zw', function ($attribute, $value, $parameters, $validator) {
            return !preg_match('/[\x{4e00}-\x{9fa5}]/u', $value);
        });
    }

    function sensitiveWordFilter($str)
    {
        $str = ' '.$str;
        $words = \App\Tool::where('name', 'nickname')->pluck('value')->toArray();   // 建议从文件或者缓存中读取敏感词列表，英文约定小写
        $flag = false;

        // 提取中文部分，防止其中夹杂英语等
        preg_match_all("/[\x{4e00}-\x{9fa5}]+/u", $str, $match);
        $chinsesArray = $match[0];
        $chineseStr = implode('', $match[0]);
        $englishStr = strtolower(preg_replace("/[^A-Za-z0-9\.\-]/", " ", $str));

        $flag_arr = array('？', '！', '￥', '（', '）', '：' , '‘' , '’', '“', '”', '《' , '》', '，',
            '…', '。', '、', 'nbsp', '】', '【' ,'～', '#', '$', '^', '%', '@', '!', '*', '-'. '_', '+', '=');
        $contentFilter = preg_replace('/\s/', '', preg_replace("/[[:punct:]]/", '',
            strip_tags(html_entity_decode(str_replace($flag_arr, '', $str), ENT_QUOTES, 'UTF-8'))));

        // 全匹配过滤,去除特殊字符后过滤中文及提取中文部分
        foreach ($words as $word)
        {
            // 判断是否包含敏感词,可以减少这里的判断来降低过滤级别，
            if (@strpos($str, $word) || @strpos($contentFilter, $word) || @strpos($chineseStr, $word)
                || @strpos($englishStr, $word)) {
                return '敏感词:' . $word;
            }
        }
        return 1;
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
