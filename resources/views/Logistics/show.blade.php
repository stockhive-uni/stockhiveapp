<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Order ID: {{ $order->id }}</h1>

        <p><strong>User ID:</strong> {{ $order->user_id }}</p>
        <p><strong>Store ID:</strong> {{ $order->store_id }}</p>
        <p><strong>Order Date/Time:</strong> {{ $order->date_time }}</p>

        <h2 class="text-xl font-bold mt-6 mb-4">Items in Order</h2>
        <form method="POST" action="{{ route('logistics.createDeliveryNote', $order->id) }}">
    @csrf
    <!-- Form contents -->
    <x-primary-button>Create Delivery Note</x-primary-button>
</form>

            <div class="mb-4">
                <label for="date_time" class="block text-white font-bold mb-2">Delivery Date/Time</label>
<!-- Before 
                <input type="datetime-local" name="date_time" id="date_time" class="w-full p-2 rounded-lg bg-gray-700 text-white" required>
             After (Remove the entire input field) -->

            </div>


            <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Ordered Quantity</th>
                        <th>Delivered Quantity</th>
                        <th>Quantity Left</th>
                        <th>Quantity to Deliver</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['id'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['ordered'] }}</td>
                            <td>{{ $item['delivered'] }}</td>
                            <td>{{ $item['quantity_left'] }}</td>
                            <td>
                                <input type="number" name="items[{{ $item['id'] }}][quantity]" min="0" max="{{ $item['quantity_left'] }}" value="0" class="w-full p-2 rounded-lg bg-gray-700 text-black">
                                <input type="hidden" name="items[{{ $item['id'] }}][id]" value="{{ $item['id'] }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

    </div>
</x-app-layout>



