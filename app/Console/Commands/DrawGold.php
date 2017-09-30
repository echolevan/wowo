<?php

namespace App\Console\Commands;

use App\Http\Controllers\WithdrawController;
use App\Withdraws;
use Illuminate\Console\Command;

class DrawGold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'draw:gold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // p屏蔽下面的就可以 自动转钱了
        return false;
        $draws = Withdraws::where('status','<','9')->get();
        foreach ($draws as $k => $v){
            $d = new WithdrawController();
            $d->to_draw($v->id);
        }
    }
}
