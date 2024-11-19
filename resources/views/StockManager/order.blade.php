<x-app-layout>
    <form action="{{route('stock-management.toOverview')}}" method='POST'>
    @csrf
    @forelse($items as $collection )
        @forelse($collection as $item)
            <div>{{$item->id}}</div>
            <div>{{$item->name}}</div>
            <div>£{{$item->price}}</div>
            <div>{{$item->department->name}}</div>
            <input type='number' name='ItemQty[{{$item->id}}]'></input>
            <input type='hidden' name='checkbox[]' value='{{$item->id}}'></input>

        @empty
        <div>empty</div>
        @endforelse
    @empty
    <div>empty</div>
    @endforelse
    <x-primary-button>Continue</x-primary-button>
    </form>
    @error('ItemQty[]')
        <div>Qty input incorrect, try again.</div>        
    @enderror
</x-app-layout>