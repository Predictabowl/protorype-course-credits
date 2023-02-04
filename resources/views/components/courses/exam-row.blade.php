<div class="table-row-group" id="{{'exam-row-'.$exam->id}}" x-init="showEditExam = 0">
    <div class="table-row tr-body" x-show="showEditExam != {{$exam->id}}">
        <div class="table-cell italic"> {{ $exam->name}} </div>
        <div class="table-cell text-center">
            @isset($exam->ssd)
                {{$exam->ssd->code}}
            @endisset
        </div>
        <div class="table-cell text-center">
            @if ($exam->free_choice)
                {{__("Yes")}}
            @else
                {{__("No")}}
            @endif
        </div>
        <div class="table-cell w-8" title="{{__("Edit")}}">
            <x-button-icon type="button"
                    @click="showEditExam = (showEditExam != {{$exam->id}})
                        ? showEditExam = {{$exam->id}} : showEditExam = 0">
                <x-heroicon-m-pencil-square class="h-5 w-5"/>
            </x-button-icon>
        </div>
        <div class="table-cell w-8">
            <x-dropdown width="24">
                <x-slot name="trigger">
                    <x-button-icon type="button">
                        <x-heroicon-m-ellipsis-horizontal class="h-5 w-5" />
                    </x-button-icon>
                </x-slot>
                <x-slot name="content">
                    <form data-action="{{ route('examDelete',[$exam]) }}"
                            data-element-id="{{'exam-row-'.$exam->id}}"
                            data-method="DELETE" id="{{ 'form-delete-exam-'.$exam->id }}">
                        @csrf
                        @method("DELETE")
                        <x-dropdown-button onclick="submitForm(event, this)">
                            {{__("Delete")}}
                        </x-dropdown-button>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
    <!-- Hidden row -->
    <form class="table-row" x-show="showEditExam == {{$exam->id}}" style="display: none"
            data-action="{{ route('examUpdate', [$exam]) }}"
            data-element-id="{{'exam-row-'.$exam->id}}"
            data-method="PUT" id="{{ 'form-edit-exam-'.$exam->id }}">
        @csrf
        @method("PUT")
        <div class="table-cell">
            <x-input class="w-full" placeholder="Nome Esame" type="text" name="name"
                value="{{ $exam->name }}" required >
            </x-input>
        </div>
        <div class="table-cell text-center">
            <x-input class="w-32" placeholder="SSD" type="text" name="ssd"
                value="{{ isset($exam->ssd) ? $exam->ssd->code : ''}}">
            </x-input>
        </div>
        <div class="table-cell text-center">
            <input class="rounded-xl shadow-sm border-gray-300
                focus:border-indigo-300 focus:ring focus:ring-indigo-200
                focus:ring-opacity-50"
                type="checkbox" name="freeChoice"
                {{ $exam->free_choice ? "checked" : ""}}>
        </div>
        <div class="table-cell align-middle text-center w-8" title="{{__("Save")}}">
            <x-buttons.confirmation-mini type="submit" onclick="submitForm(event, this)"/>
        </div>
        <div class="table-cell align-middle text-center w-8" title="{{__("Cancel")}}">
            <x-buttons.cancel-mini @click="showEditExam = (showEditExam == 0)
            ? showEditExam = {{$exam->id}} : showEditExam = 0" id="{{'cancel-button-'.$exam->id}}"/>
        </div>
    {{-- </div> --}}
    </form>
</div>

