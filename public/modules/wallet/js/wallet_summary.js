$(function() {
    var start = moment().subtract(29, 'days');
    var end = moment();
    function cb(start, end) { 
      $("input[name='from']").val(start.format('YYYY-M-D')); 
      $("input[name='to']").val(end.format('YYYY-M-D')); 
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        serach();
    }
    $('#reportrange').daterangepicker({
        locale: {
            applyLabel: 'Apply & Search'
        },
        startDate: start,
        endDate: end,
        showCustomRangeLabel: true,
        alwaysShowCalendars: true,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);

    function exportreport(start, end) { 
      $("input[name='rfrom']").val(start.format('YYYY-M-D')); 
      $("input[name='rto']").val(end.format('YYYY-M-D')); 
        $('#export_reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        getTransactionReport();
    }
    $('#export_reportrange').daterangepicker({
        locale: {
            applyLabel: 'Download Report'
        },
        startDate: start,
        endDate: end,
        autoApply: false,
        showCustomRangeLabel: true,
        alwaysShowCalendars: true,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, exportreport);
});

function getTransactionReport(){
  var from ='';
  var to ='';
  if($("input[name='rfrom']").val())
  {
    from = $("input[name='rfrom']").val();  
  }     
  if($("input[name='rto']").val())
  {
    to = $("input[name='rto']").val();  
  }
  var  url = _reportDownloadUrl;
  var  _URL_ = url;
  var customURL = "?search=";
  if(from!=''){
    customURL = customURL+"&from="+from;
  }
  if(to!=''){
    customURL = customURL+"&to="+to;
  }
  var _changeUrl = url+customURL;
  location.href = _changeUrl;
}