<?php

namespace Modules\Review\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Review\Repositories\Frontend\MyReviewRepository as ReviewRepo;

class ReviewController extends Controller
{

    public function __construct(ReviewRepo $ReviewRepo)
    {
        $this->ReviewRepo = $ReviewRepo;
    }

    public function getReviewDetails(Request $request)
    {
        $response = $this->ReviewRepo->getPropertyReviewDetails($request);
        $data = [];
        if (!empty($response)) {
            $data = [
                'message' => 'Review Already Submitted!',
                'content' => $response,
                'status_code' => 205,
                'type' => 'info'
            ];
        }
        return $data;
    }

    public function addReviewUser(Request $request)
    {
        $request->validate(
            [
                'rate_number' => 'required',
                'content' => 'max:255',
            ]
        );
        $response = $this->ReviewRepo->addUpdateReviewDetails($request);
        return $response;
    }

    public function ReviewReplyVendor(Request $request)
    {
        $request->validate(
            [
                'reply_content' => 'required|max:255',
            ]
        );
        $response = $this->ReviewRepo->updateReviewReplyVendor($request);
        return $response;
    }
}
