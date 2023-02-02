@props(["size" => "6"])
<button {{$attributes->merge([
        "type" => "button",
        "class" => "text-red-800 hover:text-red-600 active:text-red-300 rounded-2xl active:bg-red-800"])}}>
    <x-heroicon-m-x-circle class="h-{{$size}} w-{{$size}}" />
</button>
