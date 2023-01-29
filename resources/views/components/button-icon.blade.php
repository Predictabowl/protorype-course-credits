@props (["width" => "w-full", "height" => "h-full"])

<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-2 py-1 '.$width.' '.$height
    .' border border-transparent rounded-md font-semibold text-navy
    uppercase tracking-widest hover:bg-blue-300 active:bg-blue-400
    focus:outline-none focus:border-gray-900 focus:ring ring-gray-300
    disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
