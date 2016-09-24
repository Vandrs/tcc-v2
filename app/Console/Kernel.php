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
        \App\Console\Commands\MakeDiffMatrix::class,
        \App\Console\Commands\ProjetcsToElastic::class,
        \App\Console\Commands\MapElasticModel::class,
        \App\Console\Commands\ExportUsersToElastic::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:make-diff-matrix')->cron('0 */6 * * *')->withoutOverlapping();
    }
}
