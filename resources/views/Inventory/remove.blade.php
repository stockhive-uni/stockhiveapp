<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-center text-white leading-tight">
            {{ __('Floor To Storage') }}
        </h1>
    </x-slot>
    @if ($InventoryFromFloor != '[]') 
    <div class='bg-stockhive-grey-dark text-white overflow-hidden shadow-sm md:rounded-lg max-w-[1200px] m-auto p-3 mt-2 py-12'>
        <form method='POST' action='{{route('inventory.removeFromFloor')}}'>
                    <div class="md:px-16">
                        <x-primary-button>Remove</x-primary-button>
                    </div>
        <div class="overflow-x-auto w-full">
            <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg text-white">
                    @csrf
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Available Quantity</th>
                        <th>Quantity To Storage</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($InventoryFromFloor as $inventoryItem)
                    <tr>
                            <div class='flex justify-center'>
        
                                        <td>{{$inventoryItem->itemName}}</td>
                                        <td>£{{$inventoryItem->price}}</td>
                                        <td>{{$inventoryItem->quantity}}</td>
                                        <input type='hidden' name='QtyOnFloor[{{$inventoryItem->IdOfItem}}]' value="{{$inventoryItem->quantity}}"></input>
                                        <td><input type='number' class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg" name='ItemQtyRemove[{{$inventoryItem->IdOfItem}}]' value='{{$inventoryItem->quantity}}' min=1 max={{$inventoryItem->quantity}}></td>
                                        <td>{{$inventoryItem->departmentName}}</td>
                                        <td>{{$inventoryItem->locationName}}</td> <!-- upon add then change warehouse to floor -->         
                                        <td><input type='checkbox' name='checkbox[{{$inventoryItem->IdOfItem}}]' class="form-checkbox h-5 w-5 bg-stockhive-grey-dark text-accent rounded border-2" value='{{$inventoryItem->IdOfItem}}'></input></td>
                            </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </form>
    </div>
    @else
    <div class='font-semibold text-xl text-gray-800 leading-tight flex justify-center'>No Stock.</div> 
    @endif

</x-app-layout>