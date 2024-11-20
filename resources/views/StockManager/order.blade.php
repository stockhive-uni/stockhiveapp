<x-app-layout>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <!-- Sorting Form -->
        <form method="GET" action="{{ route('stock-management.sortOrder') }}" class="m-auto text-right w-[100%] mb-2">
            <div class="flex items-center justify-end gap-1 w-full">
                <!-- Select dropdown and Sort button aligned -->
                <select name="sort" id="sort" class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg w-[30%]">
                    <option value="id" {{ request('sort') === 'id' ? 'selected' : '' }}>ID</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price</option>
                </select>
                <x-primary-button class="ml-4">Sort</x-primary-button>
            </div>
            <input type="hidden" name="count" value="{{ $count }}">
            <input type="hidden" name="deliveryDate" value="{{ $deliveryDate }}">
            <input type="hidden" name="totalPrice" value="{{ $totalPrice }}">
            <input type="hidden" name="page" value="{{ request('page', 1) }}">
            <?php $itemsImploded = implode(',', $allItems); ?>
            <input type="hidden" name="items" value="{{ $itemsImploded }}">
        </form>

        <!-- Items Display -->
        <form action="{{ route('stock-management.store') }}" method="POST" class="w-full">
            @csrf
            @if($items->isNotEmpty())
                <!-- Pagination and Order Button -->
                <div class="flex justify-between items-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 w-full">
                    <x-paginate :items="$items"/>
                    <x-primary-button>Create Order</x-primary-button>
                </div>

                <!-- Items Table -->
                <table class="border-collapse border-2 w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr class="text-left border-b-2 border-grey">
                            <th class="py-2 px-4 text-center">ID</th>
                            <th class="py-2 px-4 text-center">Name</th>
                            <th class="py-2 px-4 text-center">Price</th>
                            <th class="py-2 px-4 text-center">Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="hover:bg-stockhive-grey-light transition-all">
                                <td class="py-2 px-4">{{ $item->id }}</td>
                                <td class="py-2 px-4">{{ $item->name }}</td>
                                <td class="py-2 px-4">£{{ $item->price }}</td>
                                <td class="py-2 px-4">{{ $item->department->name }}</td>
                                <input type='hidden' name='checkbox[]' value='{{$item->id}}'></input>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Order Totals -->
                <div class="bg-stockhive-grey-light text-right rounded-lg p-4 w-full mt-4">
                    <p class="text-sm">Total Count: <span class="font-semibold">{{ $count }}</span></p>
                    <p class="text-sm">Delivery Date: <span class="font-semibold">{{ \Carbon\Carbon::parse($deliveryDate)->format('d/m/Y \a\t H:i') }}</span></p>
                    <p class="text-sm">Total Price: <span class="font-semibold">£{{ $totalPrice }}</span></p>
                </div>
            @else
                <p class="text-center text-white mt-6">No items on display</p>
            @endif
        </form>
    </div>
</x-app-layout>
