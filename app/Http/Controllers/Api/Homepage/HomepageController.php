<?php

namespace App\Http\Controllers\Api\Homepage;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Repositories\Api\ApiRepositoryInterface as ApiRepo;
use App\Http\Requests\CreateNewsletterRequest;

class HomepageController extends BaseController
{
	public function __construct(ApiRepo $ApiRepo){
         $this->ApiRepo = $ApiRepo;
    } 

    public function subscrivedMailchimp(CreateNewsletterRequest $request) {
        return $this->ApiRepo->subscrivedMailchimp($request);
    } 

    public function getSocialLinks() {
        return $this->ApiRepo->getSocialLinksData();
    } 

    public function getMapContactDetails() {
        return $this->ApiRepo->getMapContactDetails();
    }

    public function getCmsPages($slug,Request $request) {
        return $this->ApiRepo->getCmsPagesData($slug,$request);
    }
    
    public function getUserNotifications() {
        return $this->ApiRepo->getUserNotifications();
    }

    public function getStateCities(Request $request) {
        return $this->ApiRepo->getStateCities($request);
    } 

    public function getCitiesArea(Request $request) {
        return $this->ApiRepo->getCitiesArea($request);
    }
}