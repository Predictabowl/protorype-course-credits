@props(["size" => "6"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-blue-800 rounded-2xl
                    hover:text-white hover:bg-blue-700
                    active:text-blue-200 active:bg-blue-800"])}}>
    <x-heroicon-m-plus-circle class="h-{{$size}} w-{{$size}}" />
</button>
