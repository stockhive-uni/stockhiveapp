<x-app-layout>
    <h1 class="text-2xl font-bold mb-4">Logistics Orders</h1>
    @if ($orders->isEmpty())
        <p>No orders found.</p>
    @else
        <table class="table-auto border-collapse w-full">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Order ID</th>
                    <th class="border px-4 py-2">User ID</th>
                    <th class="border px-4 py-2">Store ID</th>
                    <th class="border px-4 py-2">Date/Time</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td class="border px-4 py-2">{{ $order->id }}</td>
                        <td class="border px-4 py-2">{{ $order->user_id }}</td>
                        <td class="border px-4 py-2">{{ $order->store_id }}</td>
                        <td class="border px-4 py-2">{{ $order->date_time }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('logistics.show', $order->id) }}" class="text-blue-500 hover:underline">View Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-app-layout>
