<div class="profile-service-tabs">
   <div class="service-nav-tabs">
      <ul class="nav nav-tabs">
         <ul class="nav nav-tabs">
            <li class="nav-item">
               <a href="#" class="nav-link active" data-toggle="tab" data-target="#type_space">Space</a>
            </li>
         </ul>
      </ul>
   </div>
   <div class="tab-content">
        <div class="tab-pane fade  show active " id="type_space" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="bravo-list-hotel">
                <div class="row">
                    @if($spaces->total() > 0)
                      @foreach($spaces as $row)
                         <div class="col-lg-4 col-md-6">
                            @include('space::frontend.layouts.search.loop-gird')
                         </div>
                      @endforeach
                   @else
                      <div class="col-lg-12">
                          {{__("Space not found")}}
                      </div>
                   @endif
                </div>
            </div>
            <div class="bravo-pagination">
                 {{$spaces->appends(request()->query())->links('pagination::bootstrap-4')}}
                 @if($spaces->total() > 0)
                     <span class="count-string">{{ __("Showing :from - :to of :total Spaces",["from"=>$spaces->firstItem(),"to"=>$spaces->lastItem(),"total"=>$spaces->total()]) }}</span>
                 @endif
            </div>
        </div>
   </div>
</div>