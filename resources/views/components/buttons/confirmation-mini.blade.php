@props(["size" => "6"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-green-800 hover:text-green-600 active:text-green-300 rounded-2xl
                    active:bg-green-800"])}}>
    <x-heroicon-m-check-circle class="h-{{$size}} w-{{$size}}" />
</button>
