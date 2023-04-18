$('#modelContent').delegate('.resetclose','click',function() {
  $("#result").find('select').each(function(idx, sel) {
    $(sel).val($(sel).data('default'));
  });
});
function getMetaContentByName(name,content){
   var content = (content==null)?'content':content;
   return document.querySelector("meta[name='"+name+"']").getAttribute(content);
}
function statusAction(data) {
  var value = data.value;
  var redeemid = $(data).data('id');
  var _action_ = $(data).data('action');
  if(value !='pending'){
    var data = new FormData();
    data.append("_token", getMetaContentByName('csrf-token'));
    data.append("redeemid",  redeemid);
    data.append("status",  value);
    $.ajax(
       {
        type: "post",
        url: _action_,
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        datatype: "html",
         beforeSend: function()
         {
            $('.ajaxloader').show();
         }
       })
     .done(function(result)
       {
           $('.ajaxloader').hide();
           $("#globalModel").modal({
             backdrop: 'static',
             keyboard: false
           });
           $("#modelContent").empty().append(result);
       })
     .fail(function(jqXHR, ajaxOptions, thrownError)
     {
       $('.ajaxloader').hide();
     });  
  }
}

jQuery(document).ready(function() {
    jQuery('#data_filter').dataTable({
      "paging": false,
      "bInfo":false,
      "searching":false,
        processing: true,
        "order": [ 1, 'desc' ]
    });
    // Date Picker
    $('#srart_date').datepicker({
       format: 'yyyy-mm-dd',
       onSelect: function (date) {
        var date2 = $('#srart_date').datepicker('getDate');

        date2.setDate(date2.getDate() + 1);
        $('#end_date').datepicker('setDate', date2);
        //sets minDate to dt1 date + 1
        $('#end_date').datepicker('option', 'minDate', date2);
      }
    });
    $('#end_date').datepicker({
      format: 'yyyy-mm-dd',
      onClose: function () {
        var dt1 = $('#srart_date').datepicker('getDate');
        var dt2 = $('#end_date').datepicker('getDate');
        if (dt2 <= dt1) {
          var minDate = $('#end_date').datepicker('option', 'minDate');
          $('#end_date').datepicker('setDate', minDate);
        }
      }   
    });
});