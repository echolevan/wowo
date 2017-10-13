<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Levan\Baidu\Stat\BaiduStatFacade;

class GetBaidu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:baidu';

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
        //
        Cache::forget('plug_index_total_person');
        try{
            $total_person = BaiduStatFacade::getData(date('Ymd'),date('Ymd'));
            $total_person = isset($total_person['info']['body']['data'][0]['result']['sum']['0'][1]) ? $total_person['info']['body']['data'][0]['result']['sum']['0'][1]: 0;
        }catch(\Exception $e){
            $total_person = 0;
        }
        Cache::put('plug_index_total_person',$total_person,60);
    }
}
