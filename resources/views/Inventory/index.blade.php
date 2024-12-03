<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory') }}
        </h2>
    </x-slot>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Notifications') }}
    </h2>

    @if(($lowStockItemWarning == "[]") && ($spotCheckItemWarning == "[]") ) 
        <div>No Notifications.</div>
    @endif
    @if ($lowStockItemWarning != "[]")
        <h3>Low Stock</h3>
    @endif
    @foreach ($lowStockItemWarning as $item) 
        <div>{{$item->itemName}}</div>
        <div>{{$item->price}}</div>
        <div>{{$item->lowStockNum}}</div>
        <div>{{$item->quantity}}</div>
        <form method='GET'  action='{{route('stock-management')}}'>
            <x-primary-button>Order More</x-primary-button>
        </form>
    @endforeach

    @if ($spotCheckItemWarning != "[]")
    <h3>Spot Check</h3>
    @endif
    @foreach ($spotCheckItemWarning as $item) 
    <div>{{$item->itemName}}</div>
    <div>£{{$item->price}}</div>
    <div>{{$item->last_spot_checked}}</div>
    <form method="GET" action="{{ route('inventory.spotCheck') }}">
        <input type="hidden" name="spotcheck" value="{{$item->id}}">
        <input type='submit' value='Complete'>
    </form>
    @endforeach

    <form method='GET' action='{{route('inventory.update')}}'>
        <x-primary-button>Update Stock</x-primary-button>
    </form>
</x-app-layout>
