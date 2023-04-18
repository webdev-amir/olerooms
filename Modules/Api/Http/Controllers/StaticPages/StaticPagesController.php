<?php

namespace Modules\Api\Http\Controllers\StaticPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Modules\Api\Repositories\StaticPages\StaticPagesRepositoryInterface as PagesList;

class StaticPagesController extends BaseController
{
	public function __construct(PagesList $PagesRepo){
         $this->PagesRepo = $PagesRepo;
    }

	public function getCmsPages($slug,Request $request) {
     	return $this->PagesRepo->getCmsPagesData($slug,$request);
    }

    public function getCmsPagesLinks(Request $request) {
        return $this->PagesRepo->getCmsPagesLinks($request);
    }

    public function getSocialLinks(Request $request) {
     	return $this->PagesRepo->getSocialLinksData($request);
    }

    public function getPlaystoreLinks(Request $request) {
        return $this->PagesRepo->getPlaystoreLinks($request);
    }
}