<?php

namespace App\Repositories\Frontend;


interface FrontendRepositoryInterface
{
    public function getStaticPageBySlug($slug);

    public function getPropertyTypesForOptions();
    
    public function getamenitiesData();

    public function getAutocompleteLocationsLists($request);
}
