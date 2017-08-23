<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZytyleToBm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bms', function (Blueprint $table) {
            $table->tinyInteger('zy_type')->comment('1 影视 2剧集 3 软件 4其他');
            $table->integer('wwb')->default(0)->comment('0免费');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bm', function (Blueprint $table) {
            //
        });
    }
}
