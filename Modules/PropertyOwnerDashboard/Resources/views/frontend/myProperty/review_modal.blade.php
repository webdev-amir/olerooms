<div class="modal fade resetpassword_success" id="reviewProperty" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content relative modal_design p-0">
            <div class="modal-body text-center p-0">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="font24 black medium mb-1 mT30"> Please provide your reply.</h4>
                <div id='loader' style='display: none;'>
                    <img src="{{ asset('images/loader.gif') }}" width='32px' height='32px'>
                </div>
                <div class="reviewProperty_list">
                    <form action="{{route('owner.add.review-user')}}" method="POST" id="F_ReviewReplyVendor">
                        <input type="hidden" name="id" id="review_id_modal" value="">
                        <div class="row">
                            <div class="col-sm-12 col-sm-12 pl-5 pr-5">
                                <div class="form-group ermsg">
                                    <textarea name="reply_content" id="userReviewContent" cols="10" rows="5" class="form-control" placeholder="Your Reply Here!"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 profile-updte-btn">
                                <div class="col-md-6 col-md-6 ml-auto my-3">
                                    <button type="submit" id="ReviewReplyVendor" class="profile-update form-submit directSubmit" data-loader="Submitting your reply">Submit Reply</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('mydashboard::includes.review_modal')