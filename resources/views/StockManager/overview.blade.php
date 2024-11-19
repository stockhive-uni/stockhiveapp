<x-app-layout>
    <form>
        <form action="{{route('stock-management.store')}}" method='POST'>
            @csrf
            @forelse($items as $collection )
                @forelse($collection as $item)
                    <div>{{$item->id}}</div>
                    <div>{{$item->name}}</div>
                    <div>£{{$item->price}}</div>
                    <div>{{$item->department->name}}</div>
                    <div>{{$ItemQty[$item->id]}}</div>
                    <input type='hidden' name='checkbox[]' value='{{$item->id}}'></input>
        
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