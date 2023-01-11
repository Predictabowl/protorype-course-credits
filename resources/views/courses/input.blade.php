<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{__("Courses List")}}
        </h2>
    </x-slot>
    <?php
        if(isset($course)){
            $update = true;
            $action = route("courseUpdate",[$course->id]);
            $name = $course->name;
            $cfu = $course->cfu;
            $maxCfu = $course->maxRecognizedCfu;
            $otherAct = $course->otherActivitiesCfu;
            $finalCfu = $course->finalExamCfu;
            $numOfYear = $course->numberOfYears;
            $cfuThreshOld = $course->cfuTresholdForYear;
        } else {
            $update = false;
            $action = route("courseCreate");
            $name = old("name");
            $cfu = old("cfu");
            $maxCfu = old("maxRecognizedCfu");
            $otherAct = old("otherActivitiesCfu");
            $finalCfu = old("finalExamCfu");
            $numOYear = old("numberOfYears");
            $cfuThreshold = old("cfuTresholdForYear");
        }
    ?>

    <x-mainpanel>

        <x-panel>
            <div class="place-content-center">
                <form method="POST" action="{{$action}}">
                    @csrf
                    @if ($update)
                        @method("PUT")
                    @endif
                    <header class="flex justify-center">
                        <h2 class="ml-3 text-lg">{{ __("Course Data") }}</h2>
                    </header>
                    <div class="mt-1">
                        <x-input type="text" class="w-full" name="name" :placeholder="__('Course Name')"
                            value="{{$name}}" required />

                        @error("name")
                        <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-1">
                        <x-input type="number" class="w-full" name="cfu" placeholder="cfu"
                            value="{{$cfu}}" required />

                        @error("cfu")
                        <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex mt-1">
                        <x-input type="number" name="maxRecognizedCfu" :placeholder="__('Course Name')"
                            value="{{$maxCfu}}" required />

                        @error("maxRecognizedCfu")
                        <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <x-button>
                            {{ __("Ok") }}
                        </x-button>
                        <x-flash/>
                    </div>
                </form>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
