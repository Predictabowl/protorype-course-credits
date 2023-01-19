@props(['max_height' => 85, 'aria_label' => ''])

<div class="flex flex-col" style="max-height: {{ $max_height }}vh;">
    <div class="overflow-y-scroll">
        <table class="min-w-full rounded-lg" :aria-label="$aria_label">
            <thead class="sticky-top">
                {{ $header }}
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
