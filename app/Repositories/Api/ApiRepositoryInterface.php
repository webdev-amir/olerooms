<?php

namespace App\Repositories\Api;

interface ApiRepositoryInterface
{
    public function subscrivedMailchimp($request);

    public function getSocialLinksData();
    
    public function getMapContactDetails();

    public function getCmsPagesData($slug,$request);

    public function getStateCities($request);
    
    public function getCitiesArea($request);
}