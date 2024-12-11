<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-center py-4 text-gray-800 leading-tight">
            Order Overview
        </h1>
    </x-slot>
        <form action="{{ route('stock-management.store') }}" method="POST">
            <div class="lg:p-8 md:p-4 p-2 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto text-white">
            <h2 class="text-2xl text-center text-white">Items:</h2>
                @csrf
                @forelse($items as $collection)
                    <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                        @php $iteration = 0; @endphp
                        @forelse($collection as $item)
                            <div class="p-4">
                                <p class="text-lg font-semibold">ID: {{$item->id}}</p>
                                <p class="text-lg">Name: {{$item->name}}</p>
                                <p class="text-lg">Price: £{{$item->price}}</p>
                                <p class="text-lg">Department: {{$item->department->name}}</p>
                                <input type='hidden' name='ItemQty[{{$item->id}}]' value='{{$ItemQty[$iteration]}}'>
                                <input type='hidden' name='checkbox[]' value='{{$item->id}}'>
                                <p class="text-sm text-gray-400">Quantity: {{$ItemQty[$iteration]}}</p>
                                @php $iteration++; @endphp
                            </div>
                        @empty
                            <p class="text-lg text-center text-gray-400">No items found.</p>
                        @endforelse
                    </div>
                @empty
                    <p class="text-lg text-center text-gray-400">No collections found.</p>
                @endforelse
            </div>
            <div class="p-8 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto text-white">
                <h2 class="text-2xl text-white text-center">Summary</h2>
                <p class="text-lg">Total Price: £{{$totalPrice}}</p>
                <p class="text-lg">Delivery Date: {{$deliveryDate}}</p>
                <p class="text-lg">Total Items: {{$totalItems}} Items</p>
                <div class="flex justify-center mt-8">
                    <x-primary-button>Order</x-primary-button>
                </div>
            </div>
        </form>
    @error('ItemQty[]')
        <div class="p-4 text-center text-red-500">Qty input incorrect, try again.</div>
    @enderror
</x-app-layout>
