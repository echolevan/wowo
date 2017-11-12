<?php

namespace App\Console\Commands;

use App\Ad;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckAd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkTo:ad';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkTo ad';

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
        //
        Log::info('check ad start :::::');
        $ads = Ad::get();

        foreach ($ads as $k => $v){
            if ($v->end_at >= date('Y-m-d',time())){
                $s = Carbon::parse($v->end_at)->diffInDays(Carbon::now()) + 1;
                Log::info('check ad :::::'.$s.":::::info:::::".json_encode($v));
                if($s <= 3){
                    //send email
                    dispatch(new \App\Jobs\SenfAdEmail($v));
                }
            }else{
                //send email
//                dispatch(new \App\Jobs\SenfAdEmail($v));
            }
        }

        Log::info('check ad end :::::');

    }
}
