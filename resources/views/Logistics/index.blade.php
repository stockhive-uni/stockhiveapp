<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Logistics Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        @if($orders->isNotEmpty())
            <table
                class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr>
                        <th class="py-2 px-4">Order ID</th>
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Date/Time</th>
                        <th class="py-2 px-4">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td class="py-2 px-4">{{ $order->id }}</td>
                            <td class="py-2 px-4">{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                            <td class="py-2 px-4">{{ $order->date_time }}</td>
                            <td class="py-2 px-4 flex justify-center items-center gap-4">
                                <form action="{{route('stock-management.ShowOrderHistory')}}" method="GET">
                                    @csrf
                                    <input type='hidden' name="order" value='{{$order->id}}'></input>
                                    <x-primary-button>View Order</x-primary-button>
                                </form>
                                <form action="{{ route('logistics.show', $order->id) }}" method="GET" class="inline-block">
                                    @csrf
                                    <x-primary-button>Create delivery Note</x-primary-button>
                                </form>
                            </td>
                            <form action="{{ route('logistics.overdelivery') }}" method="GET" class="inline-block">
                                @csrf
                                <x-primary-button>Overdeliveries</x-primary-button>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-white text-center font-semibold py-4">No orders available.</p>
        @endif
    </div>
</x-app-layout>