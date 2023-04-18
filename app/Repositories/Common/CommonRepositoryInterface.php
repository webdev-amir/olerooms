<?php

namespace App\Repositories\Common;


interface CommonRepositoryInterface
{
    public function getUserCountsByRoles($request,$role);

    public function saveProfilePictureMedia($request);
    
    public function uploadUserDcuments($request);
    
    public function updateUserPassword($request);

    public function getStaticPageRecordBySlug($slug);

    public function getAllPagesListPluck();

    public function getUsersPluck();
    
    public function getCountryPluck();
    
    public function getCountryCodesPluck();
    
    public function getCountryListPluck();
	
	public function getPropertyTypesPluck();
	public function getPropertyTypesOptions();
}
