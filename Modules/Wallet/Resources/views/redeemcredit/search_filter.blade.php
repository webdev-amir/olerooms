<div class="row">
    <div class='col-md-2'>
      <div class="form-group">
          <div class='input-group date' id='datetimepicker6'>
              <input type='text' class="form-control from" name="from" id="srart_date"  placeholder="From" />
              <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
      </div>
    </div>
    <div class='col-md-2'>
        <div class="form-group">
            <div class='input-group date' id='datetimepicker7'>
                <input type='text' class="form-control to" name="to" id="end_date"  placeholder="To"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
     <div class='col-md-2 '>
          <div class="form-group">
              <div class='input-group date' id='datetimepicker7'>
                  {{ Form::select('order_by', [''=>'All Requests']+Config::get('custom.redeem_credit_request_status'),isset($_REQUEST['status']) ? $_REQUEST['status'] : NULL , ['class' => 'form-control']) }}
              </div>
          </div>
     </div>
    <div class='col-md-4'>
          <div class="form-group">
            <button href="javascript:;" class="btn btn-success btn-flat search_trigger" onclick="serach();"><i class="fa fa-search"></i> Search</button>
           <button href="javascript:;" class="btn btn-warning btn-flat" onclick="reset();">Reset Filter</button>
          </div>
    </div>
</div>