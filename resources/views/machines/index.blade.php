<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Machines') }}
        </h2>
    </x-slot>
    <x-middle>
        @foreach ($machines as $machine)
        <div class="bg-white dark:bg-gray-800 hover:opacity-80 overflow-hidden shadow-sm sm:rounded-lg p-4  place-self-center w-fit">
            <a href="{{ route('machines.show', ['machine' => $machine->id]) }}" class="grid gap-2">
                <img src="{{ asset('images/icon-vending-machine.png') }}" alt="{{ $machine->label }}">
                <h4 class="text-md font-semibold text-center">{{ $machine->label }}</h4>
            </a>
        </div>
        @endforeach
    </x-middle>
</x-app-layout>