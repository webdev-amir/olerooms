<?php

namespace Modules\Review\Repositories\Frontend;


interface MyReviewRepositoryInterface
{
    public function getPropertyReviewDetails($request);

    public function addUpdateReviewDetails($request);
}
