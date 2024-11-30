<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Logistics Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <div class="flex justify-between items-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
            </a>
        </div>
        <a href="{{ route('logistics.overdelivery') }}" class="btn btn-primary">Go to Overdelivery</a>
</form>


        @if($orders->isNotEmpty())
        <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr>
                        <th class="py-2 px-4">Order ID</th>
                        <th class="py-2 px-4">User ID</th>
                        <th class="py-2 px-4">Store ID</th>
                        <th class="py-2 px-4">Date/Time</th>
                        <th class="py-2 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                        <td class="py-2 px-4">{{ $order->id }}</td>
                        <td class="py-2 px-4">{{ $order->user_id }}</td>
                        <td class="py-2 px-4">{{ $order->store_id }}</td>
                        <td class="py-2 px-4">{{ $order->date_time }}</td>
                        <td class="py-2 px-4">
             <x-primary-button>View Order</x-primary-button>
            </td>
            <td class="py-2 px-4">
            <form action="{{ route('logistics.show',$order->id) }}" method="POST">
                @CSRF
        <x-primary-button>Create Delivery</x-primary-button>
        </form>
             </a>
             </td>
            </tr>
             @endforeach
            </tbody>
            </table>
        @else
            <p class="text-white text-center font-semibold py-4">No orders available.</p>
        @endif
    </div>
</x-app-layout>
