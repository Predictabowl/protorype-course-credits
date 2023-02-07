<div id="{{ 'exam-block-ssds-' . $examBlock->id }}" class="hover:bg-yellow-50 py-2">
    @php
        $alpineShow = 'examBlockSsd' . $examBlock->id;
    @endphp
    <div class="flex justify-between">
        <div class="flex items-center gap-1 flex-wrap">
            <div>
                Compatibilità:
            </div>
            @foreach ($examBlock->ssds as $ssd)
                <div  class="border hover:border-black rounded-md inline-flex items-center"
                        id="{{'exam-block-ssd-'.$ssd->id}}">
                    <span class="mx-1">{{ $ssd->code }}</span>
                    <div x-show="showEditForm === '{{ $alpineShow }}'">
                        <form data-action="{{ route('delExamBlockSsd',
                                ['examBlockId' => $examBlock->id, 'ssdId' => $ssd->id]) }}"
                            data-element-id="{{ 'exam-block-ssd-' . $ssd->id }}" data-method="DELETE">
                            @csrf
                            @method("DELETE")
                            <x-button-icon class="hover:text-red-700" onclick="submitForm(event, this)">
                                <x-heroicon-m-trash class="h-5 w-5" />
                            </x-button-icon>
                        </form>
                    </div>
                </div>
            @endforeach
            <div x-show = "showEditForm === '{{ $alpineShow}}'">
                <form class="flex items-center"
                        data-action="{{ route("addExamBlockSsd",[$examBlock->id])}}"
                        data-element-id="{{ 'exam-block-ssds-' . $examBlock->id }}" data-method="PUT">
                    @csrf
                    @method("PUT")
                    <x-input type=text class="w-24 h-8" placeholder="ssd" name="ssd" required></x-input>
                    <x-buttons.confirmation-outline  type="submit" onclick="submitForm(event, this)"/>
                </form>
            </div>
        </div>
        <div class="flex items-center">
            {{-- <x-buttons.plus-mini  type="button" @click="showEditForm = (showEditForm !== '{{ $alpineShow }}')
            ? showEditForm = '{{ $alpineShow }}' : showEditForm = 0" /> --}}
            <x-button-icon
                @click="showEditForm = '{{ $alpineShow }}'" x-show="showEditForm !== '{{ $alpineShow}}'">
                <x-heroicon-m-pencil-square class="h-5 w-5" />
            </x-button-icon>
            <div x-show = "showEditForm === '{{ $alpineShow}}'" class="flex  h-min">
                <x-buttons.cancel-outline  type="button" @click="showEditForm = 0" />
            </div>
        </div>
    </div>
</div>
