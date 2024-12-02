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

        @if($overDeliveries->isNotEmpty())
            <table
                class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr>
                        <th class="p-2">Delivery Note ID</th>
                        <th class="p-2">Item ID</th>
                        <th class="p-2">Store ID</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Returned</th>
                        <th class="p-2">Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overDeliveries as $overDelivery)
                        <tr class="hover:bg-stockhive-grey">
                            <td class="py-2 px-4">{{ $overDelivery->deliveryNote->id }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->item->id }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->store->id }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->quantity }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->returned ? 'Yes' : 'No' }}</td>
                            <td class="py-2 px-4">{{ $overDelivery->date_time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-gray-500">No overdeliveries found.</p>
        @endif
        <div class="flex justify-end gap-4 mt-6 lg:w-[90%] w-full m-auto">
            <form action="{{ route('logistics') }}" method="GET">
                @csrf
                <x-primary-button>Back to Dashboard</x-primary-button>
            </form>


            <form action="#" method="GET">
                @csrf
                <x-primary-button>View Completed Orders</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>