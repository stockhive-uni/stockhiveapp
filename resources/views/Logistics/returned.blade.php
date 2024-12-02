<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Returned Items') }}
        </h2>
    </x-slot>

    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-6">
        <h3 class="text-center font-bold mb-4">List of Returned Items</h3>

        @if($returnedItems->isNotEmpty())
            <table
                class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr>
                        <th class="p-2">Delivery Note ID</th>
                        <th class="p-2">Item ID</th>
                        <th class="p-2">Store ID</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returnedItems as $returnedItem)
                        <tr class="hover:bg-stockhive-grey">
                            <td class="py-2 px-4">{{ $returnedItem->deliveryNote->id }}</td>
                            <td class="py-2 px-4">{{ $returnedItem->item->id }}</td>
                            <td class="py-2 px-4">{{ $returnedItem->store->id }}</td>
                            <td class="py-2 px-4">{{ $returnedItem->quantity }}</td>
                            <td class="py-2 px-4">{{ $returnedItem->date_time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-gray-500">No returned items found.</p>
        @endif
    </div>
</x-app-layout>