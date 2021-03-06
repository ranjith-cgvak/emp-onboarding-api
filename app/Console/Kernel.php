<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
Use App\User;
Use App\EmailQueues;

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
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () { EmailQueues::truncate(); })->everyMinute();
        // $schedule->call('App\Http\Controllers\CronController@insertTemplateMails')->everyMinute();
        $schedule->call('App\Http\Controllers\CronController@sendRegularEmails')->everyMinute();
        $schedule->call('App\Http\Controllers\CronController@reportFailedEmails')->everyMinute();
		
		$schedule->call('App\Http\Controllers\CronController@getUserDetails')->dailyAt('01:00');
		$schedule->call('App\Http\Controllers\CronController@getUserDetailsByURL')->dailyAt('01:00');
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
