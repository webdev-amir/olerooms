<div class="panel-body">

    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('faq::menu.sidebar.form.question')}} <span class="asterisk">*</span></label>
        <div class="col-sm-10 ">
            <span class="ermsg">
            {{ Form::text('question',null, ['required','class'=>'form-control','id'=>'question','placeholder'=>trans('menu.placeholder.question'),'title'=>trans('menu.validiation.please_enter_question')]) }}
            </span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans('faq::menu.sidebar.form.answer')}}<span class="asterisk">*</span></label>
        <div class="col-sm-10 ">
            <span class="ermsg"> 
                {{ Form::textarea("answer", null, ['required','class' => 'form-control', 'id'=>'answer','rows'=>'10', 'title'=>trans('menu.validiation.please_enter_answer'),'placeholder'=>trans('menu.placeholder.answer')]) }}
            </span>
        </div>
    </div>
</div>
