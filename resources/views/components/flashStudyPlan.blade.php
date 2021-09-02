@if(session()->has("studyPlanFailure"))
    <div x-data="{ flashMsg: true }"
        x-init="setTimeout(() => flashMsg = false, 5000)"
        x-show="flashMsg"
        class="relative font-semibold text-red-600 py-2 text-sm">
        <p>{{ session()->get("studyPlanFailure") }}</p>
    </div>
@endif