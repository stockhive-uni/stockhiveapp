<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quantity to order') }}
        </h2>
    </x-slot>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <!-- Sorting Form -->
        <form method="GET" action="{{ route('stock-management.toOverview') }}" class="m-auto text-right w-[90%]">
            <!-- Select dropdown and Sort button aligned -->
            <select name="sort" id="sort" class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg w-[50%]">
                <option value="id" {{ request('sort') === 'id' ? 'selected' : '' }}>ID</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price</option>
            </select>
            <x-primary-button class="ml-4">Sort</x-primary-button>
            <input type="hidden" name="page" value="{{ request('page', 1) }}">
            <?php $itemsImploded = implode(',', $allItems); ?>
            <input type="hidden" name="items" value="{{ $itemsImploded }}">
        </form>

        <!-- Items Display -->
        <form action="{{ route('stock-management.toOverview') }}" method="GET" class="w-full">
            @if($items->isNotEmpty())
                <!-- Pagination and Order Button -->
                <div class="flex justify-between items-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
                    <x-paginate :items="$items"/>
                    <x-primary-button>Place Order</x-primary-button>
                </div>

                <!-- Items Table -->
                <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr class="text-left border-b-2 border-grey">
                            <th class="py-2 px-4 text-center">ID</th>
                            <th class="py-2 px-4 text-center">Name</th>
                            <th class="py-2 px-4 text-center">Price</th>
                            <th class="py-2 px-4 text-center">Department</th>
                            <th class="py-2 px-4 text-center">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="hover:bg-stockhive-grey-light transition-all">
                                <td class="py-2 px-4">{{ $item->id }}</td>
                                <td class="py-2 px-4">{{ $item->name }}</td>
                                <td class="py-2 px-4">£{{ $item->price }}</td>
                                <td class="py-2 px-4">{{ $item->department->name }}</td>
                                <td class="py-2 px-4 text-black"><input type='number' name='ItemQty[{{$item->id}}]'></input></td> <!-- here needs changing, the variable name needs to match the item id -->
                                <input type='hidden' name='checkbox[]' value='{{$item->id}}'></input>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else
                <p class="text-center text-white mt-6">No items on display</p>
            @endif
        </form>
    </div>
</x-app-layout>