<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Machine') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex gap-4 p-6 text-gray-900 dark:text-gray-100 items-center">
                    <img src="{{ asset('images/icon-vending-machine.png') }}" alt="{{ $machine->label }}" class="h-fit hidden sm:block">
                    <div class="grid gap-4 w-full">
                        <h4 class="text-xl font-semibold">{{ $machine->label }}</h4>
                        <iframe class="rounded-lg w-full" src="https://maps.google.com/maps?q={{ $machine->latitude }},{{ $machine->longitude }}&amp;hl=es;z=14&amp;output=embed" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($machine->products as $product)
                    <div class="bg-white dark:bg-gray-800 hover:opacity-80 overflow-hidden shadow-sm sm:rounded-lg p-4  place-self-center w-fit">
                        <a href="{{ route('products.show', ['product' => $product->id]) }}" class="">
                            <img src="{{ asset('images/icon-product.png') }}" alt="Machine 1">
                            <h4 class="text-md font-semibold text-center">{{ $product->id }}</h4>
                            <p class="text-xl text-center">{{ __('R$ 45.00') }}</p>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
</x-app-layout>