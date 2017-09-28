<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('position')->comment('位置');
            $table->string('url',300)->comment('图片地址');
            $table->string('link')->comment('url');
            $table->string('width',10)->comment('图片显示宽度');
            $table->string('height',10)->comment('图片显示宽度');
            $table->tinyInteger('is_show')->default(1)->comment('是否显示 1显示 0不显示');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
