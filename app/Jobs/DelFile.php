<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class DelFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        foreach ($this->data as $k => $v){
            $del_path = parse_url($v)['path'];

            try
            {
                if(file_exists(env('UPLOAD_PWD')."/".$del_path)){
                    @unlink(env('UPLOAD_PWD')."/".$del_path);
                    Log::info('del-ok===='.$del_path);
                }
                Log::info('del-no-file===='.$del_path);
            }
            catch(Exception $e)
            {
                Log::error('del-fail===='.$del_path);
            }

        }
    }
}


