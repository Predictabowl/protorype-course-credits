@props(["size" => "6"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-green-800 rounded-2xl
                    hover:text-white hover:bg-green-700
                    active:text-green-200 active:bg-green-800"])}}>
    <x-heroicon-m-check-circle class="h-{{$size}} w-{{$size}}" />
</button>
