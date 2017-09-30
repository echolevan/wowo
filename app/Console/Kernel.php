<?php

namespace App\Console;

use App\Console\Commands\CheckAd;
use App\Console\Commands\DelPlugs;
use App\Console\Commands\DrawGold;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        DelPlugs::class,
        CheckAd::class,
        DrawGold::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('del:plug')
                  ->everyMinute();
        $schedule->command('draw:gold')
            ->everyMinute();
        $schedule->command('check:ad')
            ->dailyAt('10:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
