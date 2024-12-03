<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Overdeliveries') }}
        </h2>
    </x-slot>

    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-6">
        @if(session('success'))
            <div class="text-green-500 font-semibold mb-4">
                {{ session('success') }}
            </div>
        @endif

        <h3 class="text-center font-bold mb-4">List of Overdeliveries</h3>

        <form action="{{ route('logistics.return') }}" method="POST">
            @csrf
            @if($overDeliveries->isNotEmpty())
                <table
                    class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr>
                            <th class="p-2">Select</th>
                            <th class="p-2">Delivery Note ID</th>
                            <th class="p-2">Item Name</th>
                            <th class="p-2">Ordered Quantity</th>
                            <th class="p-2">Delivered Quantity</th>
                            <th class="p-2">Over Delivered Quantity</th>
                            <th class="p-2">Returned</th>
                            <th class="p-2">Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overDeliveries as $overDelivery)
                            <tr class="hover:bg-stockhive-grey">
                                <td class="py-2 px-4">
                                    <input type="checkbox"
                                        name="over_deliveries[{{ $overDelivery->delivery_note_id }}][{{ $overDelivery->item_id }}]"
                                        value="1" @if($overDelivery->returned) checked @endif>
                                </td>
                                <td class="py-2 px-4">{{ $overDelivery->delivery_note_id }}</td>
                                <td class="py-2 px-4">{{ $overDelivery->item->name }}</td>
                                <td class="py-2 px-4">{{ $overDelivery->deliveryNote->order->orderItems->firstWhere('item_id', $overDelivery->item_id)->ordered }}</td>
                                <td class="py-2 px-4">{{ $overDelivery->deliveryNote->deliveredItems->firstWhere('item_id', $overDelivery->item_id)->quantity }}</td>
                                <td class="py-2 px-4">{{ $overDelivery->quantity }}</td>
                                <td class="py-2 px-4">{{ $overDelivery->returned ? 'Yes' : 'No' }}</td>
                                <td class="py-2 px-4">{{ $overDelivery->date_time }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex justify-center mt-4">
                    <x-primary-button type="submit">Mark Selected as Returned</x-primary-button>
                </div>
            @else
                <p class="text-center font-semibold">No overdeliveries found!</p>
            @endif
        </form>
    </div>
</x-app-layout>
