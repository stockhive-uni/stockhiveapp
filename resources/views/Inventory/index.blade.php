<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory') }}
        </h2>
    </x-slot>
    <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg max-w-[1200px] m-auto p-3 mt-2">
        <form method='GET' action='{{route('inventory.addToFloor')}}'>
            <x-primary-button>Storage To Floor</x-primary-button>
        </form>

        <form method='GET' action='{{route('inventory.remove')}}'>
            <x-primary-button>Floor To Storage</x-primary-button>
        </form>
    </div>

    <div class="py-12">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight justify-center flex text-white">
            {{ __('Notifications') }}
        </h2>
    
        
        @if(($lowStockItemWarning == "[]") && ($spotCheckItemWarning == "[]") ) 
        <div>No Notifications.</div>
    
        @endif

        @foreach ($lowStockItemWarning as $item) 
        <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg max-w-[800px] m-auto p-3 mt-2">
            <div class='flex justify-between'>
                <div>
                    <div class='text-xl'>Low Stock</div>
                    <div>{{$item->itemName}}</div>
                    <div>There is currently {{$item->quantity}} of this product, a minimum of {{$item->lowStockNum}} is required.</div> 
                </div>
    
                <div class='self-center'>
                    <form method='GET'  action='{{route('stock-management')}}'>
                        <x-primary-button>Order</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    
        @foreach ($spotCheckItemWarning as $item) 
        <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg max-w-[800px] m-auto p-3 mt-2">
            <div class='flex justify-between'>
                <div>
                    <div class='text-xl'>Spot Check</div>
                    <div>{{$item->itemName}}</div>
                    <div>£{{$item->price}}</div>
                    <div>{{$item->last_spot_checked}}</div>
                </div>
    
                <div class='self-center'>
                    <form method="GET" action="{{ route('inventory.spotCheck') }}">
                        <input type="hidden" name="spotcheck" value="{{$item->id}}">
                        <x-primary-button>Complete</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        @foreach ($noStockWarning as $item) 
        <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg max-w-[800px] m-auto p-3 mt-2">
            <div class='flex justify-between'>
                <div>
                    <div class='text-xl'>No Stock</div>
                    <div>{{$item->item->name}}</div>
                    <div>£{{$item->item->price}}</div>
                    <div>{{$item->item->department->name}}</div>
                </div>
    
                <div class='self-center'>
                    <form method="GET" action="{{ route('stock-management') }}">
                        <input type="hidden" name="spotcheck" value="{{$item->id}}">
                        <x-primary-button>Order</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    
    </div>
   
</x-app-layout>
