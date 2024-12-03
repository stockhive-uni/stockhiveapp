<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Stock Page') }}
        </h2>
    </x-slot>
    @if ($inventoryFromStorage != '[]') 
    <div class='bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg max-w-[1200px] m-auto p-3 mt-2 py-12'>
        <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg text-white">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Department</th>
                    <th>Location</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form method='POST' action='{{route('inventory.updated')}}'>
                        @csrf
                        <div class='flex justify-center'>
                            <div>
                                <x-primary-button>Add</x-primary-button>
                            </div>
    
                                @foreach ($inventoryFromStorage as $inventoryItem)
                                    <td>{{$inventoryItem->itemName}}</td>
                                    <td>£{{$inventoryItem->price}}</td>
                                    <td>{{$inventoryItem->quantity}}</td>
                                    <td>{{$inventoryItem->departmentName}}</td>
                                    <td>{{$inventoryItem->locationName}}</td> <!-- upon add then change warehouse to floor -->
                                    <td><input type='checkbox' name='checkbox[]' value='{{$inventoryItem->IdOfItem}}'></input></td>
                                @endforeach
                        </div>
                    </form>
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <div class='font-semibold text-xl text-gray-800 leading-tight flex justify-center'>No Stock.</div> 
    @endif

</x-app-layout>