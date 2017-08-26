<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',60)->comment('标题 最长60');
            $table->string('url',100)->comment('下载链接');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->tinyInteger('type')->index()->default(1)->commnet('类型 1是本站BT 2是百度云');
            $table->integer('download_num')->default(0)->comment('下载次数');
            $table->tinyInteger('status')->default(1)->comment('0回收站 1正常');
            $table->tinyInteger('rank')->index()->default(0)->comment('排序 最大99');
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
        Schema::dropIfExists('bms');
    }
}
