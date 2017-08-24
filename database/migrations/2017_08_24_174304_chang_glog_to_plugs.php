<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangGlogToPlugs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plugs', function (Blueprint $table) {
            //
            $table->renameColumn('wwb','gold')->default(0)->comment('如果收费需要消费的金币')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plugs', function (Blueprint $table) {
            //
        });
    }
}
