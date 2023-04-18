@extends('layouts.app')
@section('title',"News ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content">
    <section class="bravo-news padding50 bgDark m-0 pb-0">
        <div class="container">
            <div class="newsBlog fadeInUp animated1 selected">
                <div class="article">
                    <div class="header">
                        <header class="post-header">
                            <img src="{{ $records->PicturePath }}" alt="{{ $records->title }}"  onerror="this.src='{{onerrorReturnImage()}}'">
                        </header>
                    </div>
                    <div class="articalContent">
                        <h2 class="title">{{ $records->title }}</h2>
                        <div class="post-info">
                            <ul>
                                <li>{{$records->post_type}}</li>
                                <li><i class="ri-time-line"></i>{{ $records->created_at->format('F d,Y') }}</li>
                            </ul>
                        </div>
                        <div class="post-content">{!! $records->description !!}</div>
                    </div>
                </div>
            </div>
    </section>
</div>
@endsection