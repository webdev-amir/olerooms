$(function () {
    $('.numberonly').keypress(validateNumber);
    DirectFormSubmitWithAjax.init();
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function getMetaContentByName(name, content) {
    var content = (content == null) ? 'content' : content;
    return document.querySelector("meta[name='" + name + "']").getAttribute(content);
}
function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if (key < 48 || key > 57) {
        return false;
    }
    else {
        return true;
    }
};

jQuery(document).ready(function () {
    $(".showAlert").click(function () {
        var type = $(this).data('type');
        var message = $(this).data('message');
        Lobibox.alert(type,
            {
                msg: message
            });
    });
    if (document.getElementById("srart_date")) {
        $('#srart_date').datepicker({
            format: 'yyyy-mm-dd',
            onSelect: function (date) {
                var date2 = $('#srart_date').datepicker('getDate');
                date2.setDate(date2.getDate() + 1);
                $('#end_date').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#end_date').datepicker('option', 'minDate', date2);
            }
        });
    }
    if (document.getElementById("end_date")) {
        $('#end_date').datepicker({
            format: 'yyyy-mm-dd',
            onClose: function () {
                var dt1 = $('#srart_date').datepicker('getDate');
                var dt2 = $('#end_date').datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = $('#end_date').datepicker('option', 'minDate');
                    $('#end_date').datepicker('setDate', minDate);
                }
            }
        });
    }
    jQuery("#validateForm").validate({
        ignore: [],
        rules: {
            email: {
                email: true,
            },
            'g-recaptcha-response': {
                required: function () {
                    if (grecaptcha.getResponse() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            password: {
                minlength: 8
            },
            password_confirmation: {
                equalTo: '#password'
            },
            body: {
                required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                    return editorcontent.length === 0;
                }
            },
            description_en: {
                required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                    return editorcontent.length === 0;
                }
            },
        },
        messages: {
            email: {
                email: enter_correct_email,
            },
            password: {
                minlength: must_minimum_digit_pwd
            },
            password_confirmation: {
                equalTo: _enter_same_as_passowed,
            },
            'g-recaptcha-response': verify_you_are_human
        },
        highlight: function (element) {
            jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        errorPlacement: function (e, r) {
            e.appendTo(r.closest('.ermsg'));
        },
        success: function (label, element) {
            jQuery(element).closest('.form-group').removeClass('has-error');
            label.remove();
        },
        submitHandler: function (form) {
            $(".formsubmit").attr("disabled", true);
            form.submit();
        }
    });
    $(".onlyimageupload").change(function (event) {
        _imageUpload = $(this).data('uploadurl');
        var MediaId = this.id;
        var inter;
        Lobibox.progress({
            title: 'Please wait',
            label: 'Uploading files...',
            progressTpl: '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () {

            },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('.myprogress').text(percentComplete + '%');
                        $('.myprogress').css('width', percentComplete + '%');
                        var i = 0;
                    }
                }, false);
                return xhr;
            },
            closed: function () {
                // 
            }
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName('csrf-token'));
        if (MediaId == 'PImage' || MediaId == 'imageUpload') {
            data.append("user_id", $(this).data('userid'));
        }
        if (files.length > 0) { data.append("files", files[0]); }
        else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify('info', {
                        position: "top right",
                        rounded: false,

                        delayIndicator: true,
                        msg: "Please select file to upload."
                    });
                })();
            });
            return false;
        }
        var extension = $("#" + MediaId).val().split('.').pop().toUpperCase();
        if (extension != "PNG" && extension != "JPG" && extension != "GIF" && extension != "JPEG" && extension != "SVG") {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify('error', {
                        position: "top right",
                        rounded: false,

                        delayIndicator: true,
                        msg: "Invalid image file format."
                    });
                })();
            });
            $("#" + MediaId).val('');
            return false;
        }
        $.ajax({
            type: "post",
            enctype: 'multipart/form-data',
            url: _imageUpload,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('.myprogress').text(percentComplete + '%');
                        $('.myprogress').css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            beforeSend: function () {

            },
            success: function (data) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                if (data['status']) {
                    Lobibox.notify('success', {
                        position: "top right",
                        msg: 'File has been uploaded successfully'
                    });
                    if (MediaId == 'PImage' || MediaId == 'imageUpload') {
                        $('#head_dp').attr('src', _UserImgThumbSrc + data['filename']);
                        $('#imagePreview').css('background-image', 'url(' + _UserImgThumbSrc + data['filename'] + ')');
                    }
                    $("#f_" + MediaId).val(data['filename']);
                    $('#dash_' + MediaId).attr('src', _UserImgThumbSrc + data['filename']);
                    $("#f_" + MediaId).val(data['filename']);
                } else {
                    Lobibox.notify('error', {
                        position: "top right",
                        msg: data['message']
                    });
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery('#logo-duplicate_' + MediaId).val('');
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, (k, v) => {
                    if (typeof v != 'object') {
                        error += v + "<br>"
                    }
                })
                Lobibox.notify('error', {
                    position: "top right",
                    rounded: false,

                    delayIndicator: true,
                    msg: error
                });
            }
        });
    });
    $(".onlyimageanddocupload").change(function (event) {
        _imageUpload = $(this).data('uploadurl');
        var MediaId = this.id;
        var inter;
        Lobibox.progress({
            title: 'Please wait',
            label: 'Uploading files...',
            progressTpl: '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () {

            },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('.myprogress').text(percentComplete + '%');
                        $('.myprogress').css('width', percentComplete + '%');
                        var i = 0;
                    }
                }, false);
                return xhr;
            },
            closed: function () {
                // 
            }
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName('csrf-token'));
        if (MediaId == 'PImage' || MediaId == 'imageUpload') {
            data.append("user_id", $(this).data('userid'));
        }
        if (files.length > 0) { data.append("files", files[0]); }
        else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify('info', {
                        position: "top right",
                        rounded: false,

                        delayIndicator: true,
                        msg: "Please select file to upload."
                    });
                })();
            });
            return false;
        }
        var extension = $("#" + MediaId).val().split('.').pop().toUpperCase();
        if (extension != "PDF") {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify('error', {
                        position: "top right",
                        rounded: false,

                        delayIndicator: true,
                        msg: "Invalid PDF file format."
                    });
                })();
                $("#" + MediaId).val('');
            });
            return false;
        }
        $.ajax({
            type: "post",
            enctype: 'multipart/form-data',
            url: _imageUpload,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('.myprogress').text(percentComplete + '%');
                        $('.myprogress').css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            beforeSend: function () {

            },
            success: function (data) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                if (data['status']) {
                    console.log(data)
                    Lobibox.notify('success', {
                        position: "top right",
                        msg: 'File has been uploaded successfully'
                    });
                    if (MediaId == 'PImage' || MediaId == 'imageUpload') {
                        $('#head_dp').attr('src', _UserImgThumbSrc + data['filename']);
                        $('#imagePreview').css('background-image', 'url(' + _UserImgThumbSrc + data['filename'] + ')');
                    }
                    $("#val").val(data['filename']);
                    $("#f_" + MediaId).val(data['filename']);
                    $('#dash_' + MediaId).attr('src', _UserImgThumbSrc + data['filename']);
                    $("#f_" + MediaId).val(data['filename']);
                } else {
                    Lobibox.notify('error', {
                        position: "top right",
                        msg: data['message']
                    });
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery('#logo-duplicate_' + MediaId).val('');
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, (k, v) => {
                    if (typeof v != 'object') {
                        error += v + "<br>"
                    }
                })
                Lobibox.notify('error', {
                    position: "top right",
                    rounded: false,

                    delayIndicator: true,
                    msg: error
                });
            }
        });
    });
});


var DirectFormSubmitWithAjax = function () {
    "use strict";
    return {
        init: function () {
            $('.directSubmit').click(function () {
                var submitFormId = 'F_' + this.id;
                var action = $("#" + submitFormId).attr("action");
                $("#" + submitFormId).validate({
                    ignore: [],
                    rules: {
                        password: {
                            minlength: 8,
                            required: function () {
                                if ($("#old_password").val() == '')
                                    return false;
                                else
                                    return true;
                            }
                        },
                        npass: {
                            minlength: 8
                        },
                        password_confirmation: {
                            equalTo: "#npass"
                        },
                        'g-recaptcha-response': {
                            required: function () {
                                if (grecaptcha.getResponse() == '') {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                    },
                    messages: {
                        password: {
                            minlength: must_minimum_digit_pwd
                        },
                        npass: {
                            minlength: must_minimum_digit_pwd
                        },
                        password_confirmation: {
                            equalTo: _enter_same_as_passowed
                        },
                        'g-recaptcha-response': verify_you_are_human
                    },
                    errorPlacement: function (e, r) {
                        e.appendTo(r.closest('.ermsg'));
                    },
                    submitHandler: function (form) {
                        $(".directSubmit").prop("disabled", true);
                        $(".reset_loader").show();
                        $(".lobibox-close").trigger('click');
                        $(form).ajaxSubmit({
                            url: action,
                            type: "POST",
                            cache: false,
                            success: function (data) {
                                $('.directSubmit').prop("disabled", false);
                                if (data['reset']) {
                                    if (data['gcp-reset']) {
                                        grecaptcha.reset();
                                    }
                                    document.getElementById(submitFormId).reset();
                                }
                                Lobibox.notify(data['type'], {
                                    position: "top right",
                                    msg: data['message']
                                });
                                if (data['status_code'] == 200) {
                                    if (data['html']) {
                                        $("#result").html('');
                                        $("#result").html(JSON.parse(data['body']));
                                    }
                                    if (data['url']) {
                                        location.href = data['url'];
                                    }
                                }
                            },
                            error: function (e) {
                                $(".reset_loader").hide();
                                $('.directSubmit').prop("disabled", false);
                                var Arry = e.responseText;
                                var error = "";
                                JSON.parse(Arry, function (k, v) {
                                    if (typeof v != 'object') {
                                        error += v + "<br>"
                                    }
                                })
                                Lobibox.notify('error', {
                                    rounded: false,
                                    delay: 5000,
                                    delayIndicator: true,
                                    position: "top right",
                                    msg: error
                                });
                            }
                        });
                    },
                });
            });
        }
    }
}();
