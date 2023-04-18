<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeConfirmedToCompletedIfBookingCheckOutDateIsPassed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeConfirmedToCompletedIfBookingCheckOutDateIsPassed:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make Confirmed To Completed If Booking Check Out Date Is Passed';

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
       app('Modules\Cron\Http\Controllers\CronController')->makeConfirmedToCompletedIfBookingCheckOutDateIsPassed();
    }
}
