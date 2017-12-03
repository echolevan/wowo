<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GameVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value',20)->comment('游戏版本号');
            $table->tinyInteger('is_for_users')->default(1)->comment('是否用户能使用。暂时没用。默认1');
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
        Schema::dropIfExists('game_versions');
    }
}
