@extends('layouts.app')
@section('title',"News ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content" id="news_updates">
    <section class="simplified bravo-list-tour padding50 bgDark m-0 pb-0">
        <div class="container">
            <div class="heading-title mb-4 medium">
                News & Updates</span>
                <p class="simplified-sub mt-1"> A web platform where users can rent out their space to host customers to: </p>
            </div>
            <div class="newsdata button">
                <a href="javascript:;" id='News' data-type="news" class="btn btn-success font16 regular mr-2 bR12 newsupdate @if($type=='News') active @endif" onclick="paginate('{{route('frontend.news')}}','','News')">News</a>
                <a href="javascript:;" id='Article' data-type="article" class="btn btn-success font16 regular bR12 newsupdate @if($type=='Article') active @endif" onclick="paginate('{{route('frontend.news')}}','','Article')">Article</a>
            </div>
            <div class="newsBlog mT30 fadeInUp animated1 selected" id="result">                
                @include('staticpages::frontend.ajax_my_newsupdate_list')
            </div>
        </div>
    </section>
</div>
@endsection