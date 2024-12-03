<x-app-layout>
    Update Stock Page
    <x-primary-button>Add</x-primary-button>
    @foreach ($inventoryFromStorage as $inventoryItem)
        <form method='POST'>
            <div>{{$inventoryItem->itemName}}</div>
            £<div>{{$inventoryItem->price}}</div>
           Qty:<div>{{$inventoryItem->quantity}}</div>
            Department:<div>{{$inventoryItem->departmentName}}</div>
            <div>{{$inventoryItem->locationName}}</div> <!-- upon add then change warehouse to floor -->
            <input type='Checkbox'></input>
        </form>
    @endforeach
</x-app-layout>