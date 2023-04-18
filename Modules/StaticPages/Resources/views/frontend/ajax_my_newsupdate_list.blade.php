<div class="row">
    @foreach ($records as $item)
    <div class="col-sm-12 col-md-4 col-lg-3">
        <div class="item simpleCard">
            <div class="header-thumb">
                <img class="img-responsive lazy loaded title_image" src="{{$item->ThumbPicturePath}}" alt="simple" onerror="this.src='{{onerrorReturnImage()}}'">
            </div>
            <div class="caption clear p-3">
                <div>
                    <h3 class="turnicate1 title_data">{{ $item->title }}</h3>
                    <p class="simply-text mb-0 turnicate3 title_description">{{-- {!! \Illuminate\Support\Str::limit($item->description, 10, '...') !!} --}}</p>
                    <div class="mt-2"><a href="{{ route('newsupdate.list',$item->slug) }}" class="readMore orangeshade font18 medium"> Read More </a> </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<input type="hidden" id="newsupdate_list" class="newsupdate_list" value="{{$type}}" />
<div class="bravo-pagination">
    {!! $records->appends(request()->query())->links('front_dash_pagination') !!}
</div>