<?php

namespace Modules\Property\Repositories\Frontend;


interface MyPropertyRepositoryInterface
{
    public function getRecordBySlug($slug);

    public function getSessionEntryFormData();
    
    public function getSessionEntryAllData();

    public function storePropertyProcessSteps($request);
    
    public function getMyWishlist($request);

    public function updatePropertyProcessSteps($request, $id);
    
}