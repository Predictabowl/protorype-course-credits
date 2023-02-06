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
                <div  class="border hover:border-black rounded-md inline-flex items-center">
                    <span class="mx-1">{{ $ssd->code }}</span>
                    <span x-show="showEditForm === '{{ $alpineShow }}'">
                        <x-button-icon class="hover:text-red-700">
                            <x-heroicon-m-trash class="h-5 w-5" />
                        </x-button-icon>
                    </span>
                </div>
            @endforeach
            <div class="flex items-center" x-show = "showEditForm === '{{ $alpineShow}}'">
                <x-input type=text class="w-24 h-8" placeholder="ssd"></x-input>
                <x-buttons.confirmation-mini  type="button" class="h-6"/>
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
                <x-buttons.cancel-mini  type="button" @click="showEditForm = 0" />
            </div>
        </div>
    </div>
</div>
