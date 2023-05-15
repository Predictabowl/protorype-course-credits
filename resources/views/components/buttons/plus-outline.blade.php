@props(["size" => "10"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-blue-800 rounded-3xl
                    active:text-white active:bg-blue-800
                    hover:text-blue-500"])}}>
    <x-heroicon-o-plus-circle class="h-{{$size}} w-{{$size}}" />
</button>
