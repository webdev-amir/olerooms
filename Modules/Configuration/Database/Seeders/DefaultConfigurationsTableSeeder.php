<?php

namespace Modules\Configuration\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Configuration\Entities\Configuration;

class DefaultConfigurationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insertConfig[0] = ['config_title' =>'Adminemail','config_value'=>'testapp@yopmail.com'];
        $insertConfig[1] = ['config_title' =>'Admincontact','config_value'=>'+ 00 222 444 33'];
        $insertConfig[2] = ['config_title' =>'Address','config_value'=>'1355 Market St, Suite 900San, Francisco, CA 94103 United States'];
        $insertConfig[3] = ['config_title' =>'Mapaddress','config_value'=>"Add Map url here"];
        $insertConfig[4] = ['config_title' =>'Facebook','config_value'=>"https://www.facebook.com/"];
        $insertConfig[5] = ['config_title' =>'Twitter','config_value'=>"https://twitter.com/"];
        $insertConfig[6] = ['config_title' =>'Youtube','config_value'=>"https://www.youtube.com/"];
        $insertConfig[7] = ['config_title' =>'Linkedin','config_value'=>"https://www.linkedin.com/"];
        $insertConfig[8] = ['config_title' =>'Instagram','config_value'=>"https://www.instagram.com/"];
        foreach ($insertConfig as $key => $insert) {
            if(!Configuration::where('slug',strtolower($insert['config_title']))->exists()){
                Configuration::create($insert);
            }
        }
    }
}
