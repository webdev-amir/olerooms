<div class="col-sm-12 col-md-6 col-lg-6">
    <h4 class="font18 black medium mb-2"> Upload Video </h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadVideofile" id="video_files">
                <div class="uploadfileBtn">
                    <input type="file" id="video" accept="video/mp4,video/x-m4v,video/*" class="onlyvideoupload" data-uploadurl="{{route('manageProperty.uploadRoomVideo',[auth()->user()->id])}}" />
                    <i class="ri-upload-line d-block"></i>
                    Upload video
                </div>
            </div>
            <div id="prev_video">
                @if($formData && @$sessionAllData->RoomVideo)
                <p class="mb5"><a href="{{ route('downloads3file') }}?fp={{@$sessionAllData->S3RoomVideoDownloadPath}}" title="Download Video" target="_blank"><img src="{{asset('/public/img/download-video-1.png')}}" /></a></p>
                @endif
            </div>
        </div>
        {{ Form::hidden('video',null, ['id'=>'f_video','class'=>'video_files','title'=>'Please upload video']) }}
    </div>
</div>
<div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-group ermsg">
        <label>Youtube Video URL</label>
        {{ Form::url('video_url',null, ['class'=>'form-control','id'=>'video_url','placeholder'=>"Youtube Video URL",'autocomplete'=>'off','pattern'=>'https://.*']) }}
    </div>
</div>