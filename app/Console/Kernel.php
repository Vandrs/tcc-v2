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
        \App\Console\Commands\ExportUsersToElastic::class,
        \App\Console\Commands\SendValidationEmails::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:make-diff-matrix')->cron('0 */2 * * *')->withoutOverlapping();
        $schedule->command('app:send-validation-emails')->cron('0 */1 * * *')->withoutOverlapping();
    }
}
