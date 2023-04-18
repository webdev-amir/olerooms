const { event } = require("jquery");

$(function () {
    $(document).on('keypress', '.numberonly', validateNumber);
    $(document).on('keypress', '.isinteger', isNumber);
    DirectFormSubmitWithAjax.init();
});

$(document).ready(function () {
    $(document).on('click', '.myBookingButton123', function (e) {
        e.preventDefault();
        var property_slug = $(this).data('id');
        var action = $(this).data('action');
        var token = getMetaContentByName('csrf-token');
        var redirectAnch = $(this).attr('href');
        if (action != '') {
            $.ajax({
                url: action,
                type: "POST",
                cache: false,
                data: {
                    property_slug: property_slug,
                    _token: token,
                },
                // dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data != '') {
                        window.location.replace(redirectAnch);
                    }
                    // return true;
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        }
        return true;
    });

    if (document.getElementById("counterPage")) {
        const counters = document.querySelectorAll('.counter_about');
        counters.forEach( counter => { 
           const animate = () => {
              const value = +counter.getAttribute('data-count');
              const speed = +counter.getAttribute('data-speed');
              const sign  =   counter.getAttribute('data-sign');
              const data = +counter.innerText;
              const time = value / speed;
             if(data < value) {
                  counter.innerText = Math.ceil(data + time);
                  setTimeout(animate, 1);
                }else{
                  counter.innerText = value+sign;
                }
             
           }
           animate();
        });
    }

    if (_currentRname == "home" || _currentRname == "login") {
        setTimeout(function () {
            //window.location.href = window.location.href+"#notification";
            $("#exitPopUp").modal("show");
            $("#exitPopUpInvest").modal("show");
        }, 800);
    }
    //Code Verification
    var verificationCode = [];
    $(".verification-code input[type=text]").keyup(function (e) {
        $(".verification-code input[type=text]").each(function (i) {
            verificationCode[i] = $(".verification-code input[type=text]")[i].value;
            $("#mobile_otp").val(String(verificationCode.join("")));
        });

        if ($(this).length > 0 && e.key != "Backspace") {
            $(this).next().focus();
        } else {
            if (e.key == "Backspace") {
                $(this).prev().focus();
            }
        }
    }); // keyup

    if (document.getElementById("my_profile")) {
        $("#dob").datepicker({
            format: "dd-mm-yyyy",
            todayHighlight: true,
            autoclose: true,
            endDate: new Date(),
        });
    }

    $(document).on("click", ".resend-mobile-otp", function (e) {
        e.preventDefault();
        var action = $(this).attr("href");
        $.ajax({
            url: action,
            type: "GET",
            cache: false,
            dataType: "json",
            beforeSend: function () {
                $("#loader_msg").html("Please wait, sending your otp");
                $("#loader").show();
            },
            complete: function () {
                $("#loader").hide();
            },
            success: function (data) {
                $(".lobibox-close").trigger("click");
                Lobibox.notify(data["type"], {
                    position: "top right",
                    msg: data["message"],
                });

                if (data["url"]) {
                    location.href = data["url"];
                }
            },
            error: function (e) {
                Lobibox.notify(data["type"], {
                    position: "top right",
                    msg: data["message"],
                });
            },
        });
    });

    $(document).on("click", ".delete-user-account", function (e) {
        e.preventDefault();
        var action = $(this).attr("data-url");
        if (confirm("Are you sure you want to delete your account!")) {
            $.ajax({
                url: action,
                type: "GET",
                cache: false,
                dataType: "json",
                beforeSend: function () {
                    $("#loader_msg").html(_loaderMsg);
                    $("#loader").show();
                },
                complete: function () {
                    $("#loader").hide();
                },
                success: function (data) {
                    $(".lobibox-close").trigger("click");
                    Lobibox.notify(data["type"], {
                        position: "top right",
                        msg: data["message"],
                    });

                    if (data["url"]) {
                        location.href = data["url"];
                    }
                },
                error: function (e) {
                    Lobibox.notify(data["type"], {
                        position: "top right",
                        msg: data["message"],
                    });
                    if (data["url"]) {
                        location.href = data["url"];
                    }
                },
            });
        }
    });
});

$(".light-modal-close-icon").click(function () {
    var _currentUrl = $(this).data("currenturl");
    setTimeout(function () {
        window.history.pushState(
            { url: "" + _currentUrl + "" },
            "",
            _currentUrl
        );
    }, 500);
});
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$.validator.addMethod(
    "special_char_password",
    function (value, element) {
        return (
            this.optional(element) ||
            /^.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[\W_]).*$/.test(
                value
            )
        );
    },
    "The password must contain a minimum of one lower case character," +
    " one upper case character, one digit and one special character.."
);

function getMetaContentByName(name, content) {
    var content = content == null ? "content" : content;
    return document
        .querySelector("meta[name='" + name + "']")
        .getAttribute(content);
}
function validateNumber(event) {

    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if (key < 48 || key > 57) {
        return false;
    } else {
        return true;
    }
}
// Restricts input for the given textbox to the given inputFilter.
function isNumber(evt) {
    evt = evt ? evt : window.event;
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    // alert(charCode);
    return true;
}

jQuery(document).ready(function () {
    $(".showAlert").click(function () {
        var type = $(this).data("type");
        var message = $(this).data("message");
        Lobibox.alert(type, {
            msg: message,
        });
    });
    $(".changeregtab").click(function () {
        var _regtype = $(this).data("regtype");
        $("#reg_type").html(_regtype);
    });
    jQuery("#validateForm").validate({
        ignore: [],
        rules: {
            email: {
                email: true,
            },
            "g-recaptcha-response": {
                required: function () {
                    if (grecaptcha.getResponse() == "") {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            password: {
                minlength: 8,
            },
            password_confirmation: {
                equalTo: "#password",
            },
            body: {
                required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, "");
                    return editorcontent.length === 0;
                },
            },
            description_en: {
                required: function (textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, "");
                    return editorcontent.length === 0;
                },
            },
        },
        messages: {
            email: {
                email: enter_correct_email,
            },
            password: {
                minlength: must_minimum_digit_pwd,
            },
            password_confirmation: {
                equalTo: _enter_same_as_passowed,
            },
            "g-recaptcha-response": verify_you_are_human,
        },
        highlight: function (element) {
            jQuery(element)
                .closest(".form-group")
                .removeClass("has-success")
                .addClass("has-error");
        },
        errorPlacement: function (e, r) {
            e.appendTo(r.closest(".ermsg"));
        },
        success: function (label, element) {
            jQuery(element).closest(".form-group").removeClass("has-error");
            label.remove();
        },
        submitHandler: function (form) {
            $(".formsubmit").attr("disabled", true);
            form.submit();
        },
    });
    $("body").delegate(".onlyimageupload", "change", function (event) {
        _imageUpload = $(this).data("uploadurl");
        var MediaId = this.id;
        var inter;
        Lobibox.progress({
            title: "Please wait",
            label: "Uploading files...",
            progressTpl:
                '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () { },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            closed: function () {
                //
            },
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName("csrf-token"));
        if (MediaId == "PImage" || MediaId == "UImage" || MediaId == "imageUpload") {
            data.append("user_id", $(this).data("userid"));
        }
        if (files.length > 0) {
            data.append("files", files[0]);
        } else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("info", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
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
        if (
            extension != "PNG" &&
            extension != "JPG" &&
            extension != "GIF" &&
            extension != "JPEG"
        ) {
            if (files.length > 0) {
                data.append("files", files[0]);
            } else {
                $(function () {
                    (function () {
                        $(".btn-close").trigger("click");
                        $(".lobibox-close").click();
                        Lobibox.notify("info", {
                            position: "top right",
                            rounded: false,
                            delay: 2000,
                            delayIndicator: true,
                            msg: "Please select file to upload.",
                        });
                    })();
                });
                return false;
            }
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("error", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
                        delayIndicator: true,
                        msg: "Invalid image file format.",
                    });
                })();
            });

            $("#" + MediaId).val("");
            return false;
        }
        $.ajax({
            type: "post",
            enctype: "multipart/form-data",
            url: _imageUpload,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            beforeSend: function () { },
            success: function (data) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                if (data["status"]) {
                    Lobibox.notify("success", {
                        position: "top right",
                        msg: "File has been uploaded successfully",
                    });
                    $(".prodp label.error").hide();
                    if (data["status_code"] == 250) {
                        if (data["s3FullPath"] != "") {
                            $("#f_" + MediaId).val(data["filename"]);
                            $("#v_" + MediaId).attr("src", data["s3FullPath"]);
                            if (MediaId == 'UImage') {
                                $(".head_dp").attr("src", data["s3FullPath"]);
                            }
                        } else {
                            $("#f_" + MediaId).val(data["filename"]);
                            $("#v_" + MediaId).attr(
                                "src",
                                _UserImgThumbSrc + data["filename"]
                            );
                        }
                    } else {
                        if (MediaId == "PImage" || MediaId == "imageUpload") {
                            $("#head_dp").attr(
                                "src",
                                _UserImgThumbSrc + data["filename"]
                            );
                            $("#imagePreview").css(
                                "background-image",
                                "url(" +
                                _UserImgThumbSrc +
                                data["filename"] +
                                ")"
                            );
                        }
                        $("#f_" + MediaId).val(data["filename"]);
                        $("#dash_" + MediaId).attr(
                            "src",
                            _UserImgThumbSrc + data["filename"]
                        );
                        $("#f_" + MediaId).val(data["filename"]);
                    }
                } else {
                    Lobibox.notify("error", {
                        position: "top right",
                        msg: data["message"],
                    });
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery("#logo-duplicate_" + MediaId).val("");
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, (k, v) => {
                    if (typeof v != "object") {
                        error += v + "<br>";
                    }
                });
                Lobibox.notify("error", {
                    position: "top right",
                    rounded: false,
                    delay: 2000,
                    delayIndicator: true,
                    msg: error,
                });
            },
        });
    });

    $("body").delegate(".onlyimageuploadmultiple", "change", function (event) {
        _imageUpload = $(this).data("uploadurl");
        _fieldname = $(this).data("fieldname");
        _refimagedivid = $(this).data("refimagedivid");
        _maxfile = $(this).data("maxfile");
        var MediaId = this.id;
        var inter;
        var _totalUploads = document.querySelectorAll(
            '.pip > input[name="' + _fieldname + '[]"]'
        );
        if (_maxfile <= _totalUploads.length) {
            $(".btn-close").trigger("click");
            $(".lobibox-close").click();
            Lobibox.notify("error", {
                position: "top right",
                rounded: false,
                delay: 2000,
                delayIndicator: true,
                msg: "You cannot upload more then " + _maxfile + " Image",
            });
            return false;
        }
        Lobibox.progress({
            title: "Please wait",
            label: "Uploading files...",
            progressTpl:
                '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () { },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            closed: function () {
                //
            },
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName("csrf-token"));
        if (MediaId == "PImage" && MediaId == "imageUpload") {
            data.append("user_id", $(this).data("userid"));
        }
        if (files.length > 0) {
            data.append("files", files[0]);
        } else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("info", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
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
        if (
            extension != "PNG" &&
            extension != "JPG" &&
            extension != "GIF" &&
            extension != "JPEG"
        ) {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("error", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
                        delayIndicator: true,
                        msg: "Invalid image file format.",
                    });
                })();
            });
            $("#" + MediaId).val("");
            return false;
        }
        $.ajax({
            type: "post",
            enctype: "multipart/form-data",
            url: _imageUpload,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            beforeSend: function () { },
            success: function (data) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                if (data["status"]) {
                    Lobibox.notify("success", {
                        position: "top right",
                        msg: "File has been uploaded successfully",
                    });
                    $(".prodp label.error").hide();
                    if (extension == "PDF") {
                        $(
                            '<span class="pip">' +
                            '<img class="imageThumb" src="' +
                            data["full_path"] +
                            '" title="' +
                            data["filename"] +
                            '"/><input type="hidden" value="' +
                            data["filename"] +
                            '" name="' +
                            _fieldname +
                            '[]"/>' +
                            '<br/><span class="remove removerecord" data-remove="' +
                            _fieldname +
                            '">Remove Record</span>' +
                            "</span>"
                        ).insertAfter("#" + _refimagedivid);
                    } else {
                        $(
                            '<span class="pip">' +
                            '<img class="imageThumb" src="' +
                            data["full_path"] +
                            '" title="' +
                            data["filename"] +
                            '"/><input type="hidden" value="' +
                            data["filename"] +
                            '" name="' +
                            _fieldname +
                            '[]"/>' +
                            '<br/><span class="remove removerecord" data-remove="' +
                            _fieldname +
                            '">Remove Record</span>' +
                            "</span>"
                        ).insertAfter("#" + _refimagedivid);
                    }
                    var __count_fields = document.querySelectorAll(
                        '.pip > input[name="' + _fieldname + '[]"]'
                    );
                    $("#f_" + MediaId).val(__count_fields.length);
                } else {
                    Lobibox.notify("error", {
                        position: "top right",
                        msg: data["message"],
                    });
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery("#logo-duplicate_" + MediaId).val("");
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, (k, v) => {
                    if (typeof v != "object") {
                        error += v + "<br>";
                    }
                });
                Lobibox.notify("error", {
                    position: "top right",
                    rounded: false,
                    delay: 2000,
                    delayIndicator: true,
                    msg: error,
                });
            },
        });
    });

    $(".onlydocupload").change(function (event) {
        var MediaId = this.id;
        _uploadDocUrl = $(this).data("uploadurl");
        var inter;
        Lobibox.progress({
            title: "Please wait",
            label: "Uploading files...",
            progressTpl:
                '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () { },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            closed: function () { },
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName("csrf-token"));
        if (files.length > 0) {
            data.append("files", files[0]);
        } else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("info", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
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
        if (
            extension != "DOC" &&
            extension != "DOCX" &&
            extension != "TXT" &&
            extension != "PDF"
        ) {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("error", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
                        delayIndicator: true,
                        msg: "Invalid file format.",
                    });
                })();
            });
            $("#" + MediaId).val("");
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
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            beforeSend: function () { },
            success: function (data) {
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
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery("#logo-duplicate_" + MediaId).val("");
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, function (k, v) {
                    if (typeof v != "object") {
                        error += v + "<br>";
                    }
                });
                Lobibox.notify("error", {
                    position: "top right",
                    rounded: false,
                    delay: 2000,
                    delayIndicator: true,
                    msg: error,
                });
            },
        });
    });

    $("body").delegate(".onlyvideoupload", "change", function (event) {
        var MediaId = this.id;
        _uploadDocUrl = $(this).data("uploadurl");
        var inter;
        Lobibox.progress({
            title: "Please wait",
            label: "Uploading files...",
            progressTpl:
                '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () { },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            closed: function () { },
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName("csrf-token"));
        if (files.length > 0) {
            data.append("files", files[0]);
        } else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("info", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
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
        if (
            extension != "MP4" &&
            extension != "AVI" &&
            extension != "MPG" &&
            extension != "MKV" &&
            extension != "WEBM"
        ) {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("error", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
                        delayIndicator: true,
                        msg: "Invalid file format.",
                    });
                })();
            });
            $("#" + MediaId).val("");
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
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            beforeSend: function () { },
            success: function (data) {
                // console.log("success");
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                Lobibox.notify(data["type"], {
                    position: "top right",
                    msg: data["message"],
                });
                if (data["status_code"] == 200) {
                    $("#prev_video").html(
                        '<p class="mb5"><a download href="' +
                        data["full_path"] +
                        '" title="Download Video"><img src="' +
                        site_url +
                        '/public/img/download-video-1.png"/></a></p>'
                    );
                    $("#f_" + MediaId).val(data["filename"]);
                    $("#" + MediaId).val(data["filename"]);
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery("#logo-duplicate_" + MediaId).val("");
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, function (k, v) {
                    if (typeof v != "object") {
                        error += v + "<br>";
                    }
                });
                Lobibox.notify("error", {
                    position: "top right",
                    rounded: false,
                    delay: 2000,
                    delayIndicator: true,
                    msg: error,
                });
            },
        });
    });

    $("body").delegate(".imageanddocupload", "change", function (event) {
        var MediaId = this.id;
        _uploadDocUrl = $(this).data("uploadurl");
        _downloadUrl = $(this).data("downloadurl");
        var inter;
        Lobibox.progress({
            title: "Please wait",
            label: "Uploading files...",
            progressTpl:
                '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () { },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            closed: function () { },
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName("csrf-token"));
        if (files.length > 0) {
            data.append("files", files[0]);
        } else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("info", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
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
        if (
            extension != "DOC" &&
            extension != "DOCX" &&
            extension != "TXT" &&
            extension != "PDF" &&
            extension != "PNG" &&
            extension != "JPG" &&
            extension != "JPEG"
        ) {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("error", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
                        delayIndicator: true,
                        msg: "Invalid file format.",
                    });
                })();
            });
            $("#" + MediaId).val("");
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
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            beforeSend: function () { },
            success: function (data) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                Lobibox.notify(data["type"], {
                    position: "top right",
                    msg: data["message"],
                });
                if (data["status_code"] == 251) {
                    $("#ht_" + MediaId).html(
                        '<p class="mb5"><a download href="' +
                        _downloadUrl +
                        "/" +
                        data["filename"] +
                        '">' +
                        data["filename"] +
                        "</a></p>"
                    );
                    $("#f_" + MediaId).val(data["filename"]);
                    $("#f_" + MediaId)
                        .next()
                        .css({ display: "none" });
                }
                if (data["status_code"] == 252) {
                    $("#ht_" + MediaId).html(
                        '<p class="mb5"><a download href="' +
                        _downloadUrl +
                        "/" +
                        data["filename"] +
                        '">' +
                        data["filename"] +
                        "</a></p>"
                    );
                    $("#f_" + MediaId).val(data["filename"]);
                    $("#f_" + MediaId)
                        .next()
                        .css({ display: "none" });
                }
                if (data["status_code"] == 200) {
                    $("#f_" + MediaId).val(data["filename"]);
                    $("#" + MediaId).val(data["filename"]);
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery("#logo-duplicate_" + MediaId).val("");
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, function (k, v) {
                    if (typeof v != "object") {
                        error += v + "<br>";
                    }
                });
                Lobibox.notify("error", {
                    position: "top right",
                    rounded: false,
                    delay: 2000,
                    delayIndicator: true,
                    msg: error,
                });
            },
        });
    });

    $("body").delegate(".imageandpdfupload", "change", function (event) {
        var MediaId = this.id;
        _uploadDocUrl = $(this).data("uploadurl");
        _downloadUrl = $(this).data("downloadurl");
        var inter;
        Lobibox.progress({
            title: "Please wait",
            label: "Uploading files...",
            progressTpl:
                '<div class="progress " >\n\
            <div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div>\n\
            </div>',
            progressCompleted: function () { },
            onShow: function ($this) {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            closed: function () { },
        });
        event.preventDefault();
        var data = new FormData();
        var files = $("#" + MediaId).get(0).files;
        data.append("_token", getMetaContentByName("csrf-token"));
        if (files.length > 0) {
            data.append("files", files[0]);
        } else {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("info", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
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
        if (
            extension != "PDF" &&
            extension != "PNG" &&
            extension != "JPG" &&
            extension != "JPEG"
        ) {
            $(function () {
                (function () {
                    $(".btn-close").trigger("click");
                    $(".lobibox-close").click();
                    Lobibox.notify("error", {
                        position: "top right",
                        rounded: false,
                        delay: 2000,
                        delayIndicator: true,
                        msg: "Invalid file format.",
                    });
                })();
            });
            $("#" + MediaId).val("");
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
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
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
            beforeSend: function () { },
            success: function (data) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                Lobibox.notify(data["type"], {
                    position: "top right",
                    msg: data["message"],
                });
                if (data["status_code"] == 251) {
                    $("#ht_" + MediaId).html(
                        '<p class="mb5"><a download href="' +
                        _downloadUrl +
                        "/" +
                        data["filename"] +
                        '">' +
                        data["filename"] +
                        "</a></p>"
                    );
                    $("#f_" + MediaId).val(data["filename"]);
                    $("#f_" + MediaId)
                        .next()
                        .css({ display: "none" });
                }
                if (data["status_code"] == 252) {
                    if (extension == "PDF") {
                        $("#ht_" + MediaId).html(
                            '<p class="mb5"><a download href="' +
                            _downloadUrl +
                            "/" +
                            data["filename"] +
                            '">' +
                            data["filename"] +
                            "</a></p>"
                        );
                        $("#f_" + MediaId).val(data["filename"]);
                        $("#f_" + MediaId)
                            .next()
                            .css({ display: "none" });
                    } else {
                        $("#ht_" + MediaId).html(
                            '<p class="mb5"><a download href="' +
                            _downloadUrl +
                            "/" +
                            data["filename"] +
                            '">' +
                            data["filename"] +
                            "</a></p>"
                        );
                        $("#f_" + MediaId).val(data["filename"]);
                        $("#f_" + MediaId)
                            .next()
                            .css({ display: "none" });
                    }
                }
                if (data["status_code"] == 250) {
                    if (extension == "PDF") {
                        $("#ht_" + MediaId).html(
                            '<p class="mb5"><a download href="' +
                            _downloadUrl +
                            "/" +
                            data["filename"] +
                            '">' +
                            data["filename"] +
                            "</a></p>"
                        );
                        $("#f_" + MediaId).val(data["filename"]);
                        $("#f_" + MediaId)
                            .next()
                            .css({ display: "none" });
                    } else {
                        $("#f_" + MediaId).val(data["filename"]);
                        $("#f_" + MediaId)
                            .next()
                            .css({ display: "none" });
                    }
                }
                if (data["status_code"] == 200) {
                    $("#f_" + MediaId).val(data["filename"]);
                    $("#" + MediaId).val(data["filename"]);
                }
            },
            error: function (e) {
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
                jQuery("#logo-duplicate_" + MediaId).val("");
                var Arry = e.responseText;
                var error = "";
                JSON.parse(Arry, function (k, v) {
                    if (typeof v != "object") {
                        error += v + "<br>";
                    }
                });
                Lobibox.notify("error", {
                    position: "top right",
                    rounded: false,
                    delay: 2000,
                    delayIndicator: true,
                    msg: error,
                });
            },
        });
    });
});
var DirectFormSubmitWithAjax = (function () {
    "use strict";
    return {
        init: function () {
            $("body").on("click", ".directSubmit", function () {
                var submitFormId = "F_" + this.id;
                var action = $("#" + submitFormId).attr("action");
                var step_count = $("#stepCount").text();
                var addStep =
                    step_count != 4 ? parseInt(step_count) + 1 : step_count;
                if (typeof $(this).data("loader") != "undefined") {
                    var _loaderMsg = $(this).data("loader");
                }
                $("#" + submitFormId).validate({
                    ignore: [],
                    rules: {
                        password: {
                            minlength: 8,
                            required: true,
                            //special_char_password: true,
                            /*required: function() {
                                if ($("#old_password").val() == '')
                                    return false;
                                else
                                    return true;
                            }*/
                        },
                        mobile_otp: {
                            required: true,
                            minlength: 4,
                        },
                        phone: {
                            required: true,
                            minlength: 10,
                        },
                        name: {
                            required: true,
                        },
                        npass: {
                            minlength: 8,
                        },
                        password_confirmation: {
                            equalTo: "#newpassword",
                        },
                        password_confirmation_institution: {
                            equalTo: "#password_institution",
                        },
                        "g-recaptcha-response": {
                            required: function () {
                                if (grecaptcha.getResponse() == "") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                        space_rules: {
                            required: function (textarea) {
                                CKEDITOR.instances[textarea.id].updateElement();
                                var editorcontent = textarea.value.replace(
                                    /<[^>]*>/gi,
                                    ""
                                );
                                return editorcontent.length === 0;
                            },
                        },
                        "visit": {
                            required: function () {
                                if (visit == "") {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                        },
                    },
                    messages: {
                        password: {
                            minlength: must_minimum_digit_pwd,
                            special_char_password:
                                "Password should have one upper case, one lower case, one special characters and one digit",
                        },
                        npass: {
                            minlength: must_minimum_digit_pwd,
                        },
                        password_confirmation: {
                            equalTo: _enter_same_as_passowed,
                        },
                        password_confirmation_institution: {
                            equalTo: _enter_same_as_passowed,
                        },
                        mobile_otp: {
                            required: "Please enter OTP",
                            minlength: "Please enter at least 4 digits.",
                        },

                        phone: {
                            required: "Please enter mobile number",
                            minlength: "Invalid mobile number.",
                        },
                        name: {
                            required: "Please enter owner name",
                        },
                        "g-recaptcha-response": verify_you_are_human,
                    },
                    errorPlacement: function (e, r) {
                        e.appendTo(r.closest(".ermsg"));
                    },
                    submitHandler: function (form) {
                        $(".directSubmit").prop("disabled", true);
                        $(".lobibox-close").trigger("click");
                        $(form).ajaxSubmit({
                            url: action,
                            type: "POST",
                            cache: false,
                            dataType: "json",
                            beforeSend: function () {
                                $("#loader_msg").html(_loaderMsg);
                                $("#loader").show();
                            },
                            complete: function () {
                                $("#loader").hide();
                            },
                            success: function (data) {
                                $(".directSubmit").prop("disabled", false);
                                Lobibox.notify(data["type"], {
                                    position: "top right",
                                    msg: data["message"],
                                });
                                if (data["status_code"] == 205) {
                                    if ($(".nextButton_" + step_count + "").attr('data-title') != '') {
                                        $('.content_steps').text($(".nextButton_" + step_count + "").attr('data-title'));
                                    }
                                    $("#stepCount").text(addStep);
                                    if (data["url"]) {
                                        if (data["_blank"]) {
                                            $("#loader").hide();
                                            $(".close").trigger("click");
                                            $(".directSubmit").prop(
                                                "disabled",
                                                false
                                            );
                                            window.open(data["url"], "_blank");
                                        } else {
                                            location.href = data["url"];
                                        }
                                    } else {
                                        if (data["html_data"]) {
                                            $(
                                                "#reserve_block" + data["step"]
                                            ).html("");
                                            $(
                                                "#reserve_block" + data["step"]
                                            ).html(
                                                JSON.parse(data["html_data"])
                                            );
                                        }
                                        $("#loader").hide();
                                        if (data["image_panel"]) {
                                            $(".RoomImagesDiv").hide();
                                            $(".Roomfiles").prop(
                                                "required",
                                                false
                                            );
                                            $.each(
                                                JSON.parse(
                                                    data["active_rooms"]
                                                ),
                                                function (key, val) {
                                                    $(
                                                        "#" +
                                                        val +
                                                        "RoomImagesDiv"
                                                    ).show();
                                                    $(
                                                        "#f_" +
                                                        val +
                                                        "_room_images"
                                                    ).prop("required", true);
                                                }
                                            );
                                        }
                                        if (data["property_type_name"]) {
                                            $("#propertyTypeName").html(
                                                data["property_type_name"]
                                            );
                                        }
                                        if (data["scroll"]) {
                                            document.body.scrollTop =
                                                data["scroll"];
                                            document.documentElement.scrollTop =
                                                data["scroll"];
                                        }
                                        $("#" + data["current_step"]).hide();
                                        $("." + data["next_step"]).show();
                                        $("#" + data["next_step"]).show();
                                        if (data["err_field"]) {
                                            if (data["err_field"]) {
                                                var el = $(document).find(
                                                    '[name="' +
                                                    data["err_field"] +
                                                    '"]'
                                                );
                                                el.after(
                                                    $(
                                                        '<label class="error">' +
                                                        data["message"] +
                                                        "</label>"
                                                    )
                                                );
                                            }
                                            Lobibox.notify(data["type"], {
                                                position: "top right",
                                                msg: data["message"],
                                            });
                                        }
                                    }
                                } else if (data["r_type"] == "space_availity") {
                                    $("#loader").hide();
                                    if (data["html"]) {
                                        $("#reserve_block").html("");
                                        $("#reserve_block").html(
                                            JSON.parse(data["body"])
                                        );
                                    }
                                } else {
                                    $("#loader").hide();
                                    if (data["reset"]) {
                                        if (data["gcp-reset"]) {
                                            grecaptcha.reset();
                                        }
                                        document
                                            .getElementById(submitFormId)
                                            .reset();
                                        $(".hidden-field").val("");
                                    }

                                    if (data["modelClose"]) {
                                        $("#" + data["modelClose"]).modal(
                                            "hide"
                                        );
                                    }
                                    if (data["upload_agreement"] == true) {
                                        serach();
                                    }

                                    if (data["status_code"] == 200) {
                                        if (data["html"]) {
                                            $("#result").html("");
                                            $("#result").html(
                                                JSON.parse(data["body"])
                                            );
                                        }
                                        if (data["otp_vendor"]) {
                                            $("#loginVendor").hide();
                                            $(".vendorPhone").prop(
                                                "readonly",
                                                true
                                            );
                                            $(".vendor_OTP_form").show();
                                        }

                                        if (data["otp_mobile_update"]) {
                                            $(".mobile_OTP_box").show();
                                            $(".mobile_OTP_input").prop(
                                                "disabled",
                                                false
                                            );
                                            $(".mobile_OTP_input").prop(
                                                "required",
                                                true
                                            );
                                        }
                                        if (data["otp_Box_hide"]) {
                                            $(".mobile_OTP_box").hide();
                                            $(".mobile_OTP_input").prop(
                                                "required",
                                                false
                                            );
                                            $(".mobile_OTP_input").val("");
                                            $(".mobile_OTP_input").prop(
                                                "disabled",
                                                true
                                            );
                                        }
                                    }
                                    if (data["status_code"] == 207) {
                                        if (data["html"]) {
                                            $("#verifypin_otp").modal({
                                                backdrop: "static",
                                                keyboard: false,
                                            });
                                            $("#verifypin_otp_content")
                                                .empty()
                                                .append(
                                                    JSON.parse(data["html"])
                                                );
                                        }
                                    }
                                    if(data['walletAmount']){ 
                                        $(".updateWalletAmount").html(data['walletAmount']);
                                        $('#paymentModal').modal('hide');
                                    }
                                    if (data["url"]) {
                                        location.href = data["url"];
                                    }
                                    if (data["redirect-url"]) {
                                        location.href = data["redirect-url"];
                                    }
                                    if (data["tab-active"]) {
                                        // return false;
                                        $("html, body").animate(
                                            { scrollTop: 0 },
                                            "slow"
                                        );
                                        $(".lobibox-notify-wrapper").hide();
                                        $("#" + data["tab-active"]).trigger(
                                            "click"
                                        );
                                    }
                                }
                            },
                            error: function (e) {
                                $("#loader").hide();
                                $(".directSubmit").prop("disabled", false);
                                if (e.status === 401) {
                                    var result = JSON.parse(e.responseText);
                                    if (result["redirect"]) {
                                        $("#redirect").val(result["redirect"]);
                                    }
                                    Lobibox.notify("error", {
                                        position: "top right",
                                        msg: "Please login to perform this action",
                                    });
                                    //$("#login").modal("show");
                                } else {
                                    var Arry = e.responseText;
                                    console.log(Arry);
                                    var error = "";
                                    JSON.parse(Arry, function (k, v) {
                                        if (typeof v != "object") {
                                            if (
                                                v !=
                                                "The given data was invalid."
                                            ) {
                                                error += v + "<br>";
                                            }
                                        }
                                    });

                                    Lobibox.notify("error", {
                                        rounded: false,
                                        delay: 3000,
                                        delayIndicator: true,
                                        position: "top right",
                                        msg: error,
                                    });
                                }
                            },
                        });
                    },
                });
                $(".close").on("click", function () {
                    if ($(".cover_image_files").hasClass("error")) {
                        $("label .error").hide();
                    }
                });
            });
        },
    };
})();

$("body").on("click", ".clickBelowContinue", function () {
    var mainId = $(this).data("stepid");
    $("#" + mainId).trigger("click");
    return false;
});
$("body").on("click", ".get-details-in-model-globally", function () {
    var __Action = $(this).data("actionurl");
    if (typeof $(this).data("loader") != "undefined") {
        var _loaderMsg = $(this).data("loader");
    }
    $.ajax({
        type: "get",
        url: __Action,
        data: "",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        beforeSend: function () {
            $("#loader_msg").html(_loaderMsg);
            $("#loader").show();
        },
        success: function (result) {
            $("#loader").hide();
            $("#globalModelLarge").modal({
                backdrop: "static",
                keyboard: false,
            });
            $("#modelContentLarge").empty().append(JSON.parse(result["body"]));
            $(".group1").colorbox({ rel: "group1" });
        },
        error: function (e) {
            $("#loader").hide();
            $(".btn-close").trigger("click");
            $(".lobibox-close").click();
            var Arry = e.responseText;
            var error = "";
            JSON.parse(Arry, (k, v) => {
                if (typeof v != "object") {
                    error += v + "<br>";
                }
            });
            Lobibox.notify("error", {
                position: "top right",
                rounded: false,
                delay: 2000,
                delayIndicator: true,
                msg: error,
            });
        },
    });
});
$("body").on("click", ".resendVerificationPhoneCode", function (e) {
    e.preventDefault();
    var __Action = $(this).data("actionurl");
    if (typeof $(this).data("loader") != "undefined") {
        var _loaderMsg = $(this).data("loader");
    }
    $.ajax({
        type: "get",
        url: __Action,
        data: "",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        dataType: "json",
        beforeSend: function () {
            $(".resendVerificationPhoneCode").prop("disabled", true);
            $("#loader_msg").html(_loaderMsg);
            $("#loader").show();
        },
        success: function (data) {
            $("#loader").hide();
            $(".lobibox-close").click();
            $(".resendVerificationPhoneCode").prop("disabled", false);
            Lobibox.notify(data["type"], {
                position: "top right",
                msg: data["message"],
            });
            if (data["status_code"] == 200) {
                if (data["url"]) {
                    location.href = data["url"];
                }
            }
        },
        error: function (e) {
            $("#loader").hide();
            $(".btn-close").trigger("click");
            $(".lobibox-close").click();
            var Arry = e.responseText;
            var error = "";
            JSON.parse(Arry, (k, v) => {
                if (typeof v != "object") {
                    error += v + "<br>";
                }
            });
            Lobibox.notify("error", {
                position: "top right",
                rounded: false,
                delay: 2000,
                delayIndicator: true,
                msg: error,
            });
        },
    });
});
$(".inputs").keyup(function () {
    if (this.value.length == this.maxLength) {
        var $next = $(this).next(".inputs");
        if ($next.length) $(this).next(".inputs").focus();
        else $(this).blur();
    }
});

//Use on loan questionaries page
$(".get-questionarre").click(function () {
    var inputValue = $(this).attr("value");
    var __divid = $(this).data("div");
    $("#div" + __divid).hide();
    $("#ans_desc_" + __divid).prop("required", false);
    if (inputValue.toUpperCase() != "NO") {
        $("#ans_desc_" + __divid).prop("required", true);
        $("#div" + __divid).show();
    }
});

$(".get-questionarre:checked").each(function () {
    var inputValue = $(this).attr("value");
    var __divid = $(this).data("div");
    $("#div" + __divid).hide();
    $("#ans_desc_" + __divid).prop("required", false);
    if (inputValue.toUpperCase() != "NO") {
        $("#ans_desc_" + __divid).prop("required", true);
        $("#div" + __divid).show();
    }
});
// Used for dropdown-with-text case
$(".dropwithtext").change(function () {
    var inputValue = this.value;
    var __divid = $(this).data("div");
    if (inputValue == "") {
        $("#div" + __divid).fadeOut("slow");
        $("#ans_desc_" + __divid).prop("required", false);
    } else {
        $("#ans_desc_" + __divid).prop("required", true);
        $("#div" + __divid).fadeIn(1500);
    }
});
$(".dropwithtext").each(function () {
    var inputValue = this.value;
    var __divid = $(this).data("div");
    if (inputValue == "") {
        $("#div" + __divid).fadeOut("slow");
        $("#ans_desc_" + __divid).prop("required", false);
    } else {
        $("#ans_desc_" + __divid).prop("required", true);
        $("#div" + __divid).fadeIn(1500);
    }
});
//End Used for dropdown-with-text case

//Make fav-unfav project
$(".isfav").click(function () {
    var __Action = $(this).data("actionurl");
    var removeId = $(this).data("removeid");
    $("#" + removeId)
        .html("")
        .hide();
    $.ajax({
        type: "get",
        url: __Action,
        data: "",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        dataType: "json",
        beforeSend: function () { },
        success: function (data) {
            $(".ajaxloader").hide();
            Lobibox.notify(data["type"], {
                position: "top right",
                msg: data["message"],
            });
        },
        error: function (e) {
            $(".ajaxloader").hide();
            $(".btn-close").trigger("click");
            $(".lobibox-close").click();
            var Arry = e.responseText;
            var error = "";
            JSON.parse(Arry, (k, v) => {
                if (typeof v != "object") {
                    error += v + "<br>";
                }
            });
        },
    });
});

// fa-eye fa-eye-slash
$(".toggle-password").on("click", function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    let input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

$(".icloseiicon").on("click", function () {
    $(".advertise_block").fadeOut("slow");
});

$("body").delegate(".borrow-box-toggle-box", "click", function () {
    var ToggleId = $(this).data("toggleid");
    $(this)
        .closest("." + ToggleId)
        .toggleClass("open");
});

if (document.getElementById("searchtag")) { 
    function initialize() {
        var input = document.getElementById("searchTextField");
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(
            autocomplete,
            "place_changed",
            function () {
                var place = autocomplete.getPlace();
                if (document.getElementById("city")) {
                    document.getElementById("city").value = place.name;
                }
                if (document.getElementById("cityLat")) {
                    document.getElementById("cityLat").value =
                        place.geometry.location.lat();
                }
                if (document.getElementById("cityLng")) {
                    document.getElementById("cityLng").value =
                        place.geometry.location.lng();
                }
            }
        );
    }
    google.maps.event.addDomListener(window, "load", initialize);

    $("#searchTextField").keyup(function () {
        if (document.getElementById("city")) {
            document.getElementById("city").value = "";
        }
        if (document.getElementById("cityLat")) {
            document.getElementById("cityLat").value = "";
        }
        if (document.getElementById("cityLng")) {
            document.getElementById("cityLng").value = "";
        }
    });
    $("#searchTextField").blur(function () {
        if (
            !document.getElementById("cityLat").value &&
            !document.getElementById("cityLng").value
        ) {
            document.getElementById("searchTextField").value = "";
        }
    });
}

if (document.getElementById("paymentform")) {
    function initialize() {
        var input = document.getElementById("searchTextField");
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(
            autocomplete,
            "place_changed",
            function () {
                var place = autocomplete.getPlace();
                //document.getElementById('city').value = place.name;
                document.getElementById("cityLat").value =
                    place.geometry.location.lat();
                document.getElementById("cityLng").value =
                    place.geometry.location.lng();
                for (var i = 0; i < place.address_components.length; i++) {
                    for (
                        var j = 0;
                        j < place.address_components[i].types.length;
                        j++
                    ) {
                        if (
                            place.address_components[i].types[j] ==
                            "postal_code"
                        ) {
                            document.getElementById("zip_code").value =
                                place.address_components[i].long_name;
                        } else {
                            document.getElementById("zip_code").value = "";
                        }
                    }
                    var addressType = place.address_components[i].types[0];
                    // for the country, get the country code (the "short name") also
                    if (addressType == "country") {
                        document.getElementById("country").value =
                            place.address_components[i].short_name;
                    }

                    if (addressType == "administrative_area_level_1") {
                        document.getElementById("state").value =
                            place.address_components[i].short_name;
                    }
                    if (addressType == "locality") {
                        document.getElementById("city").value =
                            place.address_components[i].short_name;
                    }
                }
            }
        );
    }
    google.maps.event.addDomListener(window, "load", initialize);
    $("#searchTextField").keyup(function () {
        document.getElementById("city").value = "";
        document.getElementById("cityLat").value = "";
        document.getElementById("cityLng").value = "";
    });
    $("#searchTextField").blur(function () {
        if (
            !document.getElementById("cityLat").value &&
            !document.getElementById("cityLng").value
        ) {
            document.getElementById("searchTextField").value = "";
        }
    });
}

$(".spacelink").click(function () {
    $(".spacemenutrigger").trigger("click");
});

$(".myratingview").starRating({
    totalStars: 5,
    starSize: 20,
    activeColor: "#FF6E41",
    useGradient: false,
    readOnly: true,
});

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip({ placement: "top" });
});

$("body").delegate(".enablechat", "click", function () {
    var bookingId = $(this).data("bookingid");
    $("#chat_model").modal("show");
    $("#chat_booking_id").val(bookingId);
});

$("body").delegate(".disputemodel", "click", function () {
    var bookingId = $(this).data("bookingslug");
    $("#dispute_slug").val(bookingId);
});

var ReplaceUrl = (function () {
    "use strict";
    return {
        init: function () {
            window.history.pushState(
                { url: "" + REQUEST_URL + "" },
                "",
                REQUEST_URL
            );
        },
    };
})();

$(document).on("click", ".store-visit", function () {
    var property_id = $(this).attr("data-id");
    var url = $(this).attr("data-url");
    $.ajax({
        type: "POST",
        url: url,
        data: { property_id: property_id },
        dataType: "json",
        beforeSend: function () {
            $("#loader_msg").html(_loaderMsg);
            $("#loader").show();
        },
        success: (data) => {
            $("#loader").hide();
            Lobibox.notify(data["type"], {
                position: "top right",
                msg: data["message"],
            });
            if (data["redirect-url"]) {
                location.href = data["redirect-url"];
            }
        },
        error: function (data) {
            if (data.responseJSON.message == "Unauthenticated.") {
                Lobibox.notify("error", {
                    position: "top right",
                    msg: "Login as customer to schedule visit.",
                });
                window.location.href = site_url + '/customer/login';
            }
            $("#loader").hide();
        },
    });
});
$(".all_filters").addClass("showActive");
$(".showActive").css({
    "border-color": "#53d687",
    background: "rgba(83, 214, 135, 0.1)",
});

$(document).on("click", ".myvisitRow", function () {
    window.open($(this).data("href"), "_blank");
});


// toggle eye change password
$('.toggle-eye-password').click(function () {
    if ($(this).hasClass('fa-eye')) {
        $(this).removeClass('fa-eye');
        $(this).addClass('fa-eye-slash');
        $(this).next('input').attr('type', 'text');
    } else {
        $(this).removeClass('fa-eye-slash');
        $(this).addClass('fa-eye');
        $(this).next('input').attr('type', 'password');
    }
});

$(window).on("load", function () {
    $("#agentcodeModal").modal("show");
});
