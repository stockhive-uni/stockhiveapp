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
            <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <span>Order History:</span>
                    <!-- if statement here -->
                    @foreach ($orderHistory as $order)
                        <form action="{{route('stock-management.ShowOrderHistory')}}" method="GET">
                            <div>{{$order->id}}</div>
                            <div>{{$order->users->first_name}}</div>
                            <div>{{$order->users->last_name}}</div>
                            <div>Number of Items: {{$order->order_item->count()}}</div>
                            <input type='hidden' name="order" value='{{$order->id}}'></input>
                            <div>{{$order->date_time}}</div> <!-- maybe change this for diffForHumans() command? -->
                            <x-primary-button>Details</x-primary-button>
                        </form>
                    @endforeach
                </div>  
            </div>
        </div>
    </div>
</x-app-layout>
