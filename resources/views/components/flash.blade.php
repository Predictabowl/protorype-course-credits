@if(session()->has("success"))
    <div x-data="{ flashMsg: true }"
        x-init="setTimeout(() => flashMsg = false, 5000)"
        x-show="flashMsg"
        class="fixed bottom-3 right-3 bg-blue-500  text-white py-2 px-4 rounded-xl text-sm">
        <p>{{ session()->get("success") }}</p>
    </div>
@endif