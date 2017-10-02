<?php

use Illuminate\Database\Seeder;

class LvsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $lvs = array(
            array('id' => '1','name' => 'Lv1.新手','money' => '0','giving' => '0','created_at' => '2017-10-01 00:00:00','updated_at' => '2017-10-01 00:00:00'),
            array('id' => '2','name' => 'Lv2.随机','money' => '200','giving' => '5','created_at' => '2017-10-01 00:00:00','updated_at' => '2017-10-01 00:00:00'),
            array('id' => '3','name' => 'Lv3.普通','money' => '500','giving' => '10','created_at' => '2017-10-01 00:00:00','updated_at' => '2017-10-01 00:00:00'),
            array('id' => '4','name' => 'Lv4.英雄','money' => '1000','giving' => '15','created_at' => '2017-10-01 00:00:00','updated_at' => '2017-10-01 00:00:00'),
            array('id' => '5','name' => 'Lv5.史诗','money' => '1500','giving' => '20','created_at' => '2017-10-01 00:00:00','updated_at' => '2017-10-01 00:00:00'),
            array('id' => '6','name' => 'Lv6.传说','money' => '3000','giving' => '25','created_at' => '2017-10-01 00:00:00','updated_at' => '2017-10-01 00:00:00')
        );
        foreach ($lvs as $v){
            \Illuminate\Support\Facades\DB::table('lvs')->insert($v);
        }
    }
}
