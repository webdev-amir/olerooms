<?php

namespace Modules\City\Repositories;


interface AreaRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($slug,$id);

    public function getAjaxData($request, $id);

    public function store($request,$id);
}