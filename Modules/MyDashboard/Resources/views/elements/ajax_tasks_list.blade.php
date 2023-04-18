<p class="mb-3 fz24 bold nunito blue">
    <span class="black">Task :</span> <span id="task_name_get"></span>
</p>
<div class="radioDesign_checkmark">
    @forelse($tasks as $taskList)
    <div class="form-check p-0 mB20 startTask">
        <input class="form-check-input" type="radio" name="task" id="task_{{$taskList->id}}" data-title="{{ $taskList->name }}">
        <label class="form-check-label nunito grey fz20 semiBold" for="task_{{$taskList->id}}">
            <span> {{ $taskList->name }}</span>
            @if((isset($taskList->userTaskProgress) && $taskList->userTaskProgress->status=='inprogress'))
            <button class="btnCustom-primary d-flex">
                <a href="{{route('auth.progress', [$taskList->slug,$taskList->activeCheckpoints[$taskList->userTaskProgress->checkpoint_counts == 0 ?$taskList->userTaskProgress->checkpoint_counts :$taskList->userTaskProgress->checkpoint_counts-1]->slug,'instructions'])}}" class="disable-click">
                    Resume
                    <svg class="mL10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z" />
                    </svg>
                </a>
            </button>
            @elseif($taskList->userTaskProgressResume)
            <button class="btnCustom-primary d-flex">
                <a href="{{route('auth.progress', [$taskList->slug,$taskList->activeCheckpoints[$taskList->userTaskProgressResume->checkpoint_counts==0?$taskList->userTaskProgressResume->checkpoint_counts:$taskList->userTaskProgressResume->checkpoint_counts-1]->slug,'instructions'])}}" class="disable-click">
                    Resume
                    <svg class="mL10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z" />
                    </svg>
                </a>
            </button>

            @else
            <button class="btnCustom-primary d-flex">
                <a href="{{route('auth.progress', [$taskList->slug,$taskList->activeCheckpoints[0]->slug,'instructions'])}}" class="disable-click">
                    Start
                    <svg class="mL10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z" />
                    </svg>
                </a>
            </button>
            @endif

        </label>
    </div>
    @empty
    <div class="form-check p-0 mB20">
        <label class="form-check-label nunito grey fz20 semiBold" for="task2">
            <span> No Tasks Found</span>
        </label>
    </div>
    @endforelse
    <div class="custom_pagination frontpaginate mT30 pull-right">
        {!! $tasks->links('front_dash_pagination') !!}
    </div>
</div>