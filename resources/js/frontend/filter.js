if (document.getElementById("searchpage")) {

    $(document).ready(function () {
        /****End Guest Selectors***/

        $('body').on('change', '#state-dropdown-filter', function () {
            getstateCItyFilter();
        });
        $('body').on('change', '#city-dropdown-filter', function () {
            getCItyAreaFilter();
        });
        var state_id_selected = $("#state-dropdown-filter").val();
        var city_id_selected = $("#city-dropdown-filter").val();

        if (state_id_selected != '') {
            getstateCItyFilter();
        }

        if (city_id_selected != '') {
            getCItyAreaFilter();
        }
        function getstateCItyFilter() {
            var state_id = $("#state-dropdown-filter").val();
            $.ajax({
                url: APP_URL + '/api/get-state-cities',
                type: "POST",
                data: {
                    state_id: state_id
                },
                dataType: 'json',
                success: function (result) {
                    $("#city-dropdown-filter").html('');
                    $("#city-dropdown-filter").append('<option value="">Select City</option>');
                    $.each(result.cities, function (key, value) {
                        $("#city-dropdown-filter").append('<option value="' + value.id + '">' + value.name + '</option>');
                        if ($("#city_id").val() == value.id) {
                            $("#city-dropdown-filter").val($("#city_id").val());
                        }
                    });
                    getCItyAreaFilter();
                }
            });
        }

        function getCItyAreaFilter() {
            var city_id__ = $("#city-dropdown-filter").val();
            $.ajax({
                url: APP_URL + '/api/get-cities-area',
                type: "POST",
                data: {
                    city_id: city_id__
                },
                dataType: 'json',
                success: function (result) {
                    $("#area-dropdown-filter").html('');
                    $("#area-dropdown-filter").append('<option value="">Select Area</option>');
                    $.each(result.areas, function (key, value) {
                        $("#area-dropdown-filter").append('<option value="' + value.id + '">' + value.name + '</option>');
                        if ($("#area_id").val() == value.id) {
                            $("#area-dropdown-filter").val($("#area_id").val());
                        }
                    });
                }
            });
        }


        $("body").on('change', '.bravo_form_filter_property input[name=property_type]', function () {
            $(this).closest(".bravo_form_filter_property").submit();
        });

        $("body").on('change', '.bravo_form_filter_search_property input[type=radio]', function () {
            $(this).closest(".bravo_form_filter_search_property").submit();
        });
        $("body").on('change', '.bravo_form_filter_search_property select', function () {
            $(this).closest(".bravo_form_filter_search_property").submit();
        });

        $("body").on('click', '.property-sort', function () {
            var sort_by = $(this).attr('title');
            $("#propertyOrderBy").val(sort_by);
            $(".bravo_form_filter_search_property").submit();
        });

        $("body").on('click', '.property-map', function () {
            var map = $(this).attr('data-title');
            $("#mapShowVal").val(map);
            $(".bravo_form_filter_search_property").submit();
        });


        $("body").on('click', '#searchFilterButton', function () {
            $(".bravo_form_filter_search_property").submit();
        });


        $("body").on('click', '.search-layout', function () {
            var search_layout = $(this).attr('title');
            $("#searchLayout").val(search_layout);
            $(".bravo_form_filter_search_property").submit();
        });

        $(".bravo_form_filter_search_property").submit(function (event) {
            event.preventDefault();
            var orderby = '';
            var searchKey = '';
            var searchLayout = '';
            var check_in_date = '';
            var check_out_date = '';
            var occupancy_type = [];
            var standard_type = '';
            var room_ac_type = '';
            var map_value = '';
            var guests = '';
            var children = '';
            var adults = '';
            var state_id = '';
            var city_id = '';
            var area_id = '';
            var capacity = '';
            var rating = '';
            var property_type = '';
            var available_for = [];
            var price_range = '';
            // var bhk_type = [];
            var bhk_type = '';
            var room_standard = [];

            if ($("#propertyOrderBy").val()) {
                orderby = $("#propertyOrderBy").val();
            }

            // if ($("input[name='available_for']:checked").val()) {
            //     available_for = $("input[name='available_for']:checked").val();
            // }


            if ($("input[name='available_for[]']:checked").val()) {
                $("input[name='available_for[]']:checked").each(function (i) {
                    available_for[i] = $(this).val();
                });
            }

            if ($("input[name='room_ac_type']:checked").val()) {
                room_ac_type = $("input[name='room_ac_type']:checked").val();
            }

            if ($("input[name='price_range']:checked").val()) {
                price_range = $("input[name='price_range']:checked").val();
            }

            if ($("input[name='property_type']:checked").val()) {
                property_type = $("input[name='property_type']:checked").val();
            }

            if ($("input[name='rating']:checked").val()) {
                rating = $("input[name='rating']:checked").val();
            }

            if ($("input[name='check_in_date']").val()) {
                check_in_date = $("input[name='check_in_date']").val();
            }


            if ($("input[name='searchKey']").val()) {
                searchKey = $("input[name='searchKey']").val();
            }


            if ($("input[name='map_value']").val()) {
                map_value = $("input[name='map_value']").val();
            }

            if ($("input[name='guests']").val()) {
                guests = $("input[name='guests']").val();
            }

            if ($("input[name='children']").val()) {
                children = $("input[name='children']").val();
            }

            if ($("input[name='adults']").val()) {
                adults = $("input[name='adults']").val();
            }

            if ($("input[name='occupancy_type[]']:checked").val()) {
                $("input[name='occupancy_type[]']:checked").each(function (i) {
                    occupancy_type[i] = $(this).val();
                });
            }

            if ($("input[name='room_standard[]']:checked").val()) {
                $("input[name='room_standard[]']:checked").each(function (i) {
                    room_standard[i] = $(this).val();
                });
            }



            if ($("input[name='bhk_type']").val()) {
                bhk_type = $("input[name='bhk_type']").val();
            }


            if ($("input[name='check_out_date']").val()) {
                check_out_date = $("input[name='check_out_date']").val();
            }
            if ($("#searchLayout").val()) {
                searchLayout = $("#searchLayout").val();
            }
            if ($("select[name='state_id']").val()) {
                state_id = $("select[name='state_id']").val();
            }
            if ($("select[name='capacity']").val()) {
                capacity = $("select[name='capacity']").val();
            }

            if ($("select[name='city_id']").val()) {
                city_id = $("select[name='city_id']").val();
            }

            if ($("select[name='area_id']").val()) {
                area_id = $("select[name='area_id']").val();
            }

            var customURL = "?";
            if (searchLayout != '') { 
                customURL = customURL + "searchLayout=" + searchLayout;
            }

            if (searchKey != '') {
                customURL = customURL + "&searchKey=" + searchKey;
            }

            if (property_type != '') {
                customURL = customURL + "&property_type=" + property_type;
            }

            if (rating != '') {
                customURL = customURL + "&rating=" + rating;
            }


            if (map_value != '') {
                customURL = customURL + "&map_value=" + map_value;
            }
            if (price_range != '') {
                customURL = customURL + "&price_range=" + price_range;
            }

            if (state_id != '') {
                customURL = customURL + "&state_id=" + state_id;
            }

            if (city_id != '') {
                customURL = customURL + "&city_id=" + city_id;
            }
            if (area_id != '') {
                customURL = customURL + "&area_id=" + area_id;
            }

            if (capacity != '') {
                customURL = customURL + "&capacity=" + capacity;
            }
            if (orderby != '') {
                customURL = customURL + "&orderby=" + orderby;
            }
            if (check_in_date != '') {
                customURL = customURL + "&check_in_date=" + check_in_date;
            }

            if (check_out_date != '') {
                customURL = customURL + "&check_out_date=" + check_out_date;
            }
            if (bhk_type != '') {
                customURL = customURL + "&bhk_type=" + bhk_type;
            }

            if (occupancy_type != '') {
                customURL = customURL + "&occupancy_type=" + occupancy_type;
            }
            if (guests != '') {
                customURL = customURL + "&guests=" + guests;
            }
            if (children != '') {
                customURL = customURL + "&children=" + children;
            }

            if (adults != '') {
                customURL = customURL + "&adults=" + adults;
            }

            if (available_for != '') {
                customURL = customURL + "&available_for=" + available_for;
            }

            if (room_ac_type != '') {
                customURL = customURL + "&room_ac_type=" + room_ac_type;
            }

            if (room_standard != '') {
                customURL = customURL + "&room_standard=" + room_standard;
            }



            var _changeUrl = REQUEST_URL + customURL;
            // window.history.pushState("object or string", "Filter", _changeUrl);
            $.ajax({
                type: "get",
                url: _changeUrl,
                data: {},
                datatype: "html",
                beforeSend: function () {
                    $("#loader_msg").html(_loaderMsg);
                    $("#loader").show();
                }
            }).done(function (data) {
                window.history.pushState({ url: "" + REQUEST_URL + "" }, '', _changeUrl);
                $("#loader").hide();
                if (data['html_data']) {
                    $("#resultSearchProp").empty().append(JSON.parse(data['html_data']));
                    $(".myratingview").starRating({
                        totalStars: 5,
                        starSize: 20,
                        activeColor: "#FF6E41",
                        useGradient: false,
                        readOnly: true,
                    });
                }
                else {
                    window.location.href = _changeUrl;
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#loader").hide();
                $(".btn-close").trigger("click");
                $(".lobibox-close").click();
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
                    delay: 2000,
                    delayIndicator: true,
                    msg: error
                });
            });
        });

    });


}

$(document).ready(function () {
    /****For Deal of The Day***/
    $(document).on('click', '.deal-of-the-day-div', function (e) {
        $('#title_coupon').html($(this).attr('data-coupon-name'));
        $('#desc_coupon').html($(this).attr('data-coupon-desc'));
        $('#image_coupon').css('background', 'url(' + $(this).attr('data-coupon-imgpath') + ')');
        document.getElementById('coupon_offer_route').href = $(this).attr('data-offerhref');
    });
    /****For Deal of The Day***/

    /****For Guest Selectors***/
    $(document).on('click', '.guests-filter-input .btn-minus', function (e) {
        e.stopPropagation();
        var input = $(".guests-filter-input input[name='guests']");
        var min = parseInt(input.attr('min'));
        var old = parseInt(input.val());

        if (old <= min) {
            return;
        }
        input.val(old - 1);
    });

    $(document).on('click', '.guests-filter-input .btn-add', function (e) {
        e.stopPropagation();
        var input = $(".guests-filter-input input[name='guests']");
        var max = parseInt(input.attr('max'));
        var old = parseInt(input.val());
        if (old >= max) {
            return;
        }
        input.val(old + 1);
    });
});


