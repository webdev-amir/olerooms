const { data } = require("jquery");
if (document.getElementById("doNotReverseThisPage")) {
    $(document).ready(function () {

        setTimeout(myStopFunction, 1);
        function myStopFunction() {
            document.body.click()
            $('#doNotReverseThisPage').trigger('click');
            $(document).click();
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function () {
                window.history.go(1);
            };
        }
    });

}

if (document.getElementById("myBookingVendorPageShow") || document.getElementById("bookingSuccesspage") || document.getElementById("bookingShowpage")) {

    $('.printDetailsBooking').css('cursor', 'pointer');
    $(document).on('click', '.printDetailsBooking', function (e) {
        e.preventDefault();
        window.print();
    });

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
                style: google.maps.NavigationControlStyle.SMALL
            }
        }
        var map = new google.maps.Map(document.getElementById("embedMap"), myOptions);
        var marker = new google.maps.Marker({
            position: latlong,
            map: map,
            title: place_name,
            url: "https://maps.google.com?q=" + lat + "," + long,
            icon: mapMarkerImage,
        });
        google.maps.event.addListener(marker, 'click', function () { window.open(marker.url, '_blank'); });
    }
    google.maps.event.addDomListener(window, 'load', initMap);

}




if (document.getElementById("bookingpage")) {

    // 1 = 'hostel-pg'
    // 2 = 'flat'
    // 3 = 'guest-hotel'
    // 4 = 'hostel-pg-one-day'
    // 5 = 'homestay'

    var propertyType = $('#propertyTypeSlug').val();
    const isOnlyGuestType = ['hostel-pg', 'hostel-pg-one-day', 'homestay'].find(element => element == propertyType);
    const isRoomType = ['hostel-pg', 'guest-hotel', 'hostel-pg-one-day'].find(element => element == propertyType);
    const isOnlyOneDayType = ['guest-hotel', 'hostel-pg-one-day', 'homestay'].find(element => element == propertyType);
    const isFlatType = propertyType == 'flat';
    const isHomestay = propertyType == 'homestay';
    const isHotel = propertyType == 'guest-hotel';

    var nowDate = new Date();
    var checkindate = $(".check-in-input").val();
    var checkoutdate = $(".check-out-input").val();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
    var checkinDate2, sendMsg;
    var reloadVal = 0;
    var isCall = 1;

    $(".check-in-renders").daterangepicker(
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
                $(".check-out-renders").html(
                    '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                    start.format(bookingCore.view_end_date_formate)
                );
                $(".check-in-renders").html(
                    '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                    start.format(bookingCore.view_end_date_formate)
                );
            } else {
                $(".check-in-renders").html(
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
            if (isOnlyOneDayType) {
                setTimeout(function () {
                    dateDifference();
                    calculateAmount();
                }, 1000);
            }

        }
    );

    const checkOutDate = (checkInDate) => {
        $(".check-out-renders").daterangepicker(
            {
                singleDatePicker: true,
                startDate: checkoutdate ? new Date(checkoutdate) : new Date(),
                autoApply: true,
                disabledPast: true,
                customClass: "",
                widthSingle: 300,
                onlyShowCurrentMonth: true,
                minDate: checkindate ? new Date(checkindate) : new Date(),
                opens: bookingCore.rtl ? "right" : "left",
                locale: {
                    format: "YYYY-MM-DD",
                    direction: bookingCore.rtl ? "rtl" : "ltr",
                    firstDay: checkInDate ? checkInDate : daterangepickerLocale.first_day_of_week,
                },
            },
            function (start, end, label) {
                $(".check-out-input").val(end.format("YYYY-MM-DD"));
                var start_date = $(".check-in-input").val();
                var end_date = $(".check-out-input").val();
                if (end_date < start_date) {
                    $(".check-out-input").val(end_date);
                    $(".check-in-input").val(end_date);
                    $(".check-out-renders").html(
                        '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                        end.format(bookingCore.view_end_date_formate)
                    );
                    $(".check-in-renders").html(
                        '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                        end.format(bookingCore.view_end_date_formate)
                    );
                } else {
                    $(".check-out-renders").html(
                        '<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>' +
                        end.format(bookingCore.view_end_date_formate)
                    );
                }
                if (isOnlyOneDayType) {
                    dateDifference();
                    calculateAmount();
                }
            }
        );
    }
    checkOutDate(new Date())



    totalGuests();

    /****For Guest Selectors***/
    if (isRoomType) {
        getAcNonAcOptions();
    } else {
        calculateAmount();
    }

    $('body').on('click', '.btn-minus-guests', function (e) {
        e.stopPropagation();
        var dataClass = $(this).attr('data-input-class');
        var input = $('.' + dataClass + '');
        var min = parseInt(input.attr('min'));
        var old = parseInt(input.val());

        if (old <= min) {
            return;
        }

        input.val(old - 1);
        totalGuests();
        calculateAmount();
    });

    if (isFlatType) {
        var flatbhk_val = $('.flatbhk').val().replace('bhk', '');
        $(".btn-add-guests").each(function () {
            var dataClass = $(this).attr('data-input-class');
            var input = $('.' + dataClass + '');
            var max_attrval = parseInt(flatbhk_val * 2);
            $(input).attr('max', max_attrval);
        });
    }

    $('body').on('click', '.btn-add-guests', function (e) {
        e.stopPropagation();

        var dataClass = $(this).attr('data-input-class');
        var input = $('.' + dataClass + '');
        if (isFlatType) {
            var flatbhk_val = $('.flatbhk').val().replace('bhk', '');
            var max_attrval = parseInt(flatbhk_val * 2);
            $(input).attr('max', max_attrval);
        }

        if (isHotel) {
            var max_attrval = 2;
            $(input).attr('max', max_attrval);
        }
        var max = parseInt(input.attr('max'));
        var old = parseInt(input.val());
        if (old >= max) {
            return;
        }
        input.val(old + 1);
        totalGuests();
        calculateAmount();
    });

    $('body').on('change', '#roomTypeVal', function () {


        getAcNonAcOptions();



    });

    $('body').on('change', '.roomCoolType', function () {

        getAcNonAcOptionsRadio();



    });


    $('#offerCode').on('change', function () {
        calculateDiscountAmount();
        if ($("#AppliedCouponCode").val() == $(this).val() && $(this).val() != '') {
            $('#basic-addon1').html('Applied').removeClass('grey applyOfferButton').addClass('green ');
        } else {
            $('#basic-addon1').html('Apply').addClass('grey applyOfferButton').removeClass('green');
        }


    });


    $('.paidType').on('change', function () {
        amountType();
    });

    $('#agentCompanyCode1').on('keyup', function () {

        $('#applyCodeButton').html('Apply').removeClass('green').addClass('grey');
    });


    $(document).on('click', '.applyCodeButton', function () {

        calculateAgentCorpCodeDiscount();
        calculateAmount();
        return;

    });

    $('body').on('click', '#removeCoupon', function () {
        sendMsg = "Offer code removed successfully!";
        removeDiscount(sendMsg);
    });


    function dateDifference() {

        var start_date = $(".check-in-input").val();
        var end_date = $(".check-out-input").val();
        var endDate = new Date(end_date);
        var startDate = new Date(start_date);
        var diffTime = Math.abs(endDate - startDate);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (isNaN(diffDays)) {
            diffDays = 0;
        }
        $('#daysDiff').val(diffDays);
        // return diffDays;

    }

    function removeDiscount(sendMsg = '') {
        reloadVal = 1;
        $('#agentCompanyCode1').val('');
        $('#agentCompanyCode').val('');
        $('#agentCompanyCodeType').val('');
        $('#discountAmount').val('');
        $('.discountAmount').html(0);
        $('#discountSucccessText').hide().removeClass('d-flex');
        $('#discountType').val('');
        $('#discountValue').val('');
        $('#discountSucccessAmount').html('');
        $("#AppliedCouponCode").val('');
        $('#offerCode').val('');
        $('#basic-addon1').html('Apply').addClass('applyOfferButton grey').removeClass('green');
        $('#applyCodeButton').html('Apply').removeClass('green').addClass('grey');
        if (sendMsg != '') {
            Lobibox.notify('error', {
                position: "top right",
                msg: sendMsg,
            });
        }
        calculateAmount();
        return;

    }

    function calculateAgentCorpCodeDiscount() {
        var agentCorpCode = $('#agentCompanyCode1').val();
        var property_price = $('#totalAmount').val();
        if (property_price == 0 || agentCorpCode == '') {
            sendMsg = property_price == 0 ? "Property amount must be greater than zero!" : 'Please insert Agent/Corporate code!';
            Lobibox.notify('error', {
                position: "top right",
                msg: sendMsg,
            });
        } else {
            var agentCorpCodeUrl = $('#applyCodeButton').data('url');
            $.ajax({
                type: "POST",
                url: agentCorpCodeUrl,
                data: {
                    agent_corp_code: agentCorpCode,
                    property_price: property_price,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend: function () {
                    var _loaderMsg = $(this).attr("data-loader");
                    $("#loader_msg").html(_loaderMsg);
                    $("#loader").show();
                },
                complete: function () {
                    $("#loader").hide();
                },
                success: function (data) {
                    Lobibox.notify(data["type"], {
                        position: "top right",
                        msg: data["message"],
                    });

                    if (data['discount_amount'] && data['discount_type'] && data['type'] == 'success') {
                        $("#AppliedCouponCode").val('');
                        $('#offerCode').val('');
                        $("#agentCompanyCodeType").val(data['code_type']);
                        $("#agentCompanyCode").val(agentCorpCode);
                        $('#discountAmount').val(data['discount_amount']);
                        $('.discountAmount').html(' ₹' + data['discount_amount']);
                        $('#discountSucccessText').show().addClass('d-flex');
                        $('#discountType').val(data['discount_type']);
                        $('#discountValue').val(data['discount_deduct']);
                        $('#discountSucccessAmount').html(data['discount_deduct'] + ' % ');
                        $('#applyCodeButton').html('Applied').removeClass('grey').addClass('green');
                        $('#basic-addon1').html('Apply').addClass('applyOfferButton grey').removeClass('green');
                    } else {
                        $('#agentCompanyCode1').val('');
                        $('#agentCompanyCode').val('');
                        $('#agentCompanyCodeType').val('');
                    }

                    calculateAmount();
                }
            });
        }

    }

    function calculateDiscountAmount() {
        var property_id = $('#offerCode').attr('data-property-id');
        var offerCode = $('#offerCode').val();
        var property_price = $('#totalAmount').val();
        if (offerCode == '') {
            var sendMsg;
            if ($('#discountAmount').val() > 0) {
                $('#discountAmount').val('');
                $('.discountAmount').html(0);
                $('#discountSucccessText').hide().removeClass('d-flex');
                $('#discountType').val('');
                $('#discountValue').val('');
                $('#discountSucccessAmount').html('');
                $("#AppliedCouponCode").val('');
                calculateAmount();

                // $('#basic-addon1').html('Apply').addClass('applyOfferButton grey').removeClass('green');
                sendMsg = "Offer code removed successfully!";
            }
            else {
                sendMsg = "Please insert offer code!";
            }
            Lobibox.notify('error', {
                position: "top right",
                msg: sendMsg,
            });
            return;
        } else {

            var url = $('#offerCode').attr('data-url');
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    property_id: property_id,
                    property_price: property_price,
                    offerCode: offerCode,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend: function () {
                    var _loaderMsg = $('#basic-addon1').attr("data-loader");
                    $("#loader_msg").html(_loaderMsg);
                    $("#loader").show();
                },
                complete: function () {
                    $("#loader").hide();
                },
                success: function (data) {

                    Lobibox.notify(data["type"], {
                        position: "top right",
                        msg: data["message"],
                    });
                    if (data['discount_amount'] && data['discount_type'] && data['type'] == 'success') {

                        $('#discountSucccessText').show();
                        if (!$('#discountSucccessText').hasClass('d-flex')) {
                            $('#discountSucccessText').addClass('d-flex');
                        }

                        $('#discountAmount').val(data['discount_amount']);
                        $('.discountAmount').html(' ₹' + data['discount_amount']);
                        $('#discountSucccessText').show().addClass('d-flex');
                        $('#discountType').val(data['discount_type']);
                        $('#discountValue').val(data['discount_deduct']);
                        $('#isGlobalOfferApplied').val(data['is_global_coupon']);
                        if (data['discount_type'] == 'Flatrate') {
                            $('#discountSucccessAmount').html(' ₹' + data['discount_deduct']);
                        } else {
                            $('#discountSucccessAmount').html(data['discount_deduct'] + ' % ');
                        }

                        $("#AppliedCouponCode").val(offerCode);
                        $('#basic-addon1').html('Applied').removeClass('applyOfferButton grey').addClass('green');
                    } else {
                        $('#offerCode').val('');
                        $("#AppliedCouponCode").val('');
                        $('#discountSucccessText').hide().removeClass('d-flex');
                    }
                    $('#applyCodeButton').html('Apply').removeClass('green').addClass('grey');
                    $('#agentCompanyCode1').val('');
                    $('#agentCompanyCode').val('');
                    $('#agentCompanyCodeType').val('');
                    calculateAmount();
                }
            });
        }


    }


    function calculateAmount() {
        dateDifference();
        var isGlobalOfferApplied = $('#isGlobalOfferApplied').val();
        var agentCompanyCode = $('#agentCompanyCode').val();
        var selected_room_amount = $('#selectedPerRoomAmount').val();
        $('.selectedPerRoomAmount').html(' ₹' + selected_room_amount);
        var discountAmount = $('#discountAmount').val();
        var discountType = $('#discountType').val();
        var daysDiff = $('#daysDiff').val();
        if (daysDiff == 0) {
            $('.daysDiffernceDiv').hide().removeClass('d-flex');
        } else {
            $('.daysDiffernceDiv').show().addClass('d-flex');
        }
        var total_guests = $('.quantity_guests').val();
        var totalAmount;
        if (isFlatType || isHomestay || isHotel) {
            totalAmount = selected_room_amount;
        } else {
            totalAmount = selected_room_amount * total_guests;
        }
        if (daysDiff >= 1) {
            totalAmount = totalAmount * daysDiff;
        }
        if (discountType != '' && discountType == 'Percentage') {
            var discountValue = $('#discountValue').val();
            discountAmount = totalAmount - (totalAmount * ((100 - discountValue) / 100));
            $('#discountAmount').val(discountAmount);
            $('.discountAmount').html(' ₹' + discountAmount);
        }

        if (discountType != '' && discountType == 'Flatrate' && discountAmount >= totalAmount) {
            discountAmount = 0;
            $('#discountAmount').val(discountAmount);
            $('.discountAmount').html(' ₹' + discountAmount);
            $('#discountSucccessText').hide().removeClass('d-flex');
            $('#discountType').val('');
            $('#discountValue').val('');
        }

        $('.daysDiffernce').html(daysDiff + ' Days');
        $('#totalAmount').val(totalAmount);
        $('.totalAmount').html('₹' + totalAmount);
        if (parseInt(discountAmount) > 0) {
            $('#finalPayableAmount').val(totalAmount - discountAmount);
            $('.finalPayableAmount').html('₹' + (totalAmount - discountAmount));
        } else {
            $('#finalPayableAmount').val(totalAmount);
            $('.finalPayableAmount').html('₹' + totalAmount);
        }
        calculateCommissionAmount();
        amountType();
        var propertyCommissionAmount = $('#propertyCommissionAmount').val();
        if ((totalAmount <= 0) || (isGlobalOfferApplied == 1 && propertyCommissionAmount <= 0) || (agentCompanyCode != '' && propertyCommissionAmount <= 0)) {
            if (isGlobalOfferApplied == 1) {
                sendMsg = 'Offer code removed! Booking partial amount too low.';
            } else if (agentCompanyCode != '') {
                sendMsg = 'Agent/Corporate code discount removed! Booking partial amount too low.';
            } else {
                sendMsg = 'Offer code removed! Booking amount too low.';
            }

            if (isCall == 0 && reloadVal == 0 && discountAmount > 0) {
                removeDiscount(sendMsg);
            }

        }
        if (isCall == 0) {
            reloadVal = 0;
            $('#addPaymentButton').prop('disabled', true);
            $('#pills-bookingpayment-tab').addClass('disabled');
        }
        isCall = 0;


    }

    function amountType() {
        var payment_type = $('input:radio.paidType:checked').val();
        var payAmount;
        if (payment_type == 'partial') {
            payAmount = $('#propertyCommissionAmount').val();
            remainingAmount = $('#totalAmount').val() - payAmount;

        } else {
            payAmount = $('#finalPayableAmount').val();
            $('.remainingAmountDiv').hide().removeClass('d-flex');
        }
        $('.finalAmountAfterSelection').html('₹' + payAmount);
        $('#finalAmountAfterSelection').val(payAmount);
        if (isCall == 0) {
            $('#addPaymentButton').prop('disabled', true);
            $('#pills-bookingpayment-tab').addClass('disabled');
        }
    }

    function getAcNonAcOptionsRadio() {
        var room_id = $('option:selected', '#roomTypeVal').attr("data-id");
        var url = $('#roomTypeVal').attr('data-url');

        $.ajax({
            type: "POST",
            url: url,
            data: {
                room_id: room_id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend: function () {
                $("#loader_msg").html('Please wait getting room data.');
                $("#loader").show();
            },
            complete: function () {
                $("#loader").hide();
            },
            success: function (response) {
                if ($('#RoomTypeAc').is(':checked')) {
                    $('#selectedPerRoomAmount').val(response['ac_amount']);
                }

                if ($('#RoomTypeNonAc').is(':checked')) {
                    $('#selectedPerRoomAmount').val(response['non_ac_amount']);
                }
                if (isOnlyGuestType) {
                    $('.selectedPerRoomAmount').html('₹' + $('#selectedPerRoomAmount').val());
                }

                calculateAmount();
            }
        });

    }

    function getAcNonAcOptions() {
        var room_id = $('option:selected', '#roomTypeVal').attr("data-id");
        if (room_id) {
            var url = $('#roomTypeVal').attr('data-url');
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    room_id: room_id,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend: function () {
                    $("#loader_msg").html('Please wait getting room data.');
                    $("#loader").show();
                },
                complete: function () {
                    $("#loader").hide();
                },
                success: function (response) {
                    $('#roomOccupancyType').val(response['room_type']);
                    $('#radiobuttonRowAcType').show();
                    if (response['is_ac'] != 0 && response['is_non_ac'] != 0) {
                        $('#RoomTypeAcDiv').show();
                        $('#RoomTypeNonAcDiv').show();
                        if (document.getElementById("bookingpageedit")) {
                            var roomSubType = $('input:radio.roomCoolType:checked').val();
                            if (roomSubType == 'AC') {
                                $('#selectedPerRoomAmount').val(response['ac_amount']);

                            } else {
                                $('#selectedPerRoomAmount').val(response['non_ac_amount']);
                            }
                        } else {
                            $('#RoomTypeAc').prop('checked', true);
                            $('#selectedPerRoomAmount').val(response['ac_amount']);
                        }
                    }
                    else if (response['is_ac'] != 0) {
                        $('#RoomTypeAcDiv').show();
                        $('#RoomTypeNonAcDiv').hide();
                        $('#RoomTypeAc').prop('checked', true);
                        $('#RoomTypeNonAc').prop('checked', false);
                        $('#selectedPerRoomAmount').val(response['ac_amount']);
                    }
                    else {
                        $('#RoomTypeNonAcDiv').show();
                        $('#RoomTypeAcDiv').hide();
                        $('#RoomTypeNonAc').prop('checked', true);
                        $('#RoomTypeAc').prop('checked', false);
                        $('#selectedPerRoomAmount').val(response['non_ac_amount']);
                    }
                    if (isOnlyGuestType) {
                        $('.selectedPerRoomAmount').html('₹' + $('#selectedPerRoomAmount').val());
                    }

                    calculateAmount();
                }
            });
        } else {
            $('#selectedPerRoomAmount').val(0);
            $('#radiobuttonRowAcType').hide();
            $('#RoomTypeAcDiv').hide();
            $('#RoomTypeNonAcDiv').hide();
            $('#RoomTypeAc').prop('checked', false);
            $('#RoomTypeNonAc').prop('checked', false);
        }

    }

    function totalGuests() {
        if (isOnlyGuestType) {
            var total_guests = $('.quantity_guests').val();
            var output = total_guests == 1 ? total_guests + ' Guest ' : total_guests + ' Guests ';
        } else {
            var adults = $('.quantity_adult').val() == 1 ? + $('.quantity_adult').val() + ' Adult - ' : + $('.quantity_adult').val() + ' Adults - ';
            var children = $('.quantity_children').val() == 1 ? ' ' + $('.quantity_children').val() + ' Child' : ' ' + $('.quantity_children').val() + ' Children';
            var output = adults + children;
        }
        $('.totalGuests').html(output);

    }

    function calculateCommissionAmount() {
        var totalAmount = $('#totalAmount').val();
        var commission_percent = $('#commPercent').val();
        var commmission = Math.round(totalAmount * (commission_percent / 100));
        var isGlobalOfferApplied = $('#isGlobalOfferApplied').val();
        var discountAmount = $('#discountAmount').val();
        var agentCompanyCode = $('#agentCompanyCode').val();

        if ((isGlobalOfferApplied == 1 && discountAmount > 0) || agentCompanyCode != '') {
            commmission = commmission - discountAmount;
        }

        $('#propertyCommissionAmount').val(commmission);
        $('.propertyCommissionAmountText').html('₹' + commmission);
    }

    /****End Guest Selectors***/
}
