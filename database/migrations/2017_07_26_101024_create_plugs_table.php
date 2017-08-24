<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->integer('plug_id')->index()->comment('插件唯一ID');
            $table->string('title',120)->comment('插件标题');
            $table->string('simple_info',100)->comment('插件简介');
            $table->text('info')->comment('插件详情');
            $table->tinyInteger('is_free')->default(0)->comment('是否免费 0为免费 1为收费');
            $table->tinyInteger('rank')->default(0)->comment('排序');
            $table->integer('wwb')->default(0)->comment('如果收费需要消费的金币');
            $table->tinyInteger('type')->comment('插件分类 1是wa 2是twm 3是插件');
            $table->integer('type_one')->comment('插件标签的1级分类');
            $table->integer('type_two')->default(0)->comment('插件标签的2级分类');
            $table->text('content')->comment('插件下载地址或者字符串');
            $table->tinyInteger('is_new')->comment('插件是否是最新 0是历史版本 1是最新版本')->default(1);
            $table->string('updated_info',150)->comment('更新说明');
            $table->string('version',20)->comment('插件版本号');
            $table->string('game_version',20)->comment('插件对应游戏版本号');
            $table->integer('download_num')->default(0)->comment('插件下载次数');
            $table->integer('like_num')->default(0)->comment('插件点赞次数');
            $table->integer('collect_num')->default(0)->comment('插件收藏次数');
            $table->string('score',5)->comment('插件评分')->default('10');
            $table->tinyInteger('status')->default(1)->comment('1是正常 0是回收站');
            $table->tinyInteger('is_check')->default(1)->comment('1是审核通过 0是审核不通过');
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
        Schema::dropIfExists('plugs');
    }
}
