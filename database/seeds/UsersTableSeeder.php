<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::update('ALTER TABLE users AUTO_INCREMENT = 100000');
        $users = array(
            array('name' => 'admin','email' => 'admin@iwowcn.com',
                'password' => bcrypt('admin'),
                'avatar' => '/images/avatar/1.jpg','info' => '','tel' => '0','is_active' => '1','camp' => '1','is_admin' => '1','gold' => '0','token' => '','update_camp_at' => '1506861235','remember_token' => 'YQR5ccQmV5HPZP65mSnocTysg07EJKpMjXNsbFpKzDN9t1zDbz2HfHT5C8ts','created_at' => '2017-10-01 00:00:00','updated_at' => '2017-10-01 00:00:00','login_at' => NULL,'status' => '1','nickname' => 'admin','sex' => '0','birthday' => NULL,'birthplace' => NULL,'habitably' => NULL,'alipay' => NULL,'alipay_name' => NULL)
        );

        \Illuminate\Support\Facades\DB::table('users')->insert($users);

        // 分成
        \Illuminate\Support\Facades\DB::table('tools')->insert([
            'name' => 'fc',
            'value' => '70',
            'created_at' => '2017-10-01 00:00:00',
            'updated_at' => '2017-10-01 00:00:00',
        ]);
    }
}
