if (document.getElementById("customer_profile") || document.getElementById("my_booking")) {
    $(document).on("click", ".myproperty_modal_review", function () {
        $('#review_id_modal').val('');
        $('input:radio[name=rate_number]').prop('checked', false);
        $('#userReviewContent').val('');
        var property_id = $(this).data('property-id');
        var booking_id = $(this).data('booking-id');
        var url = $(this).attr('data-url');
        $('#property_id_modal').val(property_id);
        $('#booking_id_modal').val(booking_id);
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: {
                'property_id': property_id,
                'booking_id': booking_id,
            },
            beforeSend: function () {
                // $('#loader').show();
            },
            success: (data) => {
                // $('#loader').hide();
                // console.log(data);
                if (data['status_code'] == 205) {
                    $('#reviewProperty').modal('hide');

                    Lobibox.notify(data['type'], {
                        position: "top right",
                        msg: data['message']
                    });
                    // $('#review_id_modal').val(data.content.id);
                    // $('#rate'+data.content.rate_number).prop('checked', true);
                    // $('#userReviewContent').val(data.content.content);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

}
