<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakePendingToAutoConfirmedAndRejectedIfBookingIsNotConfirmedByVendor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makePendingToAutoConfirmedAndRejectedIfBookingIsNotConfirmedByVendor:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Pending To Auto Confirmed And Rejected If Booking Is Not Confirmed By Vendor set for every minutes';

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
        app('Modules\Cron\Http\Controllers\CronController')->makePendingToAutoConfirmedAndRejectedIfBookingIsNotConfirmedByVendor();
    }
}
