<div class="modal agent_code_modal show" tabindex="-1" role="dialog" id="agentcodeModal">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <div class="iconwrap"><img src="{{URL::to('images/star-gradient.svg')}}" alt="img not found" /></div>
            <p class="mb-0">Your OLE Rooms agent code is </p>
            <span class="codeNum">#{{strtoupper(auth()->user()->agent_code)}}</span>
         </div>
      </div>
   </div>
</div>