@props(["size" => "6"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-blue-800 hover:text-blue-600 active:text-blue-300 rounded-2xl
                    active:bg-blue-800"])}}>
    <x-heroicon-m-plus-circle class="h-{{$size}} w-{{$size}}" />
</button>
