<div class="border-2 border-orange-300 rounded-xl p-3 my-2"
        id="{{'exam-block-row-'.$examBlock->id}}">
   <x-courses.exam-block-header :examBlock="$examBlock"/>
    <div class="mt-2">
        <div class="table w-full">
            <div class="table-row tr-head font-bold text-center">
                <div class="table-cell">Nome Esame</div>
                <div class="table-cell">SSD</div>
                <div class="table-cell">A scelta</div>
            </div>
            @foreach ($examBlock->exams as $exam)
                <x-courses.exam-row :exam="$exam"/>
            @endforeach
            <meta class="insert-point">
            <!-- Add New Exam -->
            <form class="table-row" x-show="showNewExam == {{ $examBlock->id }}" style="display: none"
                    data-action="{{ route('examCreate', [$examBlock]) }}"
                    data-method="POST"
                    id="{{ 'form-new-exam-'.$examBlock->id }}">
                @csrf
                <div class="table-cell">
                    <x-input class="w-full" placeholder="Nome Esame" type="text" name="name"
                        value="{{ old('nomeEsame') }}" required>
                    </x-input>
                </div>
                <div class="table-cell text-center">
                    <x-input class="w-32" placeholder="SSD" type="text" name="ssd"
                        value="{{ old('ssd') }}">
                    </x-input>
                </div>
                <div class="table-cell text-center">
                    <input name="freeChoice"
                        class="rounded-xl shadow-sm border-gray-300
                                focus:border-indigo-300 focus:ring focus:ring-indigo-200
                                focus:ring-opacity-50"
                        type="checkbox" {{ old('freeChoice') ? 'checked' : '' }}>
                </div>
                <div class="table-cell align-middle text-center w-8" title="{{ __('Save') }}"">
                    <x-buttons.confirmation-mini type="submit" onclick="submitForm(event,this)"/>
                </div>
                <div class="table-cell align-middle text-center w-8" title="{{ __('Cancel') }}">
                    <x-buttons.cancel-mini
                        @click="showNewExam = (showNewExam == 0)
                        ? showNewExam = {{ $examBlock->id }} : showNewExam = 0" />
                </div>
            </form>
            <div class="table-row" x-show="showNewExam != {{ $examBlock->id }}">
                <x-buttons.plus-mini size="7"
                    @click="showNewExam = (showNewExam !=
                        {{ $examBlock->id }} ? showNewExam = {{ $examBlock->id }} : showNewExam = 0)" />
            </div>
        </div>
    </div>
</div>
