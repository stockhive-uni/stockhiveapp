<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Overview') }}
        </h2>
    </x-slot>
    <form action="{{route('stock-management.store')}}" method='POST'>
            @csrf
            @forelse($items as $collection )
                @php $iteration = 0; @endphp
                @forelse($collection as $item)
                    <div>{{$item->id}}</div>
                    <div>{{$item->name}}</div>
                    <div>£{{$item->price}}</div>
                    <div>{{$item->department->name}}</div>
                    <input type='hidden' name='ItemQty[{{$item->id}}]' value='{{$ItemQty[$iteration]}}'>{{$ItemQty[$iteration]}}</input>
                    <input type='hidden' name='checkbox[]' value='{{$item->id}}'></input>
                    @php $iteration++; @endphp
                @empty
                <div>empty</div>
                @endforelse
            @empty
            <div>empty</div>
            @endforelse
            <x-primary-button>Order</x-primary-button>
            </form>
            
            @error('ItemQty[]')
                <div>Qty input incorrect, try again.</div>        
            @enderror
    </form>

    £{{$totalPrice}} 
    {{$deliveryDate}}
    {{$totalItems}} Items
</x-app-layout>