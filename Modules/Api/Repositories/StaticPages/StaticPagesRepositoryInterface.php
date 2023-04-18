<?php

namespace Modules\Api\Repositories\StaticPages;


interface StaticPagesRepositoryInterface
{
    public function getCmsPagesData($slug,$request);

    public function getCmsPagesLinks($request);
    
    public function getSocialLinksData($request);
    
    public function getPlaystoreLinks($request);
}