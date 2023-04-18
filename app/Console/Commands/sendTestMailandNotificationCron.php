<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sendTestMailandNotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendTestMailandNotification:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for testing mail and notification by cron';

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
     * @return int
     */
    public function handle()
    {
         app('Modules\Cron\Http\Controllers\CronController')->sendTestMailandNotificationCron();
    }
}
