<div x-data="{ flashMsg: true }"
    x-init="setTimeout(() => flashMsg = false, 10000)"
    x-show="flashMsg"
    class="fixed bottom-3 right-3 bg-red-600 font-semibold border-2 border-red-900
         text-white py-2 px-4 rounded-xl text-sm">
    @foreach($errors as $error)
        <p>{!! $error !!}</p>
    @endforeach
</div>
