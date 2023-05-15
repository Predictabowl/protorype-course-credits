@props(["size" => "8"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-green-800 rounded-3xl
                    hover:text-white hover:bg-green-800
                    active:text-green-300 active:bg-green-800"])}}>
    <x-heroicon-o-check-circle class="h-{{$size}} w-{{$size}}" />
</button>
