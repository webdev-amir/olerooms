<div class="panel-body">
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('settings::menu.sidebar.form.name')}}<span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>trans('settings::menu.sidebar.form.name'),'title'=>'Please enter setting name','maxlength'=>100,'autocomplete'=>'off']) }}
        </div>
    </div>
    @if($data->slug == 'auto-confirmation' || $data->slug == 'booking-rejection-time')
    <div class="form-group">
        <label class="col-sm-2 control-label">@if($data->slug == 'auto-confirmation')Auto Confirmation @else Rejection Time @endif <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'Auto Confirmation or Rejection Time','title'=>'Please enter time(in minutes)','autocomplete'=>'off', 'maxlength'=>2]) }}
            <div class="description">
                <small>
                    @if($data->slug == 'booking-rejection-time')
                    Update time in minutes for rejection.
                    @else
                    Update time in minutes for auto confirmation.
                    @endif
                </small>
            </div>
        </div>
    </div>
    @elseif($data->slug == 'schedule_visit_amount')
    <div class="form-group">
        <label class="col-sm-2 control-label">@if($data->slug == 'schedule_visit_amount')Schedule Visit Amount @endif <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'enter schedule amount','title'=>'Please enter amount','autocomplete'=>'off', 'maxlength'=>4]) }}
            <div class="description"><small>Update schedule visit amount .</small></div>
        </div>
    </div>
    @elseif($data->slug == 'company-points-percetage')
    <div class="form-group">
        <label class="col-sm-2 control-label">@if($data->slug == 'company-points-percetage')Company commission % @endif <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'Enter company commission %','title'=>'Please enter amount','autocomplete'=>'off', 'maxlength'=>2]) }}
            <div class="description"><small>Update company commission %.</small></div>
        </div>
    </div>


    @elseif($data->slug == 'agent-points-percetage')
    <div class="form-group">
        <label class="col-sm-2 control-label">@if($data->slug == 'agent-points-percetage')Agent commission % @endif <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'Enter agent commission %','title'=>'Please enter amount','autocomplete'=>'off', 'maxlength'=>2]) }}
            <div class="description"><small>Update agent commission %.</small></div>
        </div>
    </div>


    @elseif($data->slug == 'forntend-user-discount-percentage-company')
    <div class="form-group">
        <label class="col-sm-2 control-label">@if($data->slug == 'forntend-user-discount-percentage-company')Company code discount % @endif <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'Enter company code discount %','title'=>'Please enter amount','autocomplete'=>'off', 'maxlength'=>2]) }}
            <div class="description"><small>Update company code discount % .</small></div>
        </div>
    </div>


    @elseif($data->slug == 'forntend-user-discount-percentage-agent')
    <div class="form-group">
        <label class="col-sm-2 control-label">@if($data->slug == 'forntend-user-discount-percentage-agent')Agent code discount % @endif <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'Enter agent code discount %','title'=>'Please enter amount','autocomplete'=>'off', 'maxlength'=>2]) }}
            <div class="description"><small>Update agent code discount % .</small></div>
        </div>
    </div>


    @elseif($data->slug == 'comission-percentage')
    <div class="form-group">
        <label class="col-sm-2 control-label">@if($data->slug == 'comission-percentage')Commission in % @endif <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'enter schedule amount','title'=>'Please enter amount','autocomplete'=>'off', 'maxlength'=>2]) }}
            <div class="description"><small>Update comission percentage %.</small></div>
        </div>
    </div>

    @elseif($data->slug == 'hostel-pg-min-booking-tenure')
    <div class="form-group">
        <label class="col-sm-2 control-label">@if($data->slug == 'hostel-pg-min-booking-tenure') Tenure in months @endif <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'enter schedule amount','title'=>'Please enter amount','autocomplete'=>'off', 'maxlength'=>2]) }}
            <div class="description"><small>Update hostel pg min re-booking gap tenure.</small></div>
        </div>
    </div>
    @elseif($data->slug == 'booking-cancelled-before-time')
    <div class="form-group">
        <label class="col-sm-2 control-label">Property Booking cancel allow time in Hours <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'Enter cancel time','title'=>'Please enter hour','autocomplete'=>'off', 'maxlength'=>3]) }}
            <div class="description"><small>Time enter in hours</small></div>
        </div>
    </div>
    @elseif($data->slug == 'schedule-cancelled-before-time')
    <div class="form-group">
        <label class="col-sm-2 control-label">Schedule Booking cancelled allow time in Hours <span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg">
            {{ Form::text('val',null, ['required','class'=>'form-control isinteger','id'=>'val','placeholder'=>'Enter cancel time','title'=>'Please enter hour','autocomplete'=>'off', 'maxlength'=>3]) }}
            <div class="description"><small>Time enter in hours</small></div>
        </div>
    </div>
    @elseif($data->slug == 'upload-document')
    <div class="form-group">
        <label class="col-sm-2 control-label">Aggrement Document/Pdf<span class="asterisk">*</span></label>
        <div class="col-sm-8 ermsg attactmentFile">
            <input type="file" id="EBill" name="EBill" class="form-control imageandpdfupload" @if(isset($data->val)) @else required @endif data-uploadurl="{{route('settings.mediaStore')}}">
            {{ Form::hidden('val',null, ['id'=>'f_EBill','title'=>'Please upload aggrement document pdf']) }}
            @if($data->val)
            <br>
            <strong>Filename: {{$data->val}}</strong>
            @endif
        </div>
    </div>
    @endif
</div>
@section('uniquePageScript')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    function getMetaContentByName(name, content) {
        var content = (content == null) ? 'content' : content;
        return document.querySelector("meta[name='" + name + "']").getAttribute(content);
    }
    $("body").delegate(".imageandpdfupload", "change", function(event) {
        var MediaId = this.id;
        _uploadDocUrl = $(this).data("uploadurl");
        _downloadUrl = $(this).data("downloadurl");
        var inter;
        Lobibox.progress({
            title: "Please wait",
            label: "Uploading files...",
            progressTpl: '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function() {},
            onShow: function($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $(".myprogress").text(percentComplete + "%");
                            $(".myprogress").css(
                                "width",
                                percentComplete + "%"
                            );
                            var i = 0;
                        }
                    },
                    false
                );
                return xhr;
            },
            closed: function() {

            },
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName("csrf-token"));
        if (files.length > 0) {
            data.append("files", files[0]);
        } else {
            $(function() {
                (function() {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("info", {
                        position: "top right",
                        rounded: false,

                        delayIndicator: true,
                        msg: "Please select file to upload.",
                    });
                })();
            });
            return false;
        }
        var extension = $("#" + MediaId)
            .val()
            .split(".")
            .pop()
            .toUpperCase();
        if (extension != "PDF") {
            $(".btn-close").trigger("click");
            $(".lobibox-close").click();
            Lobibox.notify("error", {
                position: "top right",
                rounded: false,

                delayIndicator: true,
                msg: "Invalid file format.",
            });
            $("#" + MediaId).val("");
            $("#f_" + MediaId).val("");
            return false;
        }
        $.ajax({
            type: "post",
            enctype: "multipart/form-data",
            url: _uploadDocUrl,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $(".myprogress").text(percentComplete + "%");
                            $(".myprogress").css(
                                "width",
                                percentComplete + "%"
                            );
                        }
                    },
                    false
                );
                return xhr;
            },
            success: function(data) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                Lobibox.notify(data["type"], {
                    position: "top right",
                    msg: data["message"],
                });
                if (data["status_code"] == 200) {
                    $("#f_" + MediaId).val(data["filename"]);
                    $("#" + MediaId).val(data["filename"]);
                }
            },
            error: function(e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery("#logo-duplicate_" + MediaId).val("");
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, function(k, v) {
                    if (typeof v != "object") {
                        error += v + "<br>";
                    }
                });
                Lobibox.notify("error", {
                    position: "top right",
                    rounded: false,

                    delayIndicator: true,
                    msg: error,
                });
            },
        });
    });
</script>
@endsection