<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Document Status</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <img class="profile-user-img img-responsive img-circle" src="{{$data->ThumbPicturePath}}" alt="User profile picture" onerror="this.src='{{onerrorProImage()}}'">
                <h3 class="profile-username text-center">{{ucfirst($data->name)}}</h3>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Adhar Card Number</b>
                        <p class="pull-right">{{$data->userCompleteProfileVerifired['aadhar_card_number'] ? $data->userCompleteProfileVerifired['aadhar_card_number'] : 'N/A'}}
                        </p>
                    </li>
                    <li class="list-group-item">
                        <b>GST Number</b>
                        <p class="pull-right">{{$data->userCompleteProfileVerifired['gst_number'] ? $data->userCompleteProfileVerifired['gst_number'] : 'N/A'}}
                        </p>
                    </li>
                    <li class="list-group-item">
                        <b>Aadhar card document</b>
                        @if($data->userCompleteProfileVerifired['adhar_card_doc'])

                        <a class="pull-right" href="{{ route('downloads3file') }}?fp={{$data->userCompleteProfileVerifired['S3AadharDocPath']}}" target="_blank">
                            <i class="fa fa-file-o "></i>
                        </a>
                        @else
                        <p class="pull-right">N/A</p>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <b>Selfie image</b>
                        @if($data->userCompleteProfileVerifired['selfy_image'])

                        <a class="pull-right" href="{{ route('downloads3file') }}?fp={{$data->userCompleteProfileVerifired['S3SelfyDownloadPath']}}" target="_blank">
                            <i class="fa fa-file-o "></i>
                        </a>
                        @else
                        <p class="pull-right">N/A</p>
                        @endif
                    </li>
                    {{--
                        <li class="list-group-item">
                        <b>Logo image</b>
                        @if($data->userCompleteProfileVerifired['logo_image'])
                        <a class="pull-right" href="{{ route('downloads3file') }}?fp={{$data->userCompleteProfileVerifired['S3LogoDownloadPath']}}" target="_blank">
                    <i class="fa fa-file-o "></i>
                    </a>
                    @else
                    <p class="pull-right">N/A</p>
                    @endif
                    </li>
                    --}}

                    <li class="list-group-item">
                        <b>{{trans($model.'::menu.sidebar.form.status')}}</b>
                        <p class="pull-right">
                            <span class="label {{$data->userCompleteProfileVerifired['status']=='approved'?'label-success':'label-danger'}}">{{$data->userCompleteProfileVerifired['status'] ? ucfirst($data->userCompleteProfileVerifired['status']) : 'N/A'}}</span>
                        </p>
                    </li>
                    @if($data->userCompleteProfileVerifired['status'] !='pending')
                    <li class="list-group-item">
                        <b>{{$data->userCompleteProfileVerifired['status']=='approved' ? 'Approved' : 'Declined'}} date</b>
                        <p class="pull-right">{{$data->userCompleteProfileVerifired['action_date'] ? date('m-d-Y',strtotime($data->userCompleteProfileVerifired['action_date'])): 'N/A'}}</p>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="modal-footer">
            @if($data->userCompleteProfileVerifired['status'] == 'pending')
            <button type="submit" id="VendorForm" data-default="approve" data-url="{{route('vendor.documentVerificationStatus.update',[$data->userCompleteProfileVerifired['id']])}}" data-title="Approve" class="btn btn-primary changestatus" data-reload="yes">Approve</button>
            @endif
        </div>
    </div>
</div>