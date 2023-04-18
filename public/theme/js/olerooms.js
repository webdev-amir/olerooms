jQuery(function ($) {
    $.fn.bravoAutocomplete = function (options) {
        return this.each(function () {
            var $this = $(this);
            var main = $(this).closest(".smart-search");
            var textLoading = options.textLoading;
            main.append(
                '<div class="bravo-autocomplete on-message"><div class="list-item"></div><div class="message">' +
                textLoading +
                "</div></div>"
            );
            $(document).on("click.Bst", function (event) {
                if (
                    main.has(event.target).length === 0 &&
                    !main.is(event.target)
                ) {
                    main.find(".bravo-autocomplete").removeClass("show");
                } else {
                    if (options.dataDefault.length > 0) {
                        if (main.find(".bravo-autocomplete").hasClass("show")) {
                            main.find(".bravo-autocomplete").removeClass(
                                "show"
                            );
                        } else {
                            main.find(".bravo-autocomplete").addClass("show");
                        }
                    }
                }
            });
            if (options.dataDefault.length > 0) {
                var items = "";
                for (var index in options.dataDefault) {
                    var item = options.dataDefault[index];
                    items +=
                        '<div class="item" data-id="' +
                        item.id +
                        '" data-text="' +
                        item.title +
                        '"> <i class="' +
                        options.iconItem +
                        '"></i> ' +
                        item.title +
                        " </div>";
                }
                main.find(".bravo-autocomplete .list-item").html(items);
                main.find(".bravo-autocomplete").removeClass("on-message");
            }
            var requestTimeLimit;
            if (typeof options.url != "undefined" && options.url) {
                $this.keyup(function () {
                    main.find(".bravo-autocomplete").addClass("on-message");
                    main.find(".bravo-autocomplete .message").html(textLoading);
                    main.find(".child_id").val("");
                    var query = $(this).val();
                    // var property_type_id = $(this).data('property_type_id');
                    var property_type_id = $("input[name=property_type]").val();
                    clearTimeout(requestTimeLimit);
                    if (query.length === 0) {
                        if (options.dataDefault.length > 0) {
                            var items = "";
                            for (var index in options.dataDefault) {
                                var item = options.dataDefault[index];
                                items +=
                                    '<div class="item" data-id="' +
                                    item.id +
                                    '" data-text="' +
                                    item.title +
                                    '"> <i class="' +
                                    options.iconItem +
                                    '"></i> ' +
                                    item.title +
                                    " </div>";
                            }
                            main.find(".bravo-autocomplete .list-item").html(
                                items
                            );
                            main.find(".bravo-autocomplete").removeClass(
                                "on-message"
                            );
                        } else {
                            main.find(".bravo-autocomplete").removeClass(
                                "show"
                            );
                        }
                        return;
                    }
                    requestTimeLimit = setTimeout(function () {
                        $.ajax({
                            url: options.url,
                            data: {
                                search: query,
                                property_type_id: property_type_id,
                            },
                            dataType: "json",
                            type: "get",
                            beforeSend: function () { },
                            success: function (res) {
                                if (res.original.status === 1) {
                                    var items = "";
                                    for (var ix in res.original.data) {
                                        var item = res.original.data[ix];
                                        items +=
                                            '<div class="item" data-id="' +
                                            item.id +
                                            '" data-text="' +
                                            item.title +
                                            '"> <i class="' +
                                            options.iconItem +
                                            '"></i> ' +
                                            get_highlight(item.title, query) +
                                            " </div>";
                                    }
                                    main.find(
                                        ".bravo-autocomplete .list-item"
                                    ).html(items);
                                    main.find(
                                        ".bravo-autocomplete"
                                    ).removeClass("on-message");
                                }

                                if (typeof res.message === undefined) {
                                    main.find(".bravo-autocomplete").addClass(
                                        "on-message"
                                    );
                                } else {
                                    main.find(
                                        ".bravo-autocomplete .message"
                                    ).html(res.message);
                                }
                            },
                        });
                    }, 700);

                    function get_highlight(text, val) {
                        return text.replace(
                            new RegExp(val + "(?!([^<]+)?>)", "gi"),
                            '<span class="h-line">$&</span>'
                        );
                    }
                    main.find(".bravo-autocomplete").addClass("show");
                });
            }
            main.find(".bravo-autocomplete").on("click", ".item", function () {
                var id = $(this).attr("data-id"),
                    text = $(this).attr("data-text");

                if (id.length > 0 && text.length > 0) {
                    text = text.replace(/-/g, "");
                    text = trimFunc(text, " ");
                    text = trimFunc(text, "-");
                    main.find(".parent_text").val(text).trigger("change");
                    var childval = main.find(".child_id").val();
                    main.find(".child_id").val(id).trigger("change");

                    $(".flat-options").hide();
                    if (
                        options.flatBhkType == "reset" &&
                        childval != "" &&
                        childval != main.find(".child_id").val()
                    ) {
                        var adultsHtml = $(
                            ".flat_adult_render .adults .multi"
                        ).data("html");
                        var childrenHtml = $(
                            ".flat_adult_render .children .multi"
                        ).data("html");
                        $(".flat_adult_render .adults .multi").html(
                            adultsHtml.replace(":count", 1)
                        );
                        $(".flat_adult_render .children .multi").html(
                            childrenHtml.replace(":count", 0)
                        );
                        $("#flat_adults").val(1);
                        $("#flat_children").val(0);
                    }
                } else {
                    console.log("Cannot select!");
                }
                setTimeout(function () {
                    main.find(".bravo-autocomplete").removeClass("show");
                }, 100);
            });
            var trimFunc = function (s, c) {
                if (c === "]") c = "\\]";
                if (c === "\\") c = "\\\\";
                return s.replace(
                    new RegExp("^[" + c + "]+|[" + c + "]+$", "g"),
                    ""
                );
            };
        });
    };
});
jQuery(function ($) {
    function parseErrorMessage(e) {
        var html = "";
        if (e.responseJSON) {
            if (e.responseJSON.errors) {
                return Object.values(e.responseJSON.errors).join("<br>");
            }
        }
        return html;
    }
    $(".g-map-place").each(function () {
        var map = $(this).find(".map").attr("id");
        var searchInput = $(this).find("input[name=map_place]");
        var latInput = $(this).find('input[name="map_lat"]');
        var lgnInput = $(this).find('input[name="map_lgn"]');
        new BravoMapEngine(map, {
            fitBounds: true,
            center: [51.505, -0.09],
            ready: function (engineMap) {
                engineMap.searchBox(searchInput, function (dataLatLng) {
                    latInput.attr("value", dataLatLng[0]);
                    lgnInput.attr("value", dataLatLng[1]);
                });
            },
        });
    });
    $(".bravo_fullHeight").each(function () {
        var height = $(document).height();
        if ($(document).find(".bravo-admin-bar").length > 0) {
            height = height - $(".bravo-admin-bar").height();
        }
        $(this).css("min-height", height);
    });
    $(".date-picker").each(function () {
        var options = {
            singleDatePicker: true,
            opens: bookingCore.rtl ? "right" : "left",
            locale: {
                format: bookingCore.date_format,
                direction: bookingCore.rtl ? "rtl" : "ltr",
                firstDay: daterangepickerLocale.first_day_of_week,
            },
        };
        if (typeof daterangepickerLocale == "object") {
            options.locale = _.merge(daterangepickerLocale, options.locale);
        }
        $(this).daterangepicker(options);
    });
    $(".date-picker-dob").each(function () {
        var nowDate = new Date();
        nowDate.setDate(nowDate.getDate() - 1);
        var today = new Date(
            nowDate.getFullYear(),
            nowDate.getMonth(),
            nowDate.getDate(),
            0,
            0,
            0,
            0
        );
        var options = {
            singleDatePicker: true,
            maxDate: today,
            opens: bookingCore.rtl ? "left" : "right",
            locale: {
                format: bookingCore.date_format,
                direction: bookingCore.rtl ? "rtl" : "ltr",
                firstDay: daterangepickerLocale.first_day_of_week,
            },
        };
        if (typeof daterangepickerLocale == "object") {
            options.locale = _.merge(daterangepickerLocale, options.locale);
        }
        $(this).daterangepicker(options);
        $(this).val("");
    });

    $(".date-picker-dob-update").each(function () {
        var nowDate = new Date();
        nowDate.setDate(nowDate.getDate() - 1);
        var today = new Date(
            nowDate.getFullYear(),
            nowDate.getMonth(),
            nowDate.getDate(),
            0,
            0,
            0,
            0
        );
        var options = {
            singleDatePicker: true,
            maxDate: today,
            showDropdowns: true,
            minYear: 1901,
            autoUpdateInput: false,
            opens: bookingCore.rtl ? "left" : "right",
            locale: {
                format: bookingCore.date_format,
                direction: bookingCore.rtl ? "rtl" : "ltr",
                firstDay: daterangepickerLocale.first_day_of_week,
            },
        };
        if (typeof daterangepickerLocale == "object") {
            options.locale = _.merge(daterangepickerLocale, options.locale);
        }
        $(this).daterangepicker(options);
    });
    $(".review-form .review-items .rates .fa").each(function () {
        var list = $(this).parent(),
            listItems = list.children(),
            itemIndex = $(this).index(),
            parentItem = list.parent();
        $(this).hover(
            function () {
                for (var i = 0; i < listItems.length; i++) {
                    if (i <= itemIndex) {
                        $(listItems[i]).addClass("hovered");
                    } else {
                        break;
                    }
                }
                $(this).click(function () {
                    for (var i = 0; i < listItems.length; i++) {
                        if (i <= itemIndex) {
                            $(listItems[i]).addClass("selected");
                        } else {
                            $(listItems[i]).removeClass("selected");
                        }
                    }
                    parentItem.children(".review_stats").val(itemIndex + 1);
                });
            },
            function () {
                listItems.removeClass("hovered");
            }
        );
    });
    $(".bravo-form-login [type=submit]").click(function (e) {
        e.preventDefault();
        let form = $(this).closest(".bravo-form-login");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": form
                    .find('meta[name="csrf-token"]')
                    .attr("content"),
            },
        });
        $.ajax({
            url: bookingCore.routes.login,
            data: {
                email: form.find("input[name=email]").val(),
                password: form.find("input[name=password]").val(),
                remember: form.find("input[name=remember]").is(":checked")
                    ? 1
                    : "",
                "g-recaptcha-response": form
                    .find("[name=g-recaptcha-response]")
                    .val(),
                redirect: form.find("input[name=redirect]").val(),
            },
            type: "POST",
            beforeSend: function () {
                form.find(".error").hide();
                form.find(".icon-loading").css("display", "inline-block");
                $("#loader_msg").html("Please wait, Logging to your account");
                $("#loader").show();
            },
            complete: function () {
                $("#loader").hide();
            },
            success: function (data) {
                form.find(".icon-loading").hide();
                if (data.error === true) {
                    if (data.messages !== undefined) {
                        for (var item in data.messages) {
                            var msg = data.messages[item];
                            form.find(".error-" + item)
                                .show()
                                .text(msg[0]);
                        }
                    }
                    if (data.messages.message_error !== undefined) {
                        form.find(".message-error")
                            .show()
                            .html(
                                '<div class="alert alert-danger">' +
                                data.messages.message_error[0] +
                                "</div>"
                            );
                    }
                }
                if (typeof data.redirect !== "undefined" && data.redirect) {
                    window.location.href = data.redirect;
                }
            },
        });
    });
    $(".bravo-form-login-mobile [type=submit]").click(function (e) {
        e.preventDefault();
        let form = $(this).closest(".bravo-form-login-mobile");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": form
                    .find('meta[name="csrf-token"]')
                    .attr("content"),
            },
        });
        $.ajax({
            url: bookingCore.routes.login_mobile,
            data: {
                phone: form.find("input[name=phone]").val(),
                redirect: form.find("input[name=redirect]").val(),
            },
            type: "POST",
            beforeSend: function () {
                form.find(".error").hide();
                form.find(".icon-loading").css("display", "inline-block");
                $("#loader_msg").html("Please wait, Logging to your account");
                $("#loader").show();
            },
            complete: function () {
                $("#loader").hide();
            },
            success: function (data) {
                form.find(".icon-loading").hide();
                if (data.error === true) {
                    if (data.messages !== undefined) {
                        for (var item in data.messages) {
                            var msg = data.messages[item];
                            form.find(".error-" + item)
                                .show()
                                .text(msg[0]);
                        }
                    }
                    if (data.messages.message_error !== undefined) {
                        form.find(".message-error")
                            .show()
                            .html(
                                '<div class="alert alert-danger">' +
                                data.messages.message_error[0] +
                                "</div>"
                            );
                    }
                }
                if (typeof data.redirect !== "undefined" && data.redirect) {
                    window.location.href = data.redirect;
                }
            },
        });
    });
    $("#register").on("show.bs.modal", function (event) {
        $("#login").modal("hide");
    });
    $("#login").on("show.bs.modal", function (event) {
        $("#register").modal("hide");
    });
    $(".bravo-more-menu").click(function () {
        $(this).trigger("bravo-trigger-menu-mobile");
    });
    $(".bravo-menu-mobile .b-close").click(function () {
        $(".bravo-more-menu").trigger("bravo-trigger-menu-mobile");
    });
    $(document).on("click", ".bravo-effect-bg", function () {
        $(".bravo-more-menu").trigger("bravo-trigger-menu-mobile");
    });
    $(document).on(
        "bravo-trigger-menu-mobile",
        ".bravo-more-menu",
        function () {
            $(this).toggleClass("active");
            if ($(this).hasClass("active")) {
                $(".bravo-menu-mobile").addClass("active");
                $("body")
                    .css("overflow", "hidden")
                    .append("<div class='bravo-effect-bg'></div>");
            } else {
                $(".bravo-menu-mobile").removeClass("active");
                $("body")
                    .css("overflow", "initial")
                    .find(".bravo-effect-bg")
                    .remove();
            }
        }
    );
    $(".bravo-menu-mobile .g-menu ul li .fa").click(function (e) {
        e.preventDefault();
        $(this).closest("li").toggleClass("active");
    });
    $(".bravo-menu-mobile").each(function () {
        var h_profile = $(this).find(".user-profile").height();
        var h1_main = $(window).height();
        $(this)
            .find(".g-menu")
            .css("max-height", h1_main - h_profile - 15);
    });
    $(".bravo-more-menu-user").click(function () {
        $(".bravo_user_profile > .container-fluid > .row > .col-md-3").addClass(
            "active"
        );
        $("body")
            .css("overflow", "hidden")
            .append("<div class='bravo-effect-user-bg'></div>");
    });
    $(document).on(
        "click",
        ".bravo-effect-user-bg,.bravo-close-menu-user",
        function () {
            $(
                ".bravo_user_profile > .container-fluid > .row > .col-md-3"
            ).removeClass("active");
            $("body")
                .css("overflow", "initial")
                .find(".bravo-effect-user-bg")
                .remove();
        }
    );
    $('[data-toggle="tooltip"]').tooltip();
    $(".dropdown-toggle").dropdown();
    $(".select-guests-dropdown .dropdown-item-row").click(function (e) {
        e.stopPropagation();
    });
    $(".select-seat-type-dropdown .btn-minus").on("click", function (e) {
        e.stopPropagation();
        var parent = $(this).closest(".form-select-seat-type");
        var inputAttr = $(this).data("input-attr");
        if (typeof inputAttr == "undefined") {
            inputAttr = "name";
        }
        var input = parent.find(
            ".select-seat-type-dropdown [" +
            inputAttr +
            "=" +
            $(this).data("input") +
            "]"
        );
        var min = parseInt(input.attr("min"));
        var old = parseInt(input.val());
        if (old <= min) {
            return;
        }
        input.val(old - 1);
        updateCustomSelectDropdown(input);
    });
    $(".select-seat-type-dropdown .btn-add").on("click", function (e) {
        e.stopPropagation();
        var parent = $(this).closest(".form-select-seat-type");
        var inputAttr = $(this).data("input-attr");
        if (typeof inputAttr == "undefined") {
            inputAttr = "name";
        }
        var input = parent.find(
            ".select-seat-type-dropdown [" +
            inputAttr +
            "=" +
            $(this).data("input") +
            "]"
        );
        var max = parseInt(input.attr("max"));
        var old = parseInt(input.val());
        if (old >= max) {
            return;
        }
        input.val(old + 1);
        updateCustomSelectDropdown(input);
    });
    $(".select-seat-type-dropdown input").on("keyup", function (e) {
        updateCustomSelectDropdown($(this));
    });
    $(".select-seat-type-dropdown input").on("change", function (e) {
        updateCustomSelectDropdown($(this));
    });
    function updateCustomSelectDropdown(input) {
        var parent = input.closest(".form-select-seat-type");
        var target = input.attr("id");
        var number = parseInt(input.val());
        var render = parent.find("[id=" + target + "_render]");
        var htmlString = render.find(".multi").data("html");
        var min = input.attr("min");
        //console.log(render)
        if (number > min) {
            render
                .find(".multi")
                .removeClass("d-none")
                .html(htmlString.replace(":count", number));
            render.find(".one").addClass("d-none");
        } else {
            render.find(".multi").addClass("d-none");
            render.find(".one").removeClass("d-none");
        }
    }
    $(".select-seat-type-dropdown .dropdown-item-row").on(
        "click",
        function (e) {
            e.stopPropagation();
        }
    );
    $(".smart-search .smart-search-occupancy").each(function () {
        var $this = $(this);
        var string_list = $this.attr("data-default");
        var default_list = [];
        if (string_list.length > 0) {
            default_list = JSON.parse(string_list);
        }
        var options = {
            dataDefault: default_list,
            iconItem: "",
            textLoading: $this.attr("data-onLoad"),
        };
        $this.bravoAutocomplete(options);
    });
    $(".smart-search .smart-search-flatbhk").each(function () {
        var $this = $(this);
        var string_list = $this.attr("data-default");
        var default_list = [];
        if (string_list.length > 0) {
            default_list = JSON.parse(string_list);
        }
        var options = {
            flatBhkType: "reset",
            dataDefault: default_list,
            iconItem: "",
            textLoading: $this.attr("data-onLoad"),
        };
        $this.bravoAutocomplete(options);
    });
    //Using in olerooms
    $(".smart-search .autocomplete-search").each(function () {
        var $this = $(this);
        var string_list = $(this).attr("data-default");
        var default_list = [];
        if (string_list.length > 0) {
            default_list = JSON.parse(string_list);
        }
        var options = {
            url: $("#auto_com_search_div").data("searchroute"),
            dataDefault: default_list,
            textLoading: $this.attr("data-onLoad"),
            iconItem: "icofont-location-pin",
        };

        $this.bravoAutocomplete(options);
    });
    //Using in olerooms
    $(".smart-search .smart-search-city").each(function () {
        var $this = $(this);
        var string_list = $(this).attr("data-default");
        var default_list = [];
        if (string_list.length > 0) {
            default_list = JSON.parse(string_list);
        }
        var options = {
            url: bookingCore.url + "/location/search/searchForSelect2",
            dataDefault: default_list,
            textLoading: $this.attr("data-onLoad"),
            iconItem: "icofont-location-pin",
        };
        $this.bravoAutocomplete(options);
    });
    $(document).on("click", ".service-wishlist", function () {
        var $this = $(this);
        var isPaginateRUn = document.getElementsByClassName("paginate-run");
        $.ajax({
            url: bookingCore.url + "customer/wishlist",
            data: {
                object_id: $this.attr("data-id"),
                object_model: $this.attr("data-type"),
            },
            dataType: "json",
            type: "POST",
            beforeSend: function () {
                if (isPaginateRUn.length > 0) {
                    $(".ajaxloader").show();
                }
                $this.addClass("loading");
            },
            success: function (res) {
                if (res["type"] == "error") {
                    Lobibox.notify("error", {
                        position: "top right",
                        msg: res["message"],
                    });
                } else {
                    $this.removeClass("active loading");
                    $this.addClass(res.class);
                    var isPaginateRUn =
                        document.getElementsByClassName("paginate-run");
                    if (isPaginateRUn.length > 0) {
                        paginate();
                    }
                }
            },
            error: function (e) {
                if (e.status === 401) {
                    Lobibox.notify("error", {
                        position: "top right",
                        msg: "Please login as customer to perform this action",
                    });
                }
            },
        });
    });
    $(".bravo-video-popup").click(function () {
        let video_url = $(this).data("src");
        let target = $(this).data("target");
        $(target)
            .find(".bravo_embed_video")
            .attr(
                "src",
                video_url + "?autoplay=0&amp;modestbranding=1&amp;showinfo=0"
            );
        $(target).on("hidden.bs.modal", function () {
            $(target).find(".bravo_embed_video").attr("src", "");
        });
    });
    var onSubmitContact = false;
    $(".bravo-contact-block-form").submit(function (e) {
        e.preventDefault();
        if (onSubmitContact) return;
        $(this).addClass("loading");
        var me = $(this);
        me.find(".form-mess").html("");
        $.ajax({
            url: me.attr("action"),
            type: "post",
            data: $(this).serialize(),
            dataType: "json",
            success: function (json) {
                onSubmitContact = false;
                me.removeClass("loading");
                if (json.message) {
                    me.find(".form-mess").html(
                        '<span class="' +
                        (json.status ? "text-success" : "text-danger") +
                        '">' +
                        json.message +
                        "</span>"
                    );
                }
                if (json.status) {
                    me.find("input").val("");
                    me.find("textarea").val("");
                }
            },
            error: function (e) {
                console.log(e);
                onSubmitContact = false;
                me.removeClass("loading");
                if (parseErrorMessage(e)) {
                    me.find(".form-mess").html(
                        '<span class="text-danger">' +
                        parseErrorMessage(e) +
                        "</span>"
                    );
                } else if (e.responseText) {
                    me.find(".form-mess").html(
                        '<span class="text-danger">' +
                        e.responseText +
                        "</span>"
                    );
                }
            },
        });
        return false;
    });
});
jQuery(function ($) {
    var notificationsWrapper = $(".dropdown-notifications");
    var notificationsToggle = notificationsWrapper.find("a[data-toggle]");
    var notificationsCountElem = notificationsToggle.find(".notification-icon");
    var notificationsCount = parseInt(notificationsCountElem.html());
    var notifications = notificationsWrapper.find("ul.dropdown-list-items");
    if (bookingCore.pusher_api_key && bookingCore.pusher_cluster) {
        var pusher = new Pusher(bookingCore.pusher_api_key, {
            encrypted: true,
            cluster: bookingCore.pusher_cluster,
        });
    }
    $(document).on("click", ".markAsRead", function (e) {
        e.stopPropagation();
        e.preventDefault();
        var id = $(this).data("id");
        var url = $(this).attr("href");
        $.ajax({
            url: bookingCore.markAsRead,
            data: {
                id: id,
            },
            method: "post",
            success: function (res) {
                window.location.href = url;
            },
        });
    });
    $(document).on("click", ".markAllAsRead", function (e) {
        e.stopPropagation();
        e.preventDefault();
        $.ajax({
            url: bookingCore.markAllAsRead,
            method: "post",
            success: function (res) {
                $(".dropdown-notifications")
                    .find("li.notification")
                    .removeClass("active");
                notificationsCountElem.text(0);
                notificationsWrapper.find(".notif-count").text(0);
            },
        });
    });
    var callback = function (data) {
        var existingNotifications = notifications.html();
        var newNotificationHtml =
            '<li class="notification active">' +
            '<div class="media">' +
            '   <a class="markAsRead p-0" data-id="' +
            data.idNotification +
            '" href="' +
            data.link +
            '">' +
            '    <div class="media-left">' +
            '      <div class="media-object">' +
            data.avatar +
            "      </div>" +
            "    </div>" +
            '    <div class="media-body">' +
            "      " +
            data.message +
            "" +
            '      <div class="notification-meta">' +
            '        <small class="timestamp">about a few seconds ago</small>' +
            "      </div>" +
            "    </div>" +
            "  </a>" +
            "</div>" +
            "</li>";
        notifications.html(newNotificationHtml + existingNotifications);
        notificationsCount += 1;
        notificationsCountElem.text(notificationsCount);
        notificationsWrapper.find(".notif-count").text(notificationsCount);
    };
    if (bookingCore.isAdmin > 0 && bookingCore.pusher_api_key) {
        var channel = pusher.subscribe("admin-channel");
        channel.bind("App\\Events\\PusherNotificationAdminEvent", callback);
    }
    if (bookingCore.currentUser > 0 && bookingCore.pusher_api_key) {
        var channelPrivate = pusher.subscribe(
            "user-channel-" + bookingCore.currentUser
        );
        channelPrivate.bind(
            "App\\Events\\PusherNotificationPrivateEvent",
            callback
        );
    }
});

//added by designer
$(".searchproperty_btn ").click(function () {
    // $("#searchinner_menu").toggleClass("show");
    // $("#searchinner_menu").toggleClass("hide");
    if (
        $("#searchinner_menu").hasClass("show")
    ) {
        $("#searchinner_menu").removeClass("show");
        $("#searchinner_menu").addClass("hide");
    } else {

        $("#searchinner_menu").addClass("show");
        $("#searchinner_menu").removeClass("hide");
    }
});

$(".g-button-submit .btn").click(function () {
    $("#searchinner_menu").addClass("hide");
    $("#searchinner_menu").removeClass("show");
});
