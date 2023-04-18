if (
    document.getElementById("landingpage") ||
    document.getElementById("searchpage") ||
    document.getElementById("bookingpage") ||
    document.getElementById("visitpage")
) {
    $(document).ready(function () {
        var nowDate = new Date();
        var checkindate = $(".check-in-input").val();
        var checkoutdate = $(".check-out-input").val();
        var checkinDate2;
        var today = new Date(
            nowDate.getFullYear(),
            nowDate.getMonth(),
            nowDate.getDate(),
            0,
            0,
            0,
            0
        );

        $(".check-in-render").daterangepicker(
            {
                singleDatePicker: true,
                autoApply: true,
                startDate: checkindate ? new Date(checkindate) : new Date(),
                disabledPast: true,
                customClass: "",
                widthSingle: 300,
                onlyShowCurrentMonth: true,
                minDate: today,
                opens: bookingCore.rtl ? "right" : "left",
                locale: {
                    format: "YYYY-MM-DD",
                    direction: bookingCore.rtl ? "rtl" : "ltr",
                    firstDay: daterangepickerLocale.first_day_of_week,
                },
            },
            function (start, end, label) {
                $(".check-in-input").val(start.format("YYYY-MM-DD"));
                var start_date = $(".check-in-input").val();
                var end_date = $(".check-out-input").val();
                if (start_date > end_date && end_date != "") {
                    $(".check-out-input").val(start_date);
                    $(".check-in-input").val(start_date);
                    $(".check-out-render").html(
                        '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                        start.format(bookingCore.view_end_date_formate)
                    );
                    $(".check-in-render").html(
                        '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                        start.format(bookingCore.view_end_date_formate)
                    );
                } else {
                    $(".check-in-render").html(
                        '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                        start.format(bookingCore.view_end_date_formate)
                    );
                }

                checkinDate2 = new Date(start_date);
                checkindate = new Date(
                    checkinDate2.getFullYear(),
                    checkinDate2.getMonth(),
                    checkinDate2.getDate(),
                    0,
                    0,
                    0,
                    0
                );
                checkOutDate(checkindate)
            }
        );

        const checkOutDate = (checkInDate) => {
            $(".check-out-render").daterangepicker(
                {
                    singleDatePicker: true,
                    startDate: checkoutdate ? new Date(checkoutdate) : new Date(),
                    autoApply: true,
                    disabledPast: true,
                    customClass: "",
                    widthSingle: 300,
                    onlyShowCurrentMonth: true,
                    minDate: checkInDate ? checkInDate : today,
                    opens: bookingCore.rtl ? "right" : "left",
                    locale: {
                        format: "YYYY-MM-DD",
                        direction: bookingCore.rtl ? "rtl" : "ltr",
                        firstDay: checkInDate ? checkInDate : daterangepickerLocale.first_day_of_week,
                    },
                },
                function (start, end, label) {
                    // console.log(checkindate);
                    // alert(checkindate);
                    $(".check-out-input").val(end.format("YYYY-MM-DD"));
                    var start_date = $(".check-in-input").val();
                    var end_date = $(".check-out-input").val();
                    if (end_date < start_date) {
                        $(".check-out-input").val(end_date);
                        $(".check-in-input").val(end_date);
                        $(".check-out-render").html(
                            '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                            end.format(bookingCore.view_end_date_formate)
                        );
                        $(".check-in-render").html(
                            '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                            end.format(bookingCore.view_end_date_formate)
                        );
                    } else {
                        $(".check-out-render").html(
                            '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                            end.format(bookingCore.view_end_date_formate)
                        );
                    }
                }
            );
        }

        checkOutDate(new Date())

        /****For Hotel Adults & children ***/
        $(".select-adults-dropdown .btn-minus").click(function (e) {
            e.stopPropagation();
            var parent = $(this).closest(".select-adults");
            var input = parent.find(
                ".select-adults-dropdown [name=" + $(this).data("input") + "]"
            );
            var min = parseInt(input.attr("min"));
            var old = parseInt(input.val());
            if (old <= min) {
                return;
            }
            input.val(old - 1);
            updateAdultsCountText(parent);
        });
        $(".select-adults-dropdown .btn-add").click(function (e) {
            e.stopPropagation();
            var parent = $(this).closest(".select-adults");
            var input = parent.find(
                ".select-adults-dropdown [name=" + $(this).data("input") + "]"
            );
            var max = parseInt(input.attr("max"));
            var old = parseInt(input.val());
            if (old >= max) {
                return;
            }
            input.val(old + 1);
            updateAdultsCountText(parent);
        });
        $(".select-adults-dropdown input").keyup(function (e) {
            var parent = $(this).closest(".select-adults");
            updateAdultsCountText(parent);
        });
        $(".select-adults-dropdown input").change(function (e) {
            var parent = $(this).closest(".select-adults");
            updateAdultsCountText(parent);
        });
        function updateAdultsCountText(parent) {
            var adults = parseInt(parent.find("[name=adults]").val());
            var children = parseInt(parent.find("[name=children]").val());
            var adultsHtml = parent.find(".render .adults .multi").data("html");
            parent
                .find(".render .adults .multi")
                .html(adultsHtml.replace(":count", adults));
            if (document.getElementById("searchpage")) {
                var hotelMinadulthtml = parent
                    .find(".render .adults .one")
                    .data("hoteladulthtml");
                parent
                    .find(".render .adults .one")
                    .html(hotelMinadulthtml.replace(":count", adults));
            }
            var childrenHtml = parent
                .find(".render .children .multi")
                .data("html");
            parent
                .find(".render .children .multi")
                .html(childrenHtml.replace(":count", children));
            if (adults > 1) {
                parent.find(".render .adults .multi").removeClass("d-none");
                parent.find(".render .adults .one").addClass("d-none");
            } else {
                parent.find(".render .adults .multi").addClass("d-none");
                parent.find(".render .adults .one").removeClass("d-none");
            }
            if (children > 1) {
                parent.find(".render .children .multi").removeClass("d-none");
                parent.find(".render .children .one").addClass("d-none");
            } else {
                parent.find(".render .children .multi").addClass("d-none");
                parent
                    .find(".render .children .one")
                    .removeClass("d-none")
                    .html(
                        parent
                            .find(".render .children .one")
                            .data("html")
                            .replace(":count", children)
                    );
            }
        }
        /****End Hotel Adults & children ***/
        /****For Flat Adults & children ***/
        $(".select-flat-adults-dropdown .btn-minus").click(function (e) {
            e.stopPropagation();
            var parent = $(this).closest(".select-flat-adults");
            var input = parent.find(
                ".select-flat-adults-dropdown [name=" +
                $(this).data("input") +
                "]"
            );
            var min = parseInt(input.attr("min"));
            var old = parseInt(input.val());
            if (old <= min) {
                return;
            }
            input.val(old - 1);
            updateFlatAdultsCountText(parent);
        });
        $(".select-flat-adults-dropdown .btn-add").click(function (e) {
            e.stopPropagation();
            var flatbhk_val = $(".flatbhk").val();
            if (flatbhk_val != "") {
                var max_attrval = parseInt(flatbhk_val * 2);
                var parent = $(this).closest(".select-flat-adults");
                var input = parent.find(
                    ".select-flat-adults-dropdown [name=" +
                    $(this).data("input") +
                    "]"
                );
                $(input).attr("max", max_attrval);
                var max = parseInt(input.attr("max"));
                var old = parseInt(input.val());
                if (old >= max) {
                    return;
                }
                input.val(old + 1);
                updateFlatAdultsCountText(parent);
            } else {
                $(".flat-options").show();
                return;
            }
        });
        $(".select-flat-adults-dropdown input").keyup(function (e) {
            var parent = $(this).closest(".select-flat-adults");
            updateFlatAdultsCountText(parent);
        });
        $(".select-flat-adults-dropdown input").change(function (e) {
            var parent = $(this).closest(".select-flat-adults");
            updateFlatAdultsCountText(parent);
        });
        function updateFlatAdultsCountText(parent) {
            var adults = parseInt(parent.find("[name=adults]").val());
            var children = parseInt(parent.find("[name=children]").val());
            var adultsHtml = parent.find(".render .adults .multi").data("html");
            parent
                .find(".render .adults .multi")
                .html(adultsHtml.replace(":count", adults));
            if (document.getElementById("searchpage")) {
                var flatMinadulthtml = parent
                    .find(".render .adults .one")
                    .data("flatadulthtml");
                parent
                    .find(".render .adults .one")
                    .html(flatMinadulthtml.replace(":count", adults));
            }
            var childrenHtml = parent
                .find(".render .children .multi")
                .data("html");
            parent
                .find(".render .children .multi")
                .html(childrenHtml.replace(":count", children));
            if (adults > 1) {
                parent.find(".render .adults .multi").removeClass("d-none");
                parent.find(".render .adults .one").addClass("d-none");
            } else {
                parent.find(".render .adults .multi").addClass("d-none");
                parent.find(".render .adults .one").removeClass("d-none");
            }
            if (children > 1) {
                parent.find(".render .children .multi").removeClass("d-none");
                parent.find(".render .children .one").addClass("d-none");
            } else {
                parent.find(".render .children .multi").addClass("d-none");
                parent
                    .find(".render .children .one")
                    .removeClass("d-none")
                    .html(
                        parent
                            .find(".render .children .one")
                            .data("html")
                            .replace(":count", children)
                    );
            }
        }
        /****End Flat Adults & children ***/
        /****For Guest Selectors***/
        $(".guests-dropdown .btn-minus").click(function (e) {
            e.stopPropagation();
            var parent = $(this).closest(".select-guests");
            var input = parent.find(
                ".guests-dropdown [name=" + $(this).data("input") + "]"
            );
            var min = parseInt(input.attr("min"));
            var old = parseInt(input.val());
            if (old <= min) {
                return;
            }
            input.val(old - 1);
            updateGuestCountText(parent);
        });
        $(".guests-dropdown .btn-add").click(function (e) {
            e.stopPropagation();
            var parent = $(this).closest(".select-guests");
            var input = parent.find(
                ".guests-dropdown [name=" + $(this).data("input") + "]"
            );
            var max = parseInt(input.attr("max"));
            var old = parseInt(input.val());
            if (old >= max) {
                return;
            }
            input.val(old + 1);
            updateGuestCountText(parent);
        });
        $(".guests-dropdown input").keyup(function (e) {
            var parent = $(this).closest(".select-guests");
            updateGuestCountText(parent);
        });
        $(".guests-dropdown input").change(function (e) {
            var parent = $(this).closest(".select-guests");
            updateGuestCountText(parent);
        });
        function updateGuestCountText(parent) {
            var guests = parseInt(parent.find("[name=guests]").val());
            var guestsHtml = parent.find(".render .guests .multi").data("html");
            parent
                .find(".render .guests .multi")
                .html(guestsHtml.replace(":count", guests));
            if (document.getElementById("searchpage")) {
                var guestsMinHtml = parent
                    .find(".render .guests .one")
                    .data("gusethtml");
                parent
                    .find(".render .guests .one")
                    .html(guestsMinHtml.replace(":count", guests));
            }
            if (guests > 1) {
                parent.find(".render .guests .multi").removeClass("d-none");
                parent.find(".render .guests .one").addClass("d-none");
            } else {
                parent.find(".render .guests .multi").addClass("d-none");
                parent.find(".render .guests .one").removeClass("d-none");
            }
        }
        /****End Guest Selectors***/
    });
}

if (document.getElementById("landingpage")) {

    $(document).ready(function () {




        $(".cityList .city-carousel").owlCarousel({
            loop: _SliderCityIsLoop,
            autoWidth: true,
            items: 4,
            autoplay: true,
            autoplayTimeout: 3000,
            smartSpeed: 3000,
            margin: 0,
            nav: false,
            responsive: {
                0: {
                    items: 3,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 4,
                },
            },
        });
        $(".dealofthe_day .owl-carousel").owlCarousel({
            items: 4,
            loop: false,
            margin: 15,
            nav: false,
            autoplay: true,
            autoplayTimeout: 3000,
            smartSpeed: 3000,
            responsive: {
                0: {
                    items: 1,
                },
                768: {
                    items: 2,
                },
                1000: {
                    items: 4,
                },
            },
        });
        $(".customer_trust .owl-carousel").owlCarousel({
            items: _SliderCoountForTrust,
            responsive: {
                480: { items: 1 }, // from zero to 480 screen width 4 items
                768: { items: 1 }, // from 480 screen widthto 768 6 items
                1024: {
                    items: 1, // from 768 screen width to 1024 8 items
                },
            },
            autoplay: false,
            margin: 10,
            nav: true,
            dots: false,
            loop: false,
        });
        $(".add_Slider .owl-carousel").owlCarousel({
            loop: false,
            margin: 20,
            nav: false,
            item: 2,
            autoplay: true,
            autoplayTimeout: 7000,
            lazyLoad: true,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 2,
                },
            },
        });
        $(".feature_property .owl-carousel").owlCarousel({
            margin: 30,
            loop: false,
            nav: false,
            item: 4,
            autoplay: false,
            autoplayTimeout: 7000,
            lazyLoad: true,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 4,
                },
            },
        });
    });
}
//key frame js
$(document).ready(function () {
    var keyslide = $(".key_features .owl-carousel");
    keyslide.owlCarousel({
        margin: 30,
        loop: true,
        nav: false,
        item: 5,
        autoplay: true,
        autoplayTimeout: 7000,
        lazyLoad: true,
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 2,
            },
            1000: {
                items: 5,
            },
        },
    });
    // Custom Button
    $(".customNextBtn").click(function () {
        keyslide.trigger("next.owl.carousel");
    });
    $(".customPreviousBtn").click(function () {
        keyslide.trigger("prev.owl.carousel");
    });
});

if (document.getElementById("brandSlider_script")) {
    $(".brandSlider .owl-carousel").owlCarousel({
        margin: 30,
        loop: true,
        nav: false,
        item: 8,
        autoplay: true,
        autoplayTimeout: 2500,
        lazyLoad: true,
        smartSpeed: 2500,
        responsive: { 0: { items: 3 }, 768: { items: 5 }, 1000: { items: 8 } },
    });
}
if (document.getElementById("propertyownerlandingpage")) {
    $(".customer_trust .owl-carousel").owlCarousel({
        items: _SliderCoountForTrust,
        responsive: {
            480: { items: 1 }, // from zero to 480 screen width 4 items
            768: { items: 1 }, // from 480 screen widthto 768 6 items
            1024: {
                items: 1, // from 768 screen width to 1024 8 items
            },
        },
        autoplay: true,
        margin: 10,
        nav: false,
        dots: false,
    });
}
if (document.getElementById("customer_profile_tab")) {
    $(".tablinks").click(function () {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(
                " active",
                ""
            );
        }
        tabName = $(this).data("tabname");
        document.getElementById(tabName).style.display = "block";
        $(this).addClass("active");
        //evt.currentTarget.className += " active";
    });
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
}
if (document.getElementById("vendor_complete_profile")) {
    $(document).ready(function () {
        if (window.File && window.FileList && window.FileReader) {
            $("body").delegate(".uploadfiles", "change", function (e) {
                var files = e.target.files,
                    filesLength = files.length;
                var _id_ = this.id;
                for (var i = 0; i < filesLength; i++) {
                    var f = files[i];
                    var fileReader = new FileReader();
                    fileReader.onload = function (e) {
                        $("#" + _id_)
                            .next(".pip")
                            .remove();
                        var file = e.target;
                        if (f.type == "application/pdf") {
                            $(
                                '<span class="pip">' +
                                '<img class="imageThumb" src="' +
                                pdf_file_path +
                                '" title="' +
                                file.name +
                                '"/>' +
                                '<br/><span class="remove" data-remove="' +
                                _id_ +
                                '">Remove image</span>' +
                                "</span>"
                            ).insertAfter("#" + _id_);
                        } else {
                            $(
                                '<span class="pip">' +
                                '<img class="imageThumb" src="' +
                                e.target.result +
                                '" title="' +
                                file.name +
                                '"/>' +
                                '<br/><span class="remove" data-remove="' +
                                _id_ +
                                '">Remove image</span>' +
                                "</span>"
                            ).insertAfter("#" + _id_);
                        }

                        $(".remove").click(function () {
                            $(this).parent(".pip").remove();
                            $("." + $(this).data("remove")).val("");
                        });
                    };
                    fileReader.readAsDataURL(f);
                }
            });
        } else {
            alert("Your browser doesn't support to File API");
        }
    });
}
if (document.getElementById("property_details") || document.getElementById("manage_property")) {
    $(document).on('click', '#myBtn', function () {
        myFunctionShowMore();
    });

    function myFunctionShowMore() {
        var moreText = $(".moreAmenites");
        if ($("#myBtn").text() == 'Show More') {
            moreText.show();
            $("#myBtn").text('Show Less');
        } else {
            $("#myBtn").text('Show More');
            moreText.hide();
        }
    }
}

if (document.getElementById("manage_property")||document.getElementById("customer_profile")) {

$(document).ready(function () {
    $("#state-dropdown").on("change", function () {
        getstateCIty();
    });
    $("#city-dropdown").on("change", function () {
        getCItyArea();
    });
    getstateCIty();
    function getstateCIty() {
        var state_id = $("#state-dropdown").val();
        $.ajax({
            url: APP_URL + "/api/get-state-cities",
            type: "POST",
            data: {
                state_id: state_id,
            },
            dataType: "json",
            success: function (result) {
                $("#city-dropdown").html("");
                $("#city-dropdown").append(
                    '<option value="">Select City</option>'
                );
                $.each(result.cities, function (key, value) {
                    $("#city-dropdown").append(
                        '<option value="' +
                        value.id +
                        '">' +
                        value.name +
                        "</option>"
                    );
                    if ($("#city_id").val() == value.id) {
                        $("#city-dropdown").val($("#city_id").val());
                    }
                });
                getCItyArea();
            },
        });
    }

    function getCItyArea() {
        var city_id__ = $("#city-dropdown").val();
        $.ajax({
            url: APP_URL + "/api/get-cities-area",
            type: "POST",
            data: {
                city_id: city_id__,
            },
            dataType: "json",
            success: function (result) {
                $("#area-dropdown").html("");
                $("#area-dropdown").append(
                    '<option value="">Select Area</option>'
                );
                $.each(result.areas, function (key, value) {
                    $("#area-dropdown").append(
                        '<option value="' +
                        value.id +
                        '">' +
                        value.name +
                        "</option>"
                    );
                    if ($("#area_id").val() == value.id) {
                        $("#area-dropdown").val($("#area_id").val());
                    }
                });
            },
        });
    }

    $("body").delegate(".apply_heading", "click", function () {
        var _BlockId = $(this).data("blockid");
        var step_count = $("#stepCount").text();
        $(".steps").hide();
        $("." + _BlockId).show();
        $("#" + _BlockId).show();
        $("#stepCount").text(step_count - 1);
    });
});
}

if (document.getElementById("manage_property")) {


    $(document).on('click', '.backButton', function () {
        $('.content_steps').text($(this).data('title'));
    });
    var locatorSection = document.getElementById("searchtag")
    var inputLocation = document.getElementById("location");
    function initialize() {
        var input = document.getElementById("location");
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
                showMap(place);
            }
        );
    }
    if (document.getElementById("locator-button")) {
        initGeoLocation();
        function initGeoLocation() {
            var locatorButton = document.getElementById("locator-button");
            locatorButton.addEventListener("click", locatorButtonPressed)
        }
        function locatorButtonPressed() {
            locatorSection.classList.add("loading")
            navigator.geolocation.getCurrentPosition(function (position) {
                getUserAddressBy(position.coords.latitude, position.coords.longitude)
            },
                function (error) {
                    locatorSection.classList.remove("loading")
                    Lobibox.notify("error", {
                        position: "top right",
                        msg: "The Locator was denied :( Please add your address manually",
                    });
                })
        }
        function getUserAddressBy(lat, long) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var address = JSON.parse(this.responseText)
                    setAddressToInputField(address, lat, long)
                }
            };
            xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + long + "&key=" + $("#gky").val(), true);
            xhttp.send();
        }
        function setAddressToInputField(address, lat, long) {
            var placeName = address.results[0].formatted_address;
            inputLocation.value = placeName
            document.getElementById("cityLat").value = lat;
            document.getElementById("cityLng").value = long;
            showMapUsingGeoLocation(placeName, lat, long);
            locatorSection.classList.remove("loading")
        }
    }
    google.maps.event.addDomListener(window, "load", initialize);
    // Define callback function for successful attempt
    function showMap(place) {
        // Get location data
        lat = place.geometry.location.lat();
        long = place.geometry.location.lng();
        var latlong = new google.maps.LatLng(lat, long);
        var myOptions = {
            center: latlong,
            zoom: 16,
            mapTypeControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.SMALL,
            },
        };
        var map = new google.maps.Map(
            document.getElementById("embedMap"),
            myOptions
        );
        var marker = new google.maps.Marker({
            position: latlong,
            map: map,
            title: place.name,
            icon: mapMarkerImage,
        });
    }
    function showMapUsingGeoLocation(placeName, lat, long) {
        // Get location data
        var latlong = new google.maps.LatLng(lat, long);
        var myOptions = {
            center: latlong,
            zoom: 16,
            mapTypeControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.SMALL,
            },
        };
        var map = new google.maps.Map(
            document.getElementById("embedMap"),
            myOptions
        );
        var marker = new google.maps.Marker({
            position: latlong,
            map: map,
            title: placeName,
            icon: mapMarkerImage,
        });
    }
    function initMap() {
        lat = $("#cityLat").val();
        long = $("#cityLng").val();
        place_name = $("#location").val();
        var latlong = new google.maps.LatLng(lat, long);
        var myOptions = {
            center: latlong,
            zoom: 16,
            mapTypeControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.SMALL,
            },
        };
        var map = new google.maps.Map(
            document.getElementById("embedMap"),
            myOptions
        );
        var marker = new google.maps.Marker({
            position: latlong,
            map: map,
            title: place_name,
            icon: mapMarkerImage,
        });
    }

    google.maps.event.addDomListener(window, "load", initMap);

   


    $(document).ready(function () {
        if (window.File && window.FileList && window.FileReader) {
            // $(".uploadSinglefile").on("change", function (e) {
            $("body").delegate(".uploadSinglefile", "change", function (e) {
                setTimeout(() => {
                    var files = e.target.files,
                        filesLength = files.length;
                    var _id_ = this.id;
                    var MediaId = $("#" + _id_)
                        .children(".uploadfileBtn")
                        .find("input")
                        .attr("id");
                    if (filesLength > 0) {
                        for (var i = 0; i < filesLength; i++) {
                            var f = files[i];
                            var fileReader = new FileReader();
                            fileReader.onload = function (e) {
                                $("#" + _id_)
                                    .next(".pip")
                                    .remove();
                                if ($("#ht_" + MediaId)) {
                                    $("#ht_" + MediaId).html("");
                                }
                                $("#" + _id_)
                                    .next(".pip")
                                    .remove();
                                var file = e.target;
                                if (f.type == "application/pdf") {
                                    $(
                                        '<span class="pip">' +
                                        '<img class="imageThumb" src="' +
                                        pdf_file_path +
                                        '" title="' +
                                        file.name +
                                        '"/>' +
                                        '<br/><span class="remove" data-remove="' +
                                        _id_ +
                                        '">Remove image</span>' +
                                        "</span>"
                                    ).insertAfter("#" + _id_);
                                } else {
                                    $(
                                        '<span class="pip">' +
                                        '<img class="imageThumb" src="' +
                                        e.target.result +
                                        '" title="' +
                                        file.name +
                                        '"/>' +
                                        '<br/><span class="remove" data-remove="' +
                                        _id_ +
                                        '">Remove image</span>' +
                                        "</span>"
                                    ).insertAfter("#" + _id_);
                                }

                                $(".remove").click(function () {
                                    $(this).parent(".pip").remove();
                                    $("." + $(this).data("remove")).val("");
                                });
                            };
                            fileReader.readAsDataURL(f);
                        }
                    }
                }, 1500);
            });
        } else {
            alert("Your browser doesn't support to File API");
        }

        $("body").delegate(".removerecord", "click", function (e) {
            $(this).parent(".pip").remove();
            var _fieldname = $(this).data("remove");
            var __count_fields = document.querySelectorAll(
                '.pip > input[name="' + _fieldname + '[]"]'
            );
            $("#f_" + _fieldname).val(__count_fields.length);
        });
        $("body").delegate(".removesingle", "click", function (e) {
            $(this).parent(".pip").remove();
            $("." + $(this).data("remove")).val("");
        });
    });
}

if (document.getElementsByClassName("statecityajax")) {
    // alert('statecityajax');
    $(document).ready(function () {
        $('#state-dropdown').on('change', function () {
            getstateCIty();
        });
        $('#city-dropdown').on('change', function () {
            getCItyArea();
        });
        // getstateCIty();
        function getstateCIty() {
            var state_id = $("#state-dropdown").val();
            $.ajax({
                url: APP_URL + '/api/get-state-cities',
                type: "POST",
                data: {
                    state_id: state_id
                },
                dataType: 'json',
                success: function (result) {
                    $("#city-dropdown").html('');
                    $("#city-dropdown").append('<option value="">Select City</option>');
                    $.each(result.cities, function (key, value) {
                        $("#city-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                        if ($("#city_id").val() == value.id) {
                            $("#city-dropdown").val($("#city_id").val());
                        }
                    });
                    getCItyArea();
                }
            });
        }
        function getCItyArea() {
            var city_id__ = $("#city-dropdown").val();
            $.ajax({
                url: APP_URL + '/api/get-cities-area',
                type: "POST",
                data: {
                    city_id: city_id__
                },
                dataType: 'json',
                success: function (result) {
                    $("#area-dropdown").html('');
                    $("#area-dropdown").append('<option value="">Select Area</option>');
                    $.each(result.areas, function (key, value) {
                        $("#area-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                        if ($("#area_id").val() == value.id) {
                            $("#area-dropdown").val($("#area_id").val());
                        }
                    });
                }
            });
        }
    });
}


if (document.getElementById("searchtag")) {
    $("#location").keyup(function () {
        document.getElementById("cityLat").value = "";
        document.getElementById("cityLng").value = "";
    });
    $("#location").blur(function () {
        if (
            !document.getElementById("cityLat").value &&
            !document.getElementById("cityLng").value
        ) {
            document.getElementById("location").value = "";
        }
    });
}

if (document.getElementById("manage_my_property")) {
    $(document).on("click", ".myproperty_modal", function () {
        var removeId = $(this).data("remove");
        $(".pip").remove();
        if ($("#f_" + removeId).val()) {
            $("#f_" + removeId).val("");
        }
        if ($("#ht_" + removeId).val()) {
            $("#ht_" + removeId).val("");
        }
        var id = $(this).data("id");
        var target = $(this).attr("data-target");
        $(target).modal("show");
        $(target + " .modal-body #property_id").val(id);
    });
    $(document).on("click", ".delete", function () {
        var property_id = $("#property_id").val();
        var url = $(this).attr("data-url");
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {
                property_id: property_id,
            },
            success: (data) => {
                if (data["status"]) {
                    $("#deleteProperty").modal("hide");
                    Lobibox.notify("success", {
                        position: "top right",
                        msg: data["message"],
                    });
                }
            },
            error: function (data) {
                console.log(data);
            },
        });
    });
    if (window.File && window.FileList && window.FileReader) {
        $(".uploadSinglefile").on("change", function (e) {
            setTimeout(() => {
                var files = e.target.files,
                    filesLength = files.length;
                var _id_ = this.id;
                if (filesLength > 0) {
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i];
                        var fileReader = new FileReader();
                        fileReader.onload = function (e) {
                            $("#" + _id_)
                                .next(".pip")
                                .remove();
                            var file = e.target;
                            if (f.type == "application/pdf") {
                                $(
                                    '<span class="pip">' +
                                    '<img class="imageThumb" src="' +
                                    pdf_file_path +
                                    '" title="' +
                                    file.name +
                                    '"/>' +
                                    '<br/><span class="remove" data-remove="' +
                                    _id_ +
                                    '">Remove image</span>' +
                                    "</span>"
                                ).insertAfter("#" + _id_);
                            } else {
                                $(
                                    '<span class="pip">' +
                                    '<img class="imageThumb" src="' +
                                    e.target.result +
                                    '" title="' +
                                    file.name +
                                    '"/>' +
                                    '<br/><span class="remove" data-remove="' +
                                    _id_ +
                                    '">Remove image</span>' +
                                    "</span>"
                                ).insertAfter("#" + _id_);
                            }
                            $(".remove").click(function () {
                                $(this).parent(".pip").remove();
                                $("." + $(this).data("remove")).val("");
                            });
                        };
                        fileReader.readAsDataURL(f);
                    }
                }
            }, 1500);
        });
    } else {
        alert("Your browser doesn't support to File API");
    }
    $(document).on("click", ".changestatus", function () {
        var publish_id = $(this).attr("id");
        var id = $(this).attr("data-id");
        var status = $(this).attr("data-default");
        var title = $(this).attr("data-title");
        var url = $(this).attr("data-url");
        Lobibox.confirm({
            draggable: false,
            closeButton: false,
            closeOnEsc: false,
            title: title + " Confirmation",
            msg: "Are you sure you, want to " + title + "?",
            callback: function ($this, type, ev) {
                if (type === "yes") {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status: status,
                        },
                        success: (data) => {
                            if (data["status"]) {
                                if (data["is_search"]) {
                                    serach();
                                }
                                Lobibox.notify("success", {
                                    position: "top right",
                                    msg: data["message"],
                                });
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        },
                    });
                } else {
                    $(this).val($(this).data("default"));
                    if (status == 1) {
                        $("#" + publish_id).prop("checked", true);
                    } else {
                        $("#" + publish_id).prop("checked", false);
                    }
                    return false;
                }
            },
        });
    });
    //Offer apply js
    $(document).on("click", ".myproperty_modal_offer", function () {
        $(".pip").remove();

        var id = $(this).data("id");
        var url = $(this).attr("data-url");
        var type_id = $(this).data("property-id");
        var offer_url = $(this).attr("data-offerapplyurl");
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {
                property_id: id,
                property_type_id: type_id,
            },
            beforeSend: function () {
                $("#loader").show();
            },
            success: (data) => {
                $("#loader").hide();

                if (data["status_code"] == 205) {
                    $("#applyOffer").modal("hide");
                    Lobibox.notify(data["type"], {
                        position: "top right",
                        msg: data["message"],
                    });
                    return false;
                }
                var html =
                    '<input type="hidden" name="property_id" id="property_id" value="' +
                    id +
                    '">';

                if (data.length != 0) {
                    $.each(data, function (key, val) {
                        if (val.is_offer_applied == 1) {
                            var cls = " active";
                            var applyBtn =
                                '<a href="javascript:;" data-id="' +
                                key +
                                '" class="grey text-uppercase font18 medium offer-apply" data-url="' +
                                offer_url +
                                '" data-title="Remove"> Applied </a>';
                        } else {
                            var cls = "";
                            var applyBtn =
                                '<a href="javascript:;" data-id="' +
                                key +
                                '" class="green text-uppercase font18 medium offer-apply" data-url="' +
                                offer_url +
                                '" data-title="Apply"> APPLY </a>';
                        }
                        html +=
                            '<div class="offerBox offer-applied ' +
                            cls +
                            '"><div class="d-flex text-left"><figure class="mb-0"><img src="' +
                            val.image +
                            '" width="100px" height="70px"></figure><div class="contentWrap w-100"><div class="d-flex justify-content-between mb-2"><span class="couponCode">' +
                            val.coupon_code +
                            "</span>" +
                            applyBtn +
                            '</div><p class="mb-0 grey font16 regular turnicate1">' +
                            val.title +
                            '</p> </div></div><div class="mt-3 text-left"><p class="mb-0 grey font16 regular">' +
                            val.description +
                            '</p><span class="couponCode">' +
                            val.start_date_coupon +
                            " - " +
                            val.end_date_coupon +
                            '</span></div><input type="hidden" id="coupon_id_' +
                            key +
                            '" name="coupon_id" value="' +
                            val.id +
                            '"></div>';
                    });
                } else {
                    html += '<div class="offerBox">No Coupon Available</div>';
                }
                $(".applyOffer_list").html(html);
                $("#applyOffer").modal("show");
            },
            error: function (data) {
                console.log(data);
            },
        });
    });
    $(document).on("click", ".offer-apply", function () {
        $(this).attr("disabled", true);
        var offerid = $(this).attr("data-id");
        var property_id = $("#applyOffer #property_id").val();
        var coupon_id = $("#coupon_id_" + offerid).val();
        var url = $(this).attr("data-url");
        var title = $(this).attr("data-title");
        Lobibox.confirm({
            draggable: false,
            closeButton: false,
            closeOnEsc: false,
            title: title + " Confirmation",
            msg: "Are you sure you, want to " + title + "?",
            callback: function ($this, type, ev) {
                if (type === "yes") {
                    $.ajax({
                        type: "POST",
                        enctype: "multipart/form-data",
                        url: url,
                        dataType: "json",
                        data: {
                            property_id: property_id,
                            coupon_id: coupon_id,
                        },
                        beforeSend: function () {
                            $(this).attr("disabled", "disabled");
                        },
                        success: (data) => {
                            console.log(data);
                            if (data["status_code"] == 200) {
                                $("#applyOffer").modal("hide");
                                Lobibox.notify("success", {
                                    position: "top right",
                                    msg: data["message"],
                                });
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        },
                    });
                } else {
                    $("#applyOffer").modal("hide");
                    return false;
                }
            },
        });
    });
}
if (document.getElementById("manage_my_booking")) {
    $(document).on("click", ".myproperty_modal", function () {
        $(".pip").remove();
        var id = $(this).data("id");
        var target = $(this).attr("data-target");
        $(target).modal("show");
        $(target + " .modal-body #property_id").val(id);
    });
    $(document).on("click", ".changestatus", function () {
        var publish_id = $(this).attr("id");
        var id = $(this).attr("data-id");
        var status = $(this).attr("data-default");
        var title = $(this).attr("data-title");
        var url = $(this).attr("data-url");
        Lobibox.confirm({
            draggable: false,
            closeButton: false,
            closeOnEsc: false,
            title: title + " Confirmation",
            msg: "Are you sure you, want to " + title + "?",
            callback: function ($this, type, ev) {
                if (type === "yes") {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status: status,
                        },
                        success: (data) => {
                            if (data["status"]) {
                                Lobibox.notify("success", {
                                    position: "top right",
                                    msg: data["message"],
                                });
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        },
                    });
                } else {
                    $(this).val($(this).data("default"));
                    if (status == 1) {
                        $("#" + publish_id).prop("checked", true);
                    } else {
                        $("#" + publish_id).prop("checked", false);
                    }
                    return false;
                }
            },
        });
    });
    $(function () {
        var start = moment().subtract(29, "days");
        var end = moment();
        function cb(start, end) {
            $("input[name='from']").val(start.format("YYYY-M-D"));
            $("input[name='to']").val(end.format("YYYY-M-D"));
            $("#reportrange span").html(
                start.format("MMMM D, YYYY") +
                " - " +
                end.format("MMMM D, YYYY")
            );
            serach();
        }
        $("#reportrange").daterangepicker(
            {
                startDate: start,
                endDate: end,
                showCustomRangeLabel: true,
                alwaysShowCalendars: true,
                ranges: {
                    Today: [moment(), moment()],
                    Yesterday: [
                        moment().subtract(1, "days"),
                        moment().subtract(1, "days"),
                    ],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(29, "days"), moment()],
                    "This Month": [
                        moment().startOf("month"),
                        moment().endOf("month"),
                    ],
                    "Last Month": [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                },
            },
            cb
        );
        cb(start, end);
    });
}
if (document.getElementById("filter-with-daterange")) {
    $(function () {
        var start = moment().subtract(29, "days");
        var end = moment();
        function cb(start, end) {
            $("input[name='from']").val(start.format("YYYY-M-D"));
            $("input[name='to']").val(end.format("YYYY-M-D"));
            $("#reportrange span").html(
                start.format("MMMM D, YYYY") +
                " - " +
                end.format("MMMM D, YYYY")
            );
            serach();
        }
        $("#reportrange").daterangepicker(
            {
                startDate: start,
                endDate: end,
                showCustomRangeLabel: true,
                alwaysShowCalendars: true,
                showDropdowns: true,
                minYear: 1901,
                ranges: {
                    Today: [moment(), moment()],
                    Yesterday: [
                        moment().subtract(1, "days"),
                        moment().subtract(1, "days"),
                    ],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(29, "days"), moment()],
                    "This Month": [
                        moment().startOf("month"),
                        moment().endOf("month"),
                    ],
                    "Last Month": [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                },
            },
            cb
        );
        cb(start, end);
    });
}


if (document.getElementById("property_details")) {

    function initMap() {
        lat = $("#lat").val();
        long = $("#long").val();
        place_name = $("#map_location").val();
        var latlong = new google.maps.LatLng(lat, long);
        var myOptions = {
            center: latlong,
            zoom: 16,
            mapTypeControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.SMALL,
            },
        };
        var map = new google.maps.Map(
            document.getElementById("embedMap"),
            myOptions
        );
        var marker = new google.maps.Marker({
            position: latlong,
            map: map,
            title: place_name,
            icon: mapMarkerImage,
        });
    }
    google.maps.event.addDomListener(window, "load", initMap);

    $(".feature_property .owl-carousel").owlCarousel({
        items: 4,
        loop: false,
        margin: 15,
        nav: false,
        autoplay: true,
        autoplayTimeout: 3000,
        smartSpeed: 3000,
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 2,
            },
            1000: {
                items: 4,
            },
        },
    });
}
if (document.getElementById("schedule_visit_success")) {
    function initMap() {
        $(".visit-map")
            .find(".visit-map-child")
            .map(function () {
                var id = $(this).find(".mapSec").attr("data-id");
                lat = $("#lat_" + id).val();
                long = $("#long_" + id).val();
                place_name = $("#map_location_" + id).val();
                var latlong = new google.maps.LatLng(lat, long);
                var myOptions = {
                    center: latlong,
                    zoom: 16,
                    mapTypeControl: true,
                    navigationControlOptions: {
                        style: google.maps.NavigationControlStyle.SMALL,
                    },
                };
                var map = new google.maps.Map(
                    document.getElementById("embedMap_" + id),
                    myOptions
                );
                var marker = new google.maps.Marker({
                    position: latlong,
                    map: map,
                    title: place_name,
                    icon: mapMarkerImage,
                });
            });
    }
    google.maps.event.addDomListener(window, "load", initMap);
}
if (document.getElementById("news_updates")) {
    $(".newsupdate").click(function () {
        $(".newsupdate").removeClass("active");
        $(this).addClass("active");
    });
}
$(document).ready(function () {
    var payment_type_val = $("input[name='payment_type']:checked").val();
    if (payment_type_val == "cheque") {
        $(".cheque_div").show();
        $(".cheque_related_input").prop("required", true);
        $(".upi_div").hide();
        $(".upi_related_input").prop("required", false);
        $(".upi_related_input").val("");
    } else {
        $(".upi_div").show();
        $(".upi_related_input").prop("required", true);
        $(".cheque_div").hide();
        $(".cheque_related_input").prop("required", false);
        $(".cheque_related_input").val("");
    }
    $("body").on("change", ".payment_type_property", function () {
        var payment_type_val = $("input[name='payment_type']:checked").val();
        var bank_name = $("input[name='bank_name']").data("default");
        var holder_name = $("input[name='holder_name']").data("default");
        var account_number = $("input[name='account_number']").data("default");
        var ifsc_code = $("input[name='ifsc_code']").data("default");
        var upi_id = $("input[name='upi_id']").data("default");
        var upi_qr_code_image = $("input[name='upi_qr_code_image']").data(
            "default"
        );
        var upi_thumb_image = $("#upi_thumb_image").data("default");
        var cancelled_check_photo = $(
            "input[name='cancelled_check_photo']"
        ).data("default");
        var passbook_front_photo = $("input[name='passbook_front_photo']").data(
            "default"
        );
        // $('.payment_pip').html('');
        $(".payment_cheque_upi").find(".pip").html("");
        if (payment_type_val == "cheque") {
            if (
                cancelled_check_photo === undefined &&
                passbook_front_photo === undefined
            ) {
                $("#cancelled_cheque_files").next().hide();
                $("#passbook_front_files").next().hide();
            } else {
                $("#cancelled_cheque_files").next().show();
                $("#passbook_front_files").next().show();
            }
            $(".cheque_div").show();
            $(".cheque_related_input").prop("required", true);
            $(".upi_div").hide();
            $(".upi_related_input").prop("required", false);
            $(".upi_related_input").val("");
            $("input[name='bank_name']").val(bank_name);
            $("input[name='holder_name']").val(holder_name);
            $("input[name='account_number']").val(account_number);
            $("input[name='ifsc_code']").val(ifsc_code);
            $("#f_cancelled_cheque").val(cancelled_check_photo);
            $("#f_passbook_front").val(passbook_front_photo);
        } else {
            if (upi_qr_code_image === undefined) {
                $("#upi_qr_code_files").find("span").hide();
            } else {
                $("#upi_qr_code_files").find("span").show();
            }
            $("input[name='upi_id']").val(upi_id);
            $("#f_upi_qr_code").val(upi_qr_code_image);
            $(".upi_div").show();
            $(".upi_related_input").prop("required", true);
            $(".cheque_div").hide();
            $(".cheque_related_input").prop("required", false);
            $(".cheque_related_input").val("");
        }
    });
    $("body").on("click", ".room_type", function () {
        var roomtype_count = $(".room_type:checked").length;
        var roomClassVal = $(this).val();
        if ($(this).is(":checked")) {
            $("#" + roomClassVal + "AcRoomType").prop("disabled", false).prop("required", true);
            $("#" + roomClassVal + "NonAcRoomType").prop("disabled", false);
        } else {
            $("." + roomClassVal + "_room_input").prop("checked", false).prop("disabled", true).val("");
            $("#" + roomClassVal + "AcRoomType").prop("required", false);
        }
        if (roomtype_count == 0) {
            $(".room_type_single").prop("required", true);
        } else {
            $(".room_type_single").prop("required", false);
        }
        $(this).prop("disabled", false).val(roomClassVal);
    });
    $("body").on("click", ".room_sub_type", function () {
        var roomTypeVal = $(this).attr("data-room-type");
        var roomSubTypeVal = $(this).attr("data-type");
        if ($(this).is(":checked")) {
            $("." + roomTypeVal + "_room_" + roomSubTypeVal + "_com").show();
            $("." + roomTypeVal + "_" + roomSubTypeVal + "_input")
                .prop("required", true)
                .prop("disabled", false);
            $("." + roomTypeVal + "_" + roomSubTypeVal + "_is_food").prop(
                "disabled",
                false
            );
            $(".room_type_" + roomTypeVal).prop("checked", true);
        } else {
            $("." + roomTypeVal + "_room_" + roomSubTypeVal + "_com").hide();
            $("." + roomTypeVal + "_" + roomSubTypeVal + "_input")
                .prop("required", false)
                .prop("disabled", true)
                .val("");
            $("." + roomTypeVal + "_" + roomSubTypeVal + "_is_food")
                .prop("disabled", true)
                .prop("checked", false);
        }

        var selectedRoomSubTypeLength = $(
            "." + roomTypeVal + "_room_sub_type:checked"
        ).length;
        if (selectedRoomSubTypeLength == 0) {
            $("#" + roomTypeVal + "AcRoomType").prop("required", true);
        } else {
            $("#" + roomTypeVal + "AcRoomType").prop("required", false);
        }
    });
});
$(document).on("click", ".visit-property", function () {
    var visit_id = $(this).attr("data-id");
    var url = $(this).attr("data-url");
    Lobibox.confirm({
        draggable: false,
        closeButton: false,
        closeOnEsc: false,
        title: "Remove Confirmation",
        msg: "Are you sure you, want to remove from schedule list?",
        callback: function ($this, type, ev) {
            if (type === "yes") {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        visit_id: visit_id,
                    },
                    beforeSend: function () {
                        $("#loader").show();
                    },
                    success: (data) => {
                        $("#loader").hide();
                        Lobibox.notify("success", {
                            position: "top right",
                            msg: data["message"],
                        });
                        if (data["reload"]) {
                            location.reload();
                        }
                    },
                    error: function (data) {
                        $("#loader").hide();
                        Lobibox.notify("error", {
                            position: "top right",
                            msg: "Something went wrong please try later.",
                        });
                        return false;
                    },
                });
            } else {
                $("#loader").hide();
                return false;
            }
        },
    });
});
$(function (e) {
    var nowDate = new Date();
    var today = new Date(
        nowDate.getFullYear(),
        nowDate.getMonth(),
        nowDate.getDate(),
        0,
        0,
        0,
        0
    );
    $(".visit_dates").daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        disabledPast: true,
        onlyShowCurrentMonth: true,
        minDate: today,
        setDate: null,
        locale: {
            format: "YYYY-MM-DD",
            cancelLabel: "Clear",
        },
    });

    $(".visit_time").timepicker();

    // visit date booking
    $(".visit-date-render").daterangepicker(
        {
            singleDatePicker: true,
            autoApply: true,
            disabledPast: true,
            onlyShowCurrentMonth: true,
            minDate: today,
            setDate: null,
            locale: {
                format: "YYYY-MM-DD",
                cancelLabel: "Clear",
            },
            onSelect: function (value, date) {
                console.log("value:" + value);
                console.log("date:" + date);
            },
        },
        function (start, end, label) {
            $(this.element[0])
                .parent()
                .parent()
                .parent()
                .find(".visit-date-input")
                .val(start.format("YYYY-MM-DD"));

            $(this.element[0])
                .parent()
                .parent()
                .parent()
                .find(".visit-date-render")
                .html(
                    '<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>' +
                    start.format(
                        bookingCore.view_visit_checkin_date_formate
                    )
                );
        }
    );
});

$("body").delegate(".active_remove_tab", "click", function () {
    $(".active_remove_tab").removeClass("active");
    $(this).addClass("active");

    var myvisitSerach = $(this).data("default");
    $("#search_type").val(myvisitSerach);
    serach();
});
// filter btn js
$('body').on('click', ".mobileproperty_filter", function () {

    if ($(".filter-col").hasClass("open")) {
        $(".filter-col").removeClass("open");
    } else {
        $(".filter-col").addClass("open");
    }
});


$('body').delegate('.copycode', 'click', function () {
    // Create a "hidden" input
    var aux = document.createElement("input");
    // Assign it the value of the specified element
    var elementId = $(this).data('cid');
    aux.setAttribute("value", document.getElementById(elementId).innerHTML);
    // Append it to the body
    document.body.appendChild(aux);
    // Highlight its content
    aux.select();
    // Copy the highlighted text
    document.execCommand("copy");
    // Remove it from the body
    document.body.removeChild(aux);
    Lobibox.notify('success', {
        position: "top right",
        msg: 'Copied Successfully'
    });
});

