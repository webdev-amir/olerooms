<div class="modal fade resetpassword_success" id="reviewProperty" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content relative modal_design p-0">
            <div class="modal-body text-center p-0">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="font24 black medium mb-1 mT30"> Please provide your feedback.</h4>
                <div id='loader' style='display: none;'>
                    <img src="{{ asset('images/loader.gif') }}" width='32px' height='32px'>
                </div>
                <div class="reviewProperty_list">
                    <form action="{{route('customer.add.review-user')}}" method="POST" id="F_AddReview">
                        <div class="row">
                            <div class="col-sm-12 col-sm-12 pl-5 pr-5">
                                <div class="form-group ermsg">

                                    <div class="rating">
                                        <input type="radio" name="rate_number" value="5" id="rate5" required><label for="rate5">☆</label>
                                        <input type="radio" name="rate_number" value="4" id="rate4" required><label for="rate4">☆</label>
                                        <input type="radio" name="rate_number" value="3" id="rate3" required><label for="rate3">☆</label>
                                        <input type="radio" name="rate_number" value="2" id="rate2" required><label for="rate2">☆</label>
                                        <input type="radio" name="rate_number" value="1" id="rate1" required><label for="rate1">☆</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="review_id_modal" value="">
                        <input type="hidden" name="object_id" id="property_id_modal" value="">
                        <input type="hidden" name="booking_id" id="booking_id_modal" value="">
                        <div class="row">
                            <div class="col-sm-12 col-sm-12 pl-5 pr-5">
                                <div class="form-group ermsg">
                                    <textarea name="content" id="userReviewContent" cols="10" rows="5" class="form-control" placeholder="Your Comment Here!"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 profile-updte-btn">
                                <div class="col-md-6 col-md-6 ml-auto my-3">
                                    <button type="submit" id="AddReview" class="profile-update form-submit directSubmit" data-loader="Submitting your review ">Submit Review</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>