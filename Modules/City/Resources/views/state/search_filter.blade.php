<div class="row">
  <div class='col-sm-2'>
        <div class="form-group">
            {{ Form::text('title',@$_GET['search'], ['class'=>'form-control','placeholder'=>'Search By State Name','autocomplate'=>'off']) }}
        </div>
    </div>
     <div class='col-md-2 '>
          <div class="form-group">
              <div class='input-group date' id='datetimepicker7'>
                  {{ Form::select('order_by', [''=>'Filter By Status']+Config::get('custom.state_search_status'),isset($_REQUEST['status']) ? $_REQUEST['status'] : NULL , ['class' => 'form-control']) }}
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