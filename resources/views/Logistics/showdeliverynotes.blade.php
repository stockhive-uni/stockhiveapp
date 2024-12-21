<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Order ID: {{ $order->id }}</h1>
        <p><strong>Order Date/Time:</strong> {{ $order->date_time }}</p>
        <p><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>



        <form action="{{ route('logistics') }}" method="GET">
            <x-primary-button class="mt-4">Back to Dashboard</x-primary-button>
        </form>
    </div>

    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-6">
        <h2 class="text-xl font-bold mb-4">Items in Order</h2>
        <form method="POST" action="{{ route('logistics.createDeliveryNote') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <table
                class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr>
                        <th class="p-2">Item Name</th>
                        <th class="p-2">Ordered Quantity</th>
                        <th class="p-2">Delivered Quantity</th>
                        <th class="p-2">Over Delivered Quantity</th>
                        <th class="p-2">Arrived quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr class="hover:bg-stockhive-grey">
                            <td class="p-2">{{ $item['name'] }}</td>
                            <td class="p-2">{{ $item['ordered'] }}</td>
                            <td class="p-2">{{ $item['delivered'] }}</td>
                            <td class="p-2">{{ $item['over_delivered'] }}</td>
                            <td class="p-2">
                                <input type="number" name="items[{{ $item['id'] }}][quantity]" min="0" value="0"
                                    class="p-2 rounded-lg bg-stockhive-grey-dark text-white border border-accent focus:ring focus:ring-accent w-full">
                                <input type="hidden" name="items[{{ $item['id'] }}][id]" value="{{ $item['id'] }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-end items-center gap-8 my-4 lg:w-[90%] w-full m-auto">
                <x-primary-button class="mt-4">Create Delivery Note</x-primary-button>
                @if (isset($error))
                    <p class="error">{{ $error }}</p>
                @endif
            </div>
        </form>

    </div>

    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-6">
        <h2 class="text-xl font-bold mb-4">Delivery Notes</h2>
        @forelse ($notesWithItems as $note)
            <div class="border border-stockhive-grey-dark rounded-lg p-4 mb-6">
                <h3 class="text-lg font-bold">Delivery Note ID: {{ $note['delivery_note_id'] }}</h3>
                <p><strong>Date:</strong> {{ $note['delivery_note_date'] }}</p>
                <table
                    class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr>
                            <th class="p-2">Item Name</th>
                            <th class="p-2">Delivered Quantity</th>
                            <th class="p-2">Over Delivered Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($note['delivered_items'] as $deliveredItem)
                            <tr class="hover:bg-stockhive-grey">
                                <td class="p-2">{{ $deliveredItem['name'] }}</td>
                                <td class="p-2">{{ $deliveredItem['quantity'] }}</td>
                                <td class="p-2">{{ $deliveredItem['over_delivered'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-white text-center font-semibold py-4">No delivery notes available for this order.</p>
        @endforelse
    </div>

    
</x-app-layout>