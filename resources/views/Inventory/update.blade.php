<x-app-layout>
    Update Stock Page
    <form method='POST' action='{{route('inventory.updated')}}'>
        @csrf
        <x-primary-button>Add</x-primary-button>
            @foreach ($inventoryFromStorage as $inventoryItem)
                    <div>{{$inventoryItem->itemName}}</div>
                    £<div>{{$inventoryItem->price}}</div>
                Qty:<div>{{$inventoryItem->quantity}}</div>
                    Department:<div>{{$inventoryItem->departmentName}}</div>
                    <div>{{$inventoryItem->locationName}}</div> <!-- upon add then change warehouse to floor -->
                    <input type='checkbox' name='checkbox[]' value='{{$inventoryItem->IdOfItem}}'></input>
            @endforeach
    </form>
</x-app-layout>