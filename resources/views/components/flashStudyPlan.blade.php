@if(session()->has("studyPlanFailure"))
    <div x-data="{ flashMsg: true }"
        x-init="setTimeout(() => flashMsg = false, 5000)"
        x-show="flashMsg"
        class="fixed bottom-3 right-3 bg-yellow-100  font-semibold text-red-600 text-lg py-2 px-4 rounded-xl">
        <p>{{ session()->get("studyPlanFailure") }}</p>
    </div>
@endif