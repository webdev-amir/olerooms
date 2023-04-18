<?php

namespace Modules\StaticPages\Repositories\Frontend;

use DB,Mail,Session;
use Illuminate\Support\Facades\Input;
use Modules\StaticPages\Entities\StaticPages;
use Modules\NewsUpdates\Entities\NewsUpdates;

class FrontendStaticPagesRepository implements FrontendStaticPagesRepositoryInterface {
  public $StaticPages;
  protected $model = 'StaticPages';

  function __construct(StaticPages $StaticPages) {
      $this->StaticPages = $StaticPages;
  }

  public function getRecordIdBySlug($slug)
  {
    return ($this->StaticPages->findBySlug($slug)) ? $this->StaticPages->findBySlug($slug)->id : NULL;
  }

  public function getRecordBySlug($slug)
  {
    return $this->StaticPages->findOrFail($this->getRecordIdBySlug($slug));
  }

  public function getNewsUpdates($request)
  {
    $type = $request->get('type');
    $news =  NewsUpdates::where('status',1)->orderBy('id','desc');
    if($request->get('type')){
      $news =  $news->where('post_type',$type);
    }
    return $news->paginate(\config::get('custom.default_pagination'));
  }
  
  public function getNewsUpdatesDetails($slug)
  {
      $record =  NewsUpdates::where('slug',$slug)->first();
      return $record;
  }
}
