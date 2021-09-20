
{{-- Confirmation box --}}
<div x-show="showConfirmationBox" class="fixed inset-0 grid place-content-center w-screen h-screen">
    <div class="max-w-max bg-gray-100 rounded-lg border border-black">
        <div class="text-center mt-2 mx-2 bg-white rounded-lg">
            {{ $slot }}
        </div>
        <div class="flex justify-center gap-4 p-4">
            <x-button type="button" x-on:click="document.getElementById(formId).submit(); showConfirmationBox = false">
                {{ __("Confirm") }}
            </x-button>
            <x-button type="button" x-on:click="showConfirmationBox = false">
                {{ __("Cancel") }}
            </x-button>
        </div>
    </div>
</div>