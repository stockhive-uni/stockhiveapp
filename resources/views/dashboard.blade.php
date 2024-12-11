<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-xl"><span class="font-bold">Name:</span> {{Auth::user()->first_name }} {{Auth::user()->last_name}}</p>
                    <p class="text-xl"><span class="font-bold">Email:</span> {{ Auth::user()->email }}</p>
                    <p class="text-xl"><span class="font-bold">Employee ID:</span> {{ Auth::user()->id}}</p>
                </div>    
            </div>

            <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
                <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Order Date</th>
                            <th>Date Time</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <span>Order History:</span>
                        @foreach ($orderHistory as $order)
                        <form action="{{route('stock-management.ShowOrderHistory')}}" method="GET">
                            <td>{{$order->id}}</td>
                            <td>{{$order->users->first_name}}</td>
                            <td>{{$order->users->last_name}}</td>
                            <td>{{$order->order_item->count()}}</td>
                            <input type='hidden' name="order" value='{{$order->id}}'></input>
                            <td>{{$order->date_time}}</td>
                            <td><x-primary-button>Details</x-primary-button></td>
                        </form>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
                
        </div>
    </div>
</x-app-layout>
