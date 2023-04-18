$('#modelContent').delegate('.resetclose','click',function() {
    $("#result").find('select').each(function(idx, sel) {
      $(sel).val($(sel).data('default'));
    });
  });
  function getMetaContentByName(name,content){
     var content = (content==null)?'content':content;
     return document.querySelector("meta[name='"+name+"']").getAttribute(content);
  }
  function validateNumber(event) {
      var key = window.event ? event.keyCode : event.which;
      if (event.keyCode === 8 || event.keyCode === 46) {
          return true;
      } else if ( key < 48 || key > 57 ) {
          return false;
      } else {
          return true;
      }
  };
 function statusAction(data) {
    var value= data.value;
    Lobibox.confirm({
      draggable       : false,  
      closeButton     : false,
      closeOnEsc      : false,
      title: 'Status Confirmation',
      msg: 'Are you sure you, want to '+value+'?',
      callback: function ($this, type, ev) {
        if (type === 'yes'){
          var __ID__ = $(data).data('id');
          var __action = $(data).data('action');
          var __statustype__ = $(data).data('key');
          var token = getMetaContentByName('csrf-token');
               $.ajax(
                 {
                   type: "post",
                   url: __action,
                   data: {_token: token,id: __ID__,status:value,statustype:__statustype__},
                   datatype: "html",
                   beforeSend: function()
                   {
                       $('.ajaxloader').show();
                   }
                 })
               .done(function(result)
                 {
                  serach();
                  if(data['result'] == 'true'){
                      $(".search_trigger").trigger("click")
                    }
                     $('.ajaxloader').hide();
                     if(result.length == 0){
                       $('.ajaxloader').hide();
                         return false;
                     }
                     if(result['status_code'] == 500){
                        $(data).val($(data).data('default'));  
                      return false;
                     }
                     if(result['status_code'] == 200){
                        $(data).attr('data-default', value);
                     }
                      Lobibox.notify(result['type'], {
                          rounded: false,
                          position: "top right",
                          delay: 4000,
                          delayIndicator: true,
                          msg: result['message']
                      });
                      //$("#chngStatus"+__ID__).empty().append(JSON.parse(result['body']));
                 })
               .fail(function(jqXHR, ajaxOptions, thrownError)
               {
                 $('.ajaxloader').hide();
               });  
          }else{
             $(data).val($(data).data('default'));  
             return false;
          }
        }
      });
 }

$(document).ready(function(){
  jQuery('#data_filter').dataTable({
    "paging": false,
    "bInfo":false,
    "searching":false,
      processing: true,
      // "order": [ 1, 'desc' ]
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

/*
var mapEngine = new BravoMapEngine('bravo_results_map',{
  fitBounds:true,
  center:[51.505, -0.09],
  zoom:6,
  disableScripts:true,
  ready: function (engineMap) {	
    if(bravo_map_data.markers){
      engineMap.addMarkers2(bravo_map_data.markers);
    }
  }
});
*/
