<?php

namespace Modules\EmailTemplates\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class EmailTemplatesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EmailTemplatesPermissionSeederTableSeeder::class);
        $this->call(DefaultEmailTemplatesTableSeederTableSeeder::class);
    }
}
