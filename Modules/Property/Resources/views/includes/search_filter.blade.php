<style type="text/css">
    .col-md-2 {
        width: 14.667%;
    }

    .col-md-1 {
        width: 13.333%;
    }
</style>
<div class="row">
    <div class='col-md-2'>
        <div class="form-group">
            {{ Form::text('property_code', @$_GET['property_code'], ['class'=>'form-control','placeholder'=>'Property Code']) }}
        </div>
    </div>
    <div class='col-md-2'>
        <div class="form-group">
            {{ Form::text('title',@$_GET['title'], ['class'=>'form-control','placeholder'=>'Property Title']) }}
        </div>
    </div>

    <div class='col-md-2'>
        <div class="form-group">
            {{ Form::text('city',@$_GET['city'], ['class'=>'form-control','placeholder'=>'City Name']) }}
        </div>
    </div>
    <div class='col-md-2'>
        <div class="form-group">
            <div class='input-group date' id='datetimepicker6'>
                <input type='text' class="form-control from" name="from" id="srart_date" placeholder="Available From" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class='col-md-2'>
        <div class="form-group">
            <div class='input-group date' id='datetimepicker7'>
                <input type='text' class="form-control to" name="to" id="end_date" placeholder="Available To" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class='col-md-2'>
        <div class="form-group">
            <div class='input-group'>
                {{ Form::select('strid', [''=>'Property Type']+$propertyTypePluck,isset($_REQUEST['strid']) ? $_REQUEST['strid'] : NULL , ['class' => 'form-control','style'=>'width: 167px;']) }}
            </div>
        </div>
    </div>
    <div class='col-md-2 '>
        <div class="form-group">
            <div class='input-group date' id='datetimepicker7'>
                {{ Form::select('order_by', [''=>'Property Status']+Config::get('custom.filter_property_status'),isset($_REQUEST['status']) ? $_REQUEST['status'] : NULL , ['class' => 'form-control']) }}
            </div>
        </div>
    </div>

</div>
{{ Form::hidden('page',@$_GET['page'], []) }}
<div class="row" style="display:flex; justify-content: space-between;float: right;padding-right: 8px;">
    <div class=''>
        <div class="form-group" style="width: 191px;">
            <button href="javascript:;" class="btn btn-success btn-flat search_trigger" onclick="serach();"><i class="fa fa-search"></i> Search</button>
            <button href="javascript:;" class="btn btn-warning btn-flat" onclick="reset();">Reset Filter</button>
        </div>
    </div>
</div>