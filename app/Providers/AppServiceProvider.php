<?php

namespace App\Providers;

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
