<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-center text-white leading-tight">
            {{ __('Returned Over Deliveries') }}
        </h1>
    </x-slot>

    <div class="bg-stockhive-grey-dark text-white shadow-sm md:rounded-lg mt-8 lg:w-[85%] w-full m-auto p-6">
        <form action="{{ route('logistics') }}" method="GET" class="inline-block">
            <x-primary-button class="mb-4">Back to Dashboard</x-primary-button>
        </form>
        <form action="{{ route('logistics.overdelivery') }}" method="GET" class="inline-block">
            <x-primary-button class="mb-4">Go to Overdeliveries</x-primary-button>
        </form>

        <h3 class="text-center font-bold mb-4">List of Returned Over Deliveries</h3>

        @if($returnedOverDeliveries->isNotEmpty())
        <div class="overflow-x-auto w-full">
            <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr>
                        <th class="p-2">Delivery Note ID</th>
                        <th class="p-2">Item Name</th>
                        <th class="p-2">Over Delivered Quantity</th>
                        <th class="p-2">Returned</th>
                        <th class="p-2">Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returnedOverDeliveries as $overDelivery)
                        <tr class="hover:bg-stockhive-grey">
                            <td class="py-2 px-4">{{ $overDelivery->deliveryNote->id }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->item->name }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->quantity }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->returned ? 'Yes' : 'No' }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->date_time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @else
            <p class="text-center font-semibold">No returned overdeliveries found!</p>
        @endif
    </div>
</x-app-layout>
//https://laravel-news.com/laravel-optional-helper