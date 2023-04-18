@extends('admin.layouts.master')
@section('title', " Property Managment ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="{{trans($model.'::menu.font_icon')}} "></i>
        Property Details
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route($model.'.index')}}">Property Managment</a></li>
        <li class="active">Property Details</li>
    </ol>
</section>
<section class="commonbg sec_pd2 bg_light animated7 fadeInLeft loan_preview content">
    <div class="box box-primary">
        <div class="box-body box-profile">
            <div class="container">
                <div class="shdow_block">
                    <div class="leftabstract"></div>
                    <div class="stepblock mB20">
                        <div class="profileblock">
                            <div class="imgblock">
                                <img src="{{ $data->CoverImgThunbnail }}" alt="proimg" id="v_UImage" onerror="imgError(this);" />
                            </div>
                            <div class="profile_name">
                                <span class="subtext">{{$data->property_name}}</span>
                                <h4 class="subhead lowercase">{{$data->propertyType->name }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-account-circle mR10"></i> Property Details</h4>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Property Name</h4>
                                        <span class="subtext"> {{$data->property_name}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Property Type</h4>
                                        <span class="subtext">{{$data->propertyType->name}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">City </h4>
                                        <span class="subtext">{{$data->city->name}}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Area</h4>
                                        <span class="subtext">{{ $data->area->name }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> State </h4>
                                        <span class="subtext">{{$data->state->name}}</span>
                                    </div>
                                </div>


                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Amount </h4>
                                        <span class="subtext">{{ numberformatWithCurrency($data->starting_amount)}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Security Amount </h4>
                                        <span class="subtext">{{ $data->security_deposit_amount ? numberformatWithCurrency($data->security_deposit_amount) : 'N/A' }}</span>
                                    </div>
                                </div>
                                @if($data->PropertyType->slug == 'hostel-pg' || $data->PropertyType->slug == 'guest-hotel' || $data->PropertyType->slug == 'hostel-pg-one-day')
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Total Seats </h4>
                                        <span class="subtext">{{ $data->total_seats}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Available Seats </h4>
                                        <span class="subtext">{{ $data->rented_seats}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Total Floors </h4>
                                        <span class="subtext">{{ $data->total_floors}}</span>
                                    </div>
                                </div>
                                @endif
                                @if(isset($data->furnished_type))
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Furnished Type </h4>
                                        <span class="subtext">{{ $data->FurnishedTypeValue}}</span>
                                    </div>
                                </div>
                                @endif

                                @forelse($data->propertyRooms as $rooms)
                                @if(isset($rooms->room_type) && $rooms->room_type!='all')
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Occupancies </h4>
                                        <span class="subtext">{{ $rooms->room_type}}</span>
                                    </div>
                                </div>
                                @if(isset($rooms->is_ac) || isset($rooms->is_non_ac))
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Food Availability </h4>
                                        <span class="subtext">{{ $rooms->ac_is_food_included == 1 || $rooms->non_ac_is_food_included == 1 ? 'Yes' : 'No'}}</span>
                                    </div>
                                </div>
                                @endif
                                @endif
                                @empty
                                @endforelse

                                @if(isset($data->carpet_area))
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Carpet Area </h4>
                                        <span class="subtext">{{ $data->CarpetAreaInSq}}</span>
                                    </div>
                                </div>
                                @endif
                                @if(isset($data->kitchen_modular))
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Kitchen Modular </h4>
                                        <span class="subtext">{{ ucfirst($data->kitchen_modular)}}</span>
                                    </div>
                                </div>
                                @endif
                                @if(isset($data->parking_space_avail))
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Parking space available </h4>
                                        <span class="subtext">{{ucfirst($data->parking_space_avail)}}</span>
                                    </div>
                                </div>
                                @endif
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Status </h4>
                                        <span class="subtext">{{ $data->status}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Available For </h4>
                                        @php
                                        $availableForFilter = config('custom.property_available_for');
                                        @endphp
                                        <span class="subtext">
                                            @foreach($data->propertyAvailableFor as $propertyAvailableFor)
                                            {{$availableForFilter[$propertyAvailableFor->available_for]}}
                                            @if($loop->iteration != $loop->last),@endif
                                            @endforeach
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Featured </h4>
                                        <span class="subtext">{{( $data->featured_property == 1 )? 'Featured': 'Not Featuring'  }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Video </h4>
                                        @if($data->video)
                                        <span class="subtext">
                                            <a href="{{ route('downloads3file') }}?fp={{$data->S3RoomVideoDownloadPath}}" target="_blank">
                                                {{ $data->video }}
                                            </a>
                                        </span>
                                        @else
                                        <span class="subtext">Not Available</span>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Electricity Bill </h4>

                                        @if($data->electricity_bill)
                                        <span class="subtext">
                                            <a href="{{ route('downloads3file') }}?fp={{$data->ElectricityBillDownloadPath}}" target="_blank">
                                                <img src="{{ $data->ElectricityBillImgPath }}" alt="Electricity Bill " width="64" height="45">
                                            </a>
                                        </span>
                                        @else
                                        <span class="subtext">Not Available</span>
                                        @endif

                                    </div>
                                </div>


                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Download Selfie </h4>
                                        <a href="{{ route('downloads3file') }}?fp={{$data->S3MyPropertySelfieDownloadPath}}" target="_blank">
                                            <img src="{{ $data->MyPropertySelfieDownload }}" alt="Download Selfie" width="64" height="45">
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Download Agreement </h4>
                                        @php
                                        $array = explode('.', $data->upload_agreement);
                                        $documentExtension = end($array);
                                        @endphp
                                        @if($documentExtension != 'pdf')
                                        <a href="{{ route('downloads3file') }}?fp={{$data->MyPropertyAgreementDownloadPath}}" target="_blank">
                                            <img src="{{ $data->MyPropertyAgreementDownload }}" alt="Download Agreement" width="64" height="45">
                                        </a>
                                        @else
                                        <p> <a href="{{ route('downloads3file') }}?fp={{$data->MyPropertyAgreementDownloadPath}}" target="_blank">{{$data->upload_agreement}}</a></p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Full Address </h4>
                                        <span class="subtext">{{$data->full_address}}</span>
                                    </div>
                                </div>

                            </div>

                            @if($data->YoutubeEmbededUrl)
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase"> Youtube Video</h4>
                                        <div class="detail-list-content yotube_section">
                                            <iframe style="width: 100%" height="170px" src="{{$data->YoutubeEmbededUrl}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif


                        </div>
                    </div>
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-info mR10"></i> Location & Maps </h4>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Lat</h4>
                                        <span class="subtext">{{$data->lat}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Long</h4>
                                        <span class="subtext">{{$data->long}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form_group mB20">
                                        <h4 class="subhead lowercase">Map Location</h4>
                                        <span class="subtext">{{$data->map_location}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(count($data->propertyAmenities)>0)
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-info mR10"></i> Selected Aminities </h4>
                            </div>
                            <div class="row">

                                <div class="add-space-checklist">
                                    <ul>
                                        @foreach($data->propertyAmenities as $list)
                                        <li>
                                            <div class="bravo-checkbox">
                                                <label>
                                                    {!! Form::checkbox('amenities_id[]', $data->id, null, ['readonly','checked','disabled','class' => 'amiview']) !!}
                                                    {{ $list->amenities->name }}
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="stepblock mB20">
                        <div class="card">
                            <div class="steptitle mB20">
                                <h4 class="subtext"> <i class="zmdi zmdi-info mR10"></i> Property Description </h4>
                            </div>
                            <div class="row">
                                <div class="g-overview">
                                    <div class="description">
                                        {!! $data->property_description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection