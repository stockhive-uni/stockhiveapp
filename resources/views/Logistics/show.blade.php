<x-app-layout>
    <h1 class="text-2xl font-bold mb-4">Order Details (ID: {{ $order->id }})</h1>

    <div class="mb-6">
        <p><strong>User ID:</strong> {{ $order->user_id }}</p>
        <p><strong>Store ID:</strong> {{ $order->store_id }}</p>
        <p><strong>Date/Time:</strong> {{ $order->date_time }}</p>
    </div>

    <h2 class="text-xl font-bold mb-4">Ordered Items</h2>
    @if ($items->isEmpty())
        <p>No items found for this order.</p>
    @else
        <table class="table-auto border-collapse w-full">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Item ID</th>
                    <th class="border px-4 py-2">Quantity Ordered</th>
                    <th class="border px-4 py-2">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item->item_id }}</td>
                        <td class="border px-4 py-2">{{ $item->ordered }}</td>
                        <td class="border px-4 py-2">${{ $item->price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-app-layout>
