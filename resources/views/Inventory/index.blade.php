<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory') }}
        </h2>
    </x-slot>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Notifications') }}
    </h2>

    @forelse ($lowStockItemWarning as $item) 
        <div>Low Stock</div>
        <div>{{$item->itemName}}</div>
        <div>{{$item->price}}</div>
        <div>{{$item->lowStockNum}}</div>
        <div>{{$item->quantity}}</div>
        <form method='GET'  action='{{route('stock-management')}}'>
            <x-primary-button>Order More</x-primary-button>
        </form>
    @empty
        <div>No Notifications.</div>
    @endforelse
</x-app-layout>
