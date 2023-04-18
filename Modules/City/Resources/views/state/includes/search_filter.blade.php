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
          {{ Form::text('name',@$GET['name'], ['class'=>'form-control','placeholder'=>'City Name']) }}
        </div>
    </div>
    <div class="col-md-8">
        <div class="row" style="display:flex; justify-content: space-between;float: right;padding-right: 8px;">
            <div class=''>
                    <div class="form-group" style="width: 191px;">
                      <button href="javascript:;" class="btn btn-success btn-flat search_trigger" onclick="serach();"><i class="fa fa-search"></i> Search</button>
                     <button href="javascript:;" class="btn btn-warning btn-flat" onclick="reset();">Reset Filter</button>
                    </div>
              </div>
          </div>
    </div>
</div>