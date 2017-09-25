<?php

namespace App\Console\Commands;

use App\Http\Controllers\PlugController;
use App\Plug;
use Illuminate\Console\Command;

class DelPlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'del:plug';

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
        $plug = new PlugController();
        $plug->del_plugs();
    }
}