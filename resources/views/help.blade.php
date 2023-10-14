<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ url('images/logo/fsc.png') }}" />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

    </x-authentication-card>
</x-guest-layout>