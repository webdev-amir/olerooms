<?php

namespace Modules\City\Repositories\StateRepository;


interface StateRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($slug);

    public function update($request,$id);

    public function destroy($id);
    
    public function changeStateStatus($request, $id);
}