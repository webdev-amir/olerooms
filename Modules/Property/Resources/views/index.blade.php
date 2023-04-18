@extends('admin.layouts.master')
@section('title', " Property Managment ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/lightbox.min.css') }}">
<script src="{{ asset('public/js/lightbox-plus-jquery.min.js') }}"></script>
<section class="content-header">
  <h1><i class="{{trans($model.'::menu.font_icon')}} "></i>
    Property Management
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
    <li class="active"> Property Management </li>
  </ol>
</section>
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Manage Property</h3>
      <div class="box-tools pull-right">
      </div>
      <br><br>
      @include('property::includes.search_filter')
    </div>
    <div class="box-body" id="result">
      @include('property::includes.ajax_property_list')
    </div>
  </div>
</section>
@endsection
@section('uniquePageScript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{!! Module::asset('property:js/property.js') !!}"></script>
<script>
  $(document).ready(function() {
    $('body').on('change', '.featured_property', function() {
      var id = $(this).attr('data-id');
      var featured_property = $(this).is(':checked') ? 1 : 0;
      var url = $(this).attr('data-url');
      $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: {
          'id': id,
          'featured_property': featured_property
        },
        beforeSend: function() {
          $('.ajaxloader').show();
        },
        success: (data) => {
          $('.ajaxloader').hide();

          Lobibox.notify(data['type'], {
            position: "top right",
            msg: data['message']
          });
          if (data['type'] == 'error') {
            // return false;
            location.reload();
          }
        },
        error: function(data) {
          Lobibox.notify('error', {
            position: "top right",
            msg: 'Something went wrong'
          });
        }
      });
    });


    $('body').on('click', '.manageAccount', function() {
      $("#globalModel").modal('hide');
      $("#globalModel").empty();
      var modal_url = $(this).attr('data-url');
      $.ajax({
        type: "get",
        url: modal_url,
        data: {},
        datatype: "html",
        beforeSend: function() {
          $('.ajaxloader').show();
        }
      }).done(function(data) {
        $('.ajaxloader').hide();
        if (data.length == 0 || data['type'] == 'error') {
          $('.ajaxloader').hide();
          Lobibox.notify('error', {
            position: "top right",
            msg: data['message']
          });
          $("#globalModel").modal('hide');
          return false;
        } else {
          $("#globalModel").modal('show');
          $("#globalModel").empty().append(JSON.parse(data['body']));
        }
      }).fail(function(jqXHR, ajaxOptions, thrownError) {
        $("#globalModel").modal('hide');
        $('.ajaxloader').hide();
      });
    });


    $(document).on('click', '.changestatus', function() {
      var id = $(this).attr('data-id');
      var status = $(this).attr('data-default');
      var title = $(this).attr('data-title');
      var url = $(this).attr('data-url');
      var reload = $(this).attr('data-reload');
      Lobibox.confirm({
        draggable: false,
        closeButton: false,
        closeOnEsc: false,
        title: title + ' Confirmation',
        msg: 'Are you sure you, want to ' + status + '?',
        callback: function($this, type, ev) {
          if (type === 'yes') {
            $.ajax({
              type: 'POST',
              url: url,
              dataType: 'json',
              data: {
                'id': id,
                'status': status
              },
              beforeSend: function() {
                $('.ajaxloader').show();
              },
              success: (data) => {
                $('.ajaxloader').hide();
                if (data['status']) {
                  Lobibox.notify(data['type'], {
                    position: "top right",
                    msg: data['message']
                  });
                }
                setTimeout(function() {
                  window.location.reload();
                }, 3000);
              },
              error: function(data) {
                $('.ajaxloader').hide();
                console.log(data);
              }
            });
          }
        }
      });
    });
  });
</script>
@endsection