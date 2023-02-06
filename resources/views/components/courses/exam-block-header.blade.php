<div id="{{ 'exam-block-header-' . $examBlock->id }}" x-init="showEditForm = 0">
    @php
        $alpineShow = "examBlock".$examBlock->id;
    @endphp
    <div x-show="showEditForm !== '{{$alpineShow}}' " class="hover:bg-green-50">
        <div class="md:flex items-center">
            <div class="flex-auto">CFU: {{ $examBlock->cfu }}</div>
            <div class="flex-auto">Numero di Esami sceglibili: {{ $examBlock->max_exams }}</div>
            <div class="flex-auto">Anno di Corso: {{ $examBlock->courseYear }}</div>
            <div class="flex-initial" title="{{ __('Edit') }}">
                <x-button-icon type="button"
                    @click="showEditForm = (showEditForm !== '{{$alpineShow}}')
                    ? showEditForm = '{{$alpineShow}}' : showEditForm = 0">
                    <x-heroicon-o-pencil-square class="h-7 w-7" />
                </x-button-icon>
            </div>
            <div class="flex-initial">
                <x-dropdown width="24">
                    <x-slot name="trigger">
                        <x-button-icon type="button">
                            <x-heroicon-o-ellipsis-horizontal class="h-7 w-7" />
                        </x-button-icon>
                    </x-slot>
                    <x-slot name="content">
                        <form data-action="{{ route('examBlockDelete', [$examBlock]) }}"
                            data-element-id="{{ 'exam-block-row-' . $examBlock->id }}" data-method="DELETE"">
                            @csrf
                            @method('DELETE')
                            <x-dropdown-button onclick="submitForm(event, this)">
                                {{ __('Delete') }}
                            </x-dropdown-button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
    <form x-show="showEditForm === '{{$alpineShow}}'" style="display: none"
            data-method="PUT"
            data-action="{{ route('examBlockUpdate', [$examBlock]) }}"
            data-element-id="{{ 'exam-block-header-'.$examBlock->id }}">
        @csrf
        @method("PUT")
        <div class="table w-full text-center">
            <div class="table-row">
                <div class="table-cell">CFU</div>
                <div class="table-cell">N. esami sceglibili</div>
                <div class="table-cell">Anno di Corso</div>
            </div>
            <div class="table-row">
                <div class="table-cell">
                    <x-input class="w-24" type="number" name="cfu" value="{{ $examBlock->cfu }}" required>
                    </x-input>
                </div>
                <div class="table-cell">
                    <x-input class="w-24" type="number" name="maxExams" value="{{ $examBlock->max_exams }}" required>
                    </x-input>
                </div>
                <div class="table-cell">
                    <x-input class="w-24" type="number" name="courseYear" value="{{ $examBlock->courseYear }}">
                    </x-input>
                </div>
                <div class="table-cell align-middle w-10">
                    <x-buttons.confirmation-outline type="submit" onclick="submitForm(event,this)" />
                </div>
                <div class="table-cell align-middle w-10">
                    <x-buttons.cancel-outline @click="showEditForm = 0" />
                </div>
            </div>
        </div>
    </form>
</div>
