<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLvsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lvs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',30)->comment('等级名称');
            $table->string('money',60)->comment('等级所需充值的钱 例 [100,300] 的json 格式');
            $table->tinyInteger('giving')->comment('充值赠送的金额');
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
        Schema::dropIfExists('lvs');
    }
}
