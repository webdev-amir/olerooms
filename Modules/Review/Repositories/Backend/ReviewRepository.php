<?php

namespace Modules\Review\Repositories\Backend;

use config, DB, Session;
use DataTables;
use Carbon\Carbon;
use Modules\Review\Entities\Review;
use Modules\Property\Entities\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ReviewRepository implements ReviewRepositoryInterface
{

    public $Review;
    protected $model = 'Review';

    function __construct(Review $Review, User $User, Property $Property)
    {
        $this->Review = $Review;
        $this->Users = $User;
        $this->Property = $Property;
    }

    public function getRecord($id)
    {
        return $this->Review->find($id);
    }

    public function getRecordBySlug($slug)
    {
        $record = $this->Review->findBySlug($slug);

        return $record;
    }

    public function getAjaxData($request)
    {
        try {

            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->where('status', 'publish')->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->with(['user', 'property', 'booking']);
            if ($request->has('username')) {

                $username = $request->username;
                $lists->whereHas('user', function ($query) use ($username) {
                    $query->where([['users.username', 'like', '%' . $username . '%']]);
                });
            }

            if ($request->has('bookingId')) {
                $bookingId = $request->bookingId;
                $lists->whereHas('booking', function ($query) use ($bookingId) {
                    $query->where([['bookings.code', 'like', '%' . $bookingId . '%']]);
                });
            }

            if ($request->has('srart_date') && $request->has('end_date')) {
                $lists->whereBetween('publish_date', [date('Y-m-d', strtotime($request->srart_date)), date('Y-m-d', strtotime($request->end_date))]);
            }

            $lists = $lists->latest()->get();
            return DataTables::of($lists)
                ->addColumn('action', function ($list) use ($model) {
                    $dispalyButton = displayButton(['deleteAjax' => [strtolower($model) . '.destroy', [$list->id]]]);
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                    return $delete;
                })
                ->editColumn('id', function ($list) {
                    return ($list->booking ? $list->booking->code : "N/A");
                })
                ->editColumn('user_name', function ($list) {
                    return ($list->user ? $list->user->name : "N/A");
                })

                ->editColumn('pro_owner', function ($list) {
                    return (!empty($list->property->author) ? $list->property->author->name : "N/A");
                })
                ->editColumn('property_name', function ($list) {
                    return (!empty($list->property)? $list->property->property_name : "N/A");

                })
                ->editColumn('booking_date', function ($list) {
                    return (date('d M Y, g:i a', strtotime($list->created_at)));
                })
                ->editColumn('rating_number', function ($list) {
                    //                      return ($list->rate_number ? $list->rate_number : "N/A");

                    return "<div class='myratingview' data-rating='" . $list->rate_number . "'>" . $list->rate_number . "</div>";
                })
                ->editColumn('review_content', function ($list) {
                    return $list->content ? $list->content : "";
                })
                ->rawColumns(['status', 'action', 'rating_number'])
                ->make(true);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getVendors()
    {
        return $this->Users->where('role_id', 3)->orderBy('name')->pluck('name', 'id');
    }

    public function destroy($id)
    {
        return $this->Review->destroy($id);
    }
}
