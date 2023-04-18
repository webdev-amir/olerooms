<?php

namespace Modules\Api\Repositories\Customer\Home;

interface HomeRepositoryInterface
{
   public function searchProperty($request);

   public function getHomepageData($request);

   public function getState($request);

   public function getCity($request);

   public function getLocation($request);
   
   public function searchPropertyFilter($request);
}

