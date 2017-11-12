<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',30)->comment('标签的名称 例如法师');
            $table->string('thumb',120)->default('')->comment('标签的图片地址');
            $table->integer('pid')->default(0)->comment('0位1级标签 其他的对应 id');
            $table->tinyInteger('type')->default(1)->comment('1是wa tmw 2是插件分类');
            $table->tinyInteger('status')->default(1)->comment('1是正常 0是回收站');
            $table->tinyInteger('is_check')->default(1)->comment('1是审核通过 0是审核不通过');
            $table->tinyInteger('is_for_user')->default(1)->comment('1是 0不是');
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
        Schema::dropIfExists('tags');
    }
}
