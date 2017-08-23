<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManyColToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('nickname',30)->comment('用户昵称');
            $table->tinyInteger('sex')->default(0)->comment('0保密 1男 2女');
            $table->string('birthday',30)->nullable()->comment('生日');
            $table->string('birthplace',100)->nullable()->comment('出生地');
            $table->string('habitably')->nullable('居住地');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
