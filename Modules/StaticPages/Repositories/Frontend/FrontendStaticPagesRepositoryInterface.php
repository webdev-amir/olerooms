<?php

namespace Modules\StaticPages\Repositories\Frontend;


interface FrontendStaticPagesRepositoryInterface
{
    public function getRecordBySlug($slug);
    
    public function getNewsUpdates($request);
}
