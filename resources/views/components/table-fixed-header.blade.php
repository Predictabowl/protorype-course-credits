@props(['max_height' => '85vh', 'aria_label' => ''])

<div class="flex flex-col" style="max-height: {{ $max_height }};">
    <div class="overflow-y-scroll">
        <table class="min-w-full rounded-lg" aria-label="{{ $aria_label }}">
            <thead class="sticky-top">
                <tr class="tr-head">
                    {{ $header }}
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
