@if(count($sliders)>0)
<section class="add_Slider mb-4">
    <div class="container">
        <div class="bravo-list-hotel layout_carousel m-0 ">
            <div class="list-item">
               <div class="owl-carousel owl-loaded owl-drag">
                  <div class="owl-stage-outer">
                     <div class="owl-stage" style="transform: translate3d(0px, 0px, 0px); transition: all 0.25s ease 0s; width: 1130px;">
                        @foreach($sliders as $slider)
                        <div class="owl-item active" style="width: 545px; margin-right: 20px;">
                            <div class="item-loop pb-0">
                                <div class="thumb-image pb-0">
                                    <a href="{{$slider->url}}" target="_blank">
                                        <img class="img-responsive lazy loaded" src="{{$slider->picture_path}}" alt="image not found" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                     </div>
                  </div>
               </div>
            </div>
        </div>
    </div>
</section>
@endif