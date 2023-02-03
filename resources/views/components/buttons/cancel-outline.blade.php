@props(["size" => "8"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-red-800 rounded-3xl
                    hover:text-white hover:bg-red-800
                    active:text-red-300 active:bg-red-800"])}}>
    <x-heroicon-o-x-circle class="h-{{$size}} w-{{$size}}" />
</button>
