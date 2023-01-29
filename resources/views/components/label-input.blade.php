@props(['name', 'inputAttributes' => ''])

<x-label class="text-base">
    {{ $slot }}
</x-label>
<x-input {{ $attributes->merge() }} :name="$name" />
@error("{{ $name }}")
    <span class="text-xs text-red-500">{{ $message }}</span>
@enderror
