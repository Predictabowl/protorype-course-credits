@props(["size" => "6"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-red-800 rounded-2xl
                    hover:text-white hover:bg-red-700
                    active:text-red-200 active:bg-red-800"])}}>
    <x-heroicon-m-x-circle class="h-{{$size}} w-{{$size}}" />
</button>
