<?php

namespace Modules\EmailTemplates\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\EmailTemplates\Entities\EmailTemplate;

class DefaultEmailTemplatesTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activateAccount = EmailTemplate::where('slug','activate-user')->first();
        if(!$activateAccount){
            EmailTemplate::create([
                'name'=>'Activate User',
                'subject'=>'Activate your account',
                'body' => "<p><strong>Hello [username] ,</strong></p>

<p>Thank you for creating an account. To activate your account and start using support, please confirm your email address by clicking below.</p>

<p><a href='#''>[activationlink]</a></p>",
            ]);
         }
        $activateAccount = EmailTemplate::where('slug','send-test-mail-and-notification-cron')->first();
        if(!$activateAccount){
            EmailTemplate::create([
                'name'=>'Send Test Mail and Notification Cron',
                'subject'=>'Send Test Mail and Notification Cron',
                'body' => "<p><strong>Hello [username] ,</strong></p>

<p>This is a mail regading testing cron mail and notifications.</p>",
            ]);
        }
    }
}
