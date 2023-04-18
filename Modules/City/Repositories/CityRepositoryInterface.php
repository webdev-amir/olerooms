<?php

namespace Modules\City\Repositories;


interface CityRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($slug);

    public function update($request,$id);

    public function destroy($id);
    
    public function saveBannerImageMedia($request);
}