<?php

namespace App\Console;

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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->hourly();
        $schedule->command('command:runreminder')
                ->everyMinute()->withoutOverlapping();
        // $schedule->command('command:runwfapproved')
        //         ->weekly()->dailyAt('9:00')->withoutOverlapping();
        // $schedule->command('command:runwfapprovecar')
        //         ->weekly()->dailyAt('9:00')->withoutOverlapping();       
        // $schedule->command('command:runwfapproved')
        // ->everyMinute()->withoutOverlapping();
        // $schedule->command('command:runwfapprovecar')
        // ->everyMinute()->withoutOverlapping();
                
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
