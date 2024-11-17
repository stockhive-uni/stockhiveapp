<x-app-layout>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <form method="GET" action="{{ route('stock.sort') }}" class="m-auto text-right w-[90%]">
            <select name="sort" id="sort" class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg w-[50%]">
                <option value="id" {{ request('sort') === 'id' ? 'selected' : '' }}>ID</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price</option>
            </select>
            <x-primary-button class="ml-4">Sort</x-primary-button>
        </form>
        <form action="{{ route('stock.chosenItems') }}" method="POST">
            @csrf
            @if($items->isNotEmpty())
                <table class="border-separate border-2 m-auto my-8 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Department</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>£{{ $item->price }}</td>
                                <td>{{ $item->department->name }}</td>
                                <td><input type="checkbox" name="items[]" value="{{ $item->id }}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-white">No available stock to order</p>
            @endif
            <div class="m-auto w-[90%] text-right">
                <x-primary-button>Create Order</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>