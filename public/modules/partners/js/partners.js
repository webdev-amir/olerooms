//Function to insert filename in fake upload box
function after_logo_select(id) {
     var uploadedFile = jQuery('#'+id)[0].files[0];
     jQuery('#logo-duplicate').val(uploadedFile.name);
     jQuery('#logo_popover_'+id+ ' #logo_popover_content').html('<img class="img-thumbnail tool-img" alt="" width="304" height="236" id="logo_popover_img_'+id+'" >');
     document.getElementById('logo_popover_img_'+id).src = URL.createObjectURL(uploadedFile);
     jQuery('#logo_popover_'+id).removeClass('disabled disabled_advanced');
};

jQuery(document).ready(function(){
   jQuery('#toggle_popover_mediaId').popover({
         html:true,
         title: 'Partners Image',
         container: 'body',
         placement: 'top',
         trigger: 'click',
         content: function(){
             return $('#logo_popover_mediaId').html();
         }
     }).click(function(){
         jQuery(this).children('i').toggleClass('fa-eye fa-eye-slash');
     }); 
});