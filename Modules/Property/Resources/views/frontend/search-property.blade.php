@extends('layouts.app')
@section('title',"Search ".trans('menu.pipe')." " .app_name())
@section('content')
@php
$default_search_layout = config('custom.default_search_layout');
$row_search_layout = config('custom.row_search_layout');
$search_layout = request()->get('searchLayout');
@endphp
<div class="page-template-content" id="searchpage">
    <div class="bravo_search_event listing-track padding50 bgDark m-0 pb-0 selected">
        <div class="container">
            <div class="row listing-back">
                <div class="col-lg-3 col-md-12 filter-col">
                    @include('frontend.includes.search.filter-search')
                </div>
                <div class="col-lg-9 col-md-12 listing-right" id="resultSearchProp">
                    @include('frontend.includes.search.ajax_properties_list')
                </div>
            </div>
        </div>
    </div>
</div>


@endsection