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
            return !Tool::where('name','nickname')->where('value',$value)->first();
        });

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
