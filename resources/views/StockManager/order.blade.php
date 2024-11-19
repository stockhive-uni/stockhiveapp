<x-app-layout>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <form method="GET" action="{{ route('stock-management.sortOrder') }}" class="m-auto text-right w-[90%]">
            <select name="sort" id="sort" class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg w-[50%]">
                <option value="id" {{ request('sort') === 'id' ? 'selected' : '' }}>ID</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price</option>
            </select>
            <input type="hidden" name="count" value="{{ $count }}">
            <input type="hidden" name="deliveryDate" value="{{ $deliveryDate }}">
            <input type="hidden" name="totalPrice" value="{{ $totalPrice }}">
            <input type="hidden" name="page" value="{{ request('page', 1) }}"> <!-- Get page number, default to 1 if none set. -->
            <?php $itemsImploded = implode(',', $allItems); ?>
            <input type="hidden" name="items" value="{{ $itemsImploded }}">
            <x-primary-button class="ml-4">Sort</x-primary-button>
            <x-get-permissions/>
        </form>
        <form action="{{route('stock-management.store')}}" method='POST'>
            @csrf
            @if($items->isNotEmpty())
                <div class="flex justify-between items-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
                    <x-paginate :items="$items"/>
                    <x-primary-button>Create Order</x-primary-button>
                </div>
                @foreach($items as $item)
                    <div>{{$item->id}}</div>
                    <div>{{$item->name}}</div>
                    <div>£{{$item->price}}</div>
                    <div>{{$item->department->name}}</div>
                    <input type='hidden' name='checkbox[]' value='{{$item->id}}'>
                @endforeach
                <x-primary-button>Order</x-primary-button>
            </form>

            {{$count}}
            {{$deliveryDate}}
            £{{$totalPrice}}
            @else
            <p class="text-white">No items on display</p>
        @endif
    </div>
</x-app-layout>