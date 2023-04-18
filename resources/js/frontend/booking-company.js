if (document.getElementById("searchpage") || document.getElementById("property_details") || document.getElementById("BookNowButtonPage")) {

    $(document).on('click', '.bookNowButton', function () {
        var cusRoute = $('.customerRoute' + $(this).attr('data-id')).val();
        var compRoute = $('.companyRoute' + $(this).attr('data-id')).val();
        $('#customerRoute').attr('href', cusRoute).attr('data-id', $(this).attr('data-id')).attr('data-action', $(this).attr('data-action'));
        $('#companyRoute').attr('href', compRoute).attr('data-id', $(this).attr('data-id')).attr('data-action', $(this).attr('data-action'));
    });


    $(document).on('click', '#scheduleNowButton', function () {
        var cusRoute = $('.customerScheduleRoute').val();
        var compRoute = $('.companyScheduleRoute').val();
        $('#scheduleCustomerRoute').attr('data-url', cusRoute).attr('data-id', $(this).attr('data-id'));
        $('#scheduleCompanyRoute').attr('data-url', compRoute).attr('data-id', $(this).attr('data-id'));
    });


    $(document).on('click', '.store-visit-company', function () {
        var property_id = $(this).attr('data-id');
        var url = $(this).attr('data-url');
        $.ajax({
            type: "POST",
            url: url,
            data: { 'property_id': property_id },
            dataType: 'json',
            beforeSend: function () {
                $("#loader_msg").html(_loaderMsg);
                $("#loader").show();
            },
            success: (data) => {
                $("#loader").hide();
                Lobibox.notify(data['type'], {
                    position: "top right",
                    msg: data['message']
                });
                if (data["redirect-url"]) {
                    location.href = data["redirect-url"];
                }

            },
            error: function (data) {
                if (data.responseJSON.message == "Unauthenticated.") {
                    Lobibox.notify('error', {
                        position: "top right",
                        msg: 'Login as company to schedule visit.'
                    });
                    window.location.href = site_url + '/company/login';
                }
                $("#loader").hide();
            }
        });
    })
}



if (document.getElementById("bookingPageCompany")) {

    var propertyType = $('#propertyTypeSlug').val();
    const isRoomType = ['hostel-pg', 'guest-hotel', 'hostel-pg-one-day'].find(element => element == propertyType);
    const isOnlyOneDayType = ['guest-hotel', 'hostel-pg-one-day', 'homestay'].find(element => element == propertyType);
    const isFlatType = propertyType == 'flat';
    const isHomestay = propertyType == 'homestay';
    const isHotel = propertyType == 'guest-hotel';
    var reloadVal = 0;
    var isCall = 1;


    calculateAmount();

    // 1 = 'hostel-pg'
    // 2 = 'flat'
    // 3 = 'guest-hotel'
    // 4 = 'hostel-pg-one-day'
    // 5 = 'homestay'

    var nowDate = new Date();
    var checkindate = $(".check-in-input").val();
    var checkoutdate = $(".check-out-input").val();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
    var checkinDate2;

    $('.check-in-render').daterangepicker({
        singleDatePicker: true,
        startDate: checkindate ? new Date(checkindate) : new Date(),
        autoApply: true,
        disabledPast: true,
        customClass: '',
        widthSingle: 300,
        onlyShowCurrentMonth: true,
        minDate: today,
        opens: bookingCore.rtl ? 'right' : 'left',
        locale: {
            format: "YYYY-MM-DD",
            direction: bookingCore.rtl ? 'rtl' : 'ltr',
            firstDay: daterangepickerLocale.first_day_of_week
        },
    }, function (start, end, label) {

        $(".check-in-input").val(start.format('YYYY-MM-DD'));
        var start_date = $(".check-in-input").val();
        var end_date = $(".check-out-input").val();
        if (start_date > end_date && end_date != '') {
            end_date = $(".check-out-input").val(start_date);
            start_date = $(".check-in-input").val(start_date);
            $('.check-out-render').html('<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>' + start.format(bookingCore.view_end_date_formate));
            $('.check-in-render').html('<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>' + start.format(bookingCore.view_end_date_formate));
        } else {
            $('.check-in-render').html('<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>' + start.format(bookingCore.view_end_date_formate));
        }
        if (isOnlyOneDayType) {
            dateDifference();
            calculateAmount();
        }

    });


    $('.check-out-render').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        startDate: checkoutdate ? new Date(checkoutdate) : new Date(),
        disabledPast: true,
        customClass: '',
        widthSingle: 300,
        onlyShowCurrentMonth: true,
        minDate: checkindate ? new Date(checkindate) : new Date(),
        opens: bookingCore.rtl ? 'right' : 'left',
        locale: {
            format: "YYYY-MM-DD",
            direction: bookingCore.rtl ? 'rtl' : 'ltr',
            firstDay: daterangepickerLocale.first_day_of_week
        },
    }, function (start, end, label) {

        $(".check-out-input").val(end.format('YYYY-MM-DD'));
        var start_date = $(".check-in-input").val();
        var end_date = $(".check-out-input").val();
        if (end_date < start_date) {
            start_date = $(".check-out-input").val(end_date);
            end_date = $(".check-in-input").val(end_date);
            $('.check-out-render').html('<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>' + end.format(bookingCore.view_end_date_formate));
            $('.check-in-render').html('<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>' + end.format(bookingCore.view_end_date_formate));
        } else {
            $('.check-out-render').html('<i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>' + end.format(bookingCore.view_end_date_formate));
        }
        if (isOnlyOneDayType) {
            dateDifference();
            calculateAmount();
        }



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
        calculateAmount();


    });

    $('body').on('click', '.btn-add-guests', function (e) {

        e.stopPropagation();
        var dataClass = $(this).attr('data-input-class');
        var input = $('.' + dataClass + '');
        if (isFlatType) {
            var flatbhk_val = $('.flatbhk').val().replace('bhk', '');
            var max_attrval = parseInt(flatbhk_val * 2);
            $(input).attr('max', max_attrval);
        }

        var max = parseInt(input.attr('max'));
        var old = parseInt(input.val());
        if (old >= max) {
            return;
        }
        input.val(old + 1);
        calculateAmount();


    });


    $('body').on('change', '.room_ac_non_ac_checkbox', function (e) {

        if ($(this).is(':checked')) {
            $('.quantity_guests_' + $(this).data('id') + '_' + $(this).data('type')).addClass('box_selected').attr('required', true).attr('min', 1);

        } else {
            $('.quantity_guests_' + $(this).data('id') + '_' + $(this).data('type')).removeClass('box_selected').val(0).attr('required', false).attr('min', 0).attr('data-total-amount', 0);
        }
        calculateAmount();


    });

    $('body').on('click', '.btn-minus-guests-multi', function (e) {

        e.stopPropagation();
        var dataClass = $(this).attr('data-input-class');
        var input = $('.' + dataClass + '');
        if (input.hasClass('box_selected')) {
            var min = parseInt(input.attr('min'));
            var old = parseInt(input.val());
            if (old <= min) {
                return;
            }
            input.val(old - 1);
            input.attr('data-total-amount', (old - 1) * parseInt(input.attr('data-peramount')));
        }
        calculateAmount();

    });



    $('body').on('click', '.btn-add-guests-multi', function (e) {

        e.stopPropagation();
        var dataClass = $(this).attr('data-input-class');
        var input = $('.' + dataClass + '');
        if (input.hasClass('box_selected')) {
            var max = parseInt(input.attr('max'));
            var old = parseInt(input.val());
            if (old >= max) {
                return;
            }
            input.val(old + 1);
            input.attr('data-total-amount', (old + 1) * parseInt(input.attr('data-peramount')));
        }
        calculateAmount();


    });

    $('#agentCompanyCode1').on('keyup', function () {

        $('#applyCodeButton').html('Apply').removeClass('green').addClass('grey');

    });


    $('#offerCode').on('change', function () {

        calculateDiscountAmount();
        if ($("#AppliedCouponCode").val() == $(this).val() && $(this).val() != '') {
            $('#basic-addon1').html('Applied').removeClass('grey applyOfferButton').addClass('green ');
        } else {
            $('#basic-addon1').html('Apply').addClass('grey applyOfferButton').removeClass('green');
        }


    });


    $(document).on('click', '.applyCodeButton', function () {
        calculateAgentCorpCodeDiscount();
        calculateAmount();
    });

    $('body').on('click', '#removeCoupon', function () {
        sendMsg = "Offer code removed successfully!";
        removeDiscount(sendMsg);

    });

       
    $('.paidType').on('change', function () {
        amountType();
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
                sendMsg = "Please select offer code!";
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
        totalGuests();
        var isGlobalOfferApplied = $('#isGlobalOfferApplied').val();
        var agentCompanyCode = $('#agentCompanyCode').val();
        if (isRoomType) {
            totalAmountCal();
        }
        var totalAmount = $('#totalAmount').val();
        var discountAmount = $('#discountAmount').val();
        var discountType = $('#discountType').val();
        var daysDiff = $('#daysDiff').val();
        if (daysDiff == 0) {
            $('.daysDiffernceDiv').hide().removeClass('d-flex');
        } else {
            $('.daysDiffernceDiv').show().addClass('d-flex');
        }

        if (daysDiff >= 1) {
            totalAmount = totalAmount * daysDiff;
        }
        if (discountType != '' && discountType == 'Percentage') {
            var discountValue = $('#discountValue').val();
            discountAmount = totalAmount - (totalAmount * ((100 - discountValue) / 100));

            $('#discountAmount').val(discountAmount);
            $('.discountAmount').html(' ₹ ' + discountAmount);
        }

        if (discountType != '' && discountType == 'Flatrate' && discountAmount >= totalAmount) {
            discountAmount = 0;
            $('#discountAmount').val(discountAmount);
            $('.discountAmount').html(' ₹ ' + discountAmount);
            $('#discountSucccessText').hide().removeClass('d-flex');
            $('#discountType').val('');
            $('#discountValue').val('');
        }

        $('.daysDiffernce').html(daysDiff + ' Days');
        $('#totalAmount').val(totalAmount);
        $('.totalAmount').html('₹ ' + totalAmount);
        if (parseInt(discountAmount) > 0) {
            $('#finalPayableAmount').val(totalAmount - discountAmount);
            $('.finalPayableAmount').html('₹ ' + (totalAmount - discountAmount));
        } else {
            $('#finalPayableAmount').val(totalAmount);
            $('.finalPayableAmount').html('₹ ' + totalAmount);
        }
        calculateCommissionAmount();
        amountType();
        var propertyCommissionAmount = $('#propertyCommissionAmount').val();
        if ((totalAmount <= 0) || (isGlobalOfferApplied == 1 && propertyCommissionAmount <= 0) || (agentCompanyCode != '' && propertyCommissionAmount <= 0)) {

            if (isGlobalOfferApplied == 1) {
                sendMsg = 'Offer code removed! Booking partial amount too low.';
            } else if (agentCompanyCode != '') {
                sendMsg = 'Agent/Corporate code discount removed! Booking partial amount too low.';
            }
            else {
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

    function totalAmountCal() {

        var sum = 0;
        $(".quantity__input ").each(function () {
            sum += +$(this).attr('data-total-amount');
        });
        $("#totalAmount").val(sum);
    }

    function totalGuests() {

        if (!isRoomType) {
            $(".total_guests").val($('.quantity_guests ').val());
            $('.totalGuests').html($('.quantity_guests ').val());
            return;
        }
        var sum = 0;
        $(".quantity__input ").each(function () {
            sum += +$(this).val();
        });
        $(".total_guests").val(sum);
        // var total_guests = $('.quantity_guests').val();
        var output = sum == 1 ? sum + ' Guest ' : sum + ' Guests ';
        $('.totalGuests').html(output);
    }

    function calculateCommissionAmount() {


        var totalAmount = $('#totalAmount').val();
        var commission_percent = $('#commPercent').val();
        var commmission = Math.round(totalAmount * (commission_percent / 100));
        var isGlobalOfferApplied = $('#isGlobalOfferApplied').val();
        var discountAmount = $('#discountAmount').val();
        if ((isGlobalOfferApplied == 1 && discountAmount > 0) || agentCompanyCode != '') {
            commmission = commmission - discountAmount;
        }

        $('#propertyCommissionAmount').val(commmission);
        $('.propertyCommissionAmountText').html('₹' + commmission);
    }
}