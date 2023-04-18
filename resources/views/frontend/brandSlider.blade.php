<div class="bravo-list-hotel m-0" id="brandSlider_script">
    <div class="list-item">
        <div class="owl-carousel">
            @forelse($partners as $partner)
            <div class="item-loop pb-0">
                <figure><img src="{{$partner->picture_path}}" alt="image not found" /></figure>
            </div>
            @empty
            <div class="item-loop pb-0 text-center">
                No Records Found
            </div>
            @endforelse
        </div>
    </div>
</div>
