@props(["placeholder" => "Cerca", "filter" => null])

<div {{ $attributes->merge([ "class" => "relative flex lg:inline-flex items-center bg-gray-100 rounded-xl px-3 py-2"])}}>
    <form method="GET" action="#">
        @if(isset($filter) && request($filter))
            <input type="hidden" name="{{$filter}}" value="{{request($filter)}}">
        @endif
        <input type="text" name="search" placeholder="{{ $placeholder }}"
            class="bg-transparent placeholder-black font-semibold text-sm"
            value="{{ request("search") }}">
    </form>
</div>