<table class="table table-bordered table-hover table-striped dataTable no-footer" id="data_filter">
    <thead>
        <tr>
            <th style="display:none"></th>
            <th width="05%">ID</th>
            <th width="15%" data-orderable="false">Property Info</th>
            <th width="15%" data-orderable="false">Owner Info</th>
            <th width="10%">Image</th>
            <th width="10%">Status of Selfie</th>
            <th width="10%">Status of Agreement</th>
            <th width="10%">Featured Property</th>
            <th>Owner Status</th>

            <th width="15%">Status</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($records) && count($records)>0)
        @foreach($records as $key => $list)
        <tr>
            <td style="display:none">{{$key+1}}</td>
            <td>{{$list->id}}</td>
            <td>
                Name : {{$list->property_name}}
                <br>
                Code : {{$list->property_code}}
                <br>
                Type : {{$list->PropertValue}}
                <br>
                Created : {{display_date($list->created_at)}}
                <br>
                Updated : {{display_date($list->updated_at)}}

            </td>
            <td>
                Name : {{$list->author->name}}
                <br>
                Email : {{$list->author->email}}
                <br>
                Mobile : {{$list->author->phone}}
            </td>
            <td> <a href="{{$list->CoverImg}}" data-lightbox="example-1" onerror="this.href='{{onerrorReturnImage()}}'"><img class="" style="width: 60px;" src="{{$list->CoverImgThunbnail}}" onerror="this.src='{{onerrorReturnImage()}}'"></a></td>
            <td>
                @if(strtoupper($list->status_selfie) == 'APPROVED' || strtoupper($list->status_selfie) == 'REJECTED')
                <span class="label btext-{{$list->status_selfie}}">
                    {{strtoupper($list->status_selfie)}}
                </span>
                @else
                @if($list->status_selfie)
                <a class="selfi_agreement_button" href="{{ route('downloads3file') }}?fp={{$list->S3MyPropertySelfieDownloadPath}}" target="_blank"><i class="ri-camera-line"></i> Download Selfie </a>
                <br><br>

                <span id="chngStatusSelfie{{$list->id}}">
                    {{ Form::select('status_selfie', Config::get('custom.vendor_selfie_agreement_status'), $list->status_selfie, ['data-default'=>$list->status_selfie,'class' => 'form-control','onChange'=>'statusAction(this);','data-key'=>'status_selfie','data-id'=>$list->id,'data-title'=>ucfirst($list->status_selfie),'data-action'=>route('property.changeStatus')]) }}
                </span>
                @else
                N/A
                @endif
                @endif
            </td>
            <td>
                @if(strtoupper($list->status_agreement) == 'APPROVED' || strtoupper($list->status_agreement) == 'REJECTED')
                <span class="label btext-{{$list->status_agreement}}">
                    {{strtoupper($list->status_agreement)}}
                </span>
                @else
                @if($list->status_agreement)
                <a class="selfi_agreement_button" href="{{ route('downloads3file') }}?fp={{$list->MyPropertyAgreementDownloadPath}}" target="_blank"><i class="ri-camera-line"></i> Download Agreement </a>
                <br><br>

                <span id="chngStatusAggrement{{$list->id}}">
                    {{ Form::select('status_agreement', Config::get('custom.vendor_selfie_agreement_status'), $list->status_agreement, ['class' => 'form-control','onChange'=>'statusAction(this);','data-key'=>'status_agreement','data-default'=>$list->status_agreement,'data-id'=>$list->id,'data-title'=>ucfirst($list->status_agreement),'data-action'=>route('property.changeStatus')]) }}
                </span>
                @else
                N/A
                @endif
                @endif
            </td>
            <td class="text-center">
                <input type="checkbox" data-url="{{route($model.'.featured')}}" data-id="{{$list->id}}" class="featured_property" {{$list->featured_property == 0 ?'' : 'checked'}}>
            </td>
            <td>
                @if(!$list->author->userCompleteProfileVerifired)
                @if($list->author->userCompleteProfileVerifiredIfRejected)
                <span class="label label-danger"> Rejected </span>
                @else
                N/A
                @endif
                @else
                @if($list->author->userCompleteProfileVerifired && $list->author->userCompleteProfileVerifired->status == 'pending')
                <button type="submit" title="Document Status" data-toggle="modal" data-form-url="{{route('vendor.update',[$list->author->id])}}" data-url="{{route('vendor.documentVerificationStatus',[$list->author->id])}}" data-target="#modal-default" class="manageAccount btn btn-primary" data-placement="top">Approve</button>
                <button type="submit" id="VendorForm1" data-default="decline" data-url="{{route('vendor.documentVerificationStatus.update',[$list->author->userCompleteProfileVerifired->id])}}" data-title="Decline" class="btn btn-danger changestatus" data-reload="yes">Decline</button>
                @endif
                @if(isset($list->author->userCompleteProfileVerifired) && $list->author->userCompleteProfileVerifired->status == 'approved')
                <span class="label label-success">Approved</span>
                @endif
                @endif
            </td>
            <td>
                <span id="chngStatus{{$list->id}}">
                    {{ Form::select('status', Config::get('custom.property_status'), $list->status, ['class' => 'form-control','onChange'=>'statusAction(this);','data-key'=>'status','data-default'=>$list->status,'data-id'=>$list->id,'data-title'=>ucfirst($list->status),'data-action'=>route('property.changeStatus')]) }}
                </span>
                {{--
                    @if(strtoupper($list->status) == 'PUBLISH' || strtoupper($list->status) == 'REJECT')
                    <span class="label btext-{{$list->status}}">
                {{strtoupper($list->status)}}
                </span>
                @else
                <span id="chngStatus{{$list->id}}">
                    {{ Form::select('status', Config::get('custom.property_status'), $list->status, ['class' => 'form-control','onChange'=>'statusAction(this);','data-key'=>'status','data-default'=>$list->status,'data-id'=>$list->id,'data-title'=>ucfirst($list->status),'data-action'=>route('property.changeStatus')]) }}
                </span>
                @endif
                --}}
            </td>
            <td>
                <span class="margin-r-5"><a data-toggle="tooltip" class="" title="View" href="{{ route('property.details',$list->slug) }}"><i class="fa fa-eye" aria-hidden="true"></i></a> </span>
                <span class="margin-r-5"><a data-toggle="tooltip" class="" title="Edit" href="{{ route('admin.manageProperty.edit',$list->slug) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a> </span>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@if(isset($records))
<div class="pull-right">
    {!! $records->appends(request()->query())->links('pagination') !!}
</div>
@endif