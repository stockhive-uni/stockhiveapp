<x-app-layout>
    @php global $permissions; @endphp
    @include('components.get-permissions', ['id' => Auth::User()->id])
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-center py-4 text-gray-800 leading-tight">
            {{ __('Order From Warehouse') }}
        </h1>
    </x-slot>
    <div class="p-8 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
        <h2 class="text-2xl text-center text-white">Search:</h2>
        <div class="flex justify-center">
            <form name='searchFunction' action='{{route('stock-management.search')}}' method='GET' class="flex items-center gap-2">
                <input class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent p-2" type='text' name='search' :value="request()">
                <x-primary-button>Search</x-primary-button>
            </form>
        </div>
    </div>

        <div class="p-8 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
            <h2 class="text-2xl text-center text-white">Sort By:</h2>
            <div class="flex justify-center">
                <form method="GET" action="{{ route('stock-management.sort') }}" class="flex items-center gap-2">
                    <select name="sort" id="sort" class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent p-2">
                    <option value="id" {{ request('sort') === 'id' ? 'selected' : '' }}>ID</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price</option>
                    </select>
                    <input type="hidden" name="page" value="{{ request('page', 1) }}">
                    <x-primary-button>Sort</x-primary-button>
                </form>
            </div>
        </div> 

        <form action="{{ route('stock-management.chosenItems') }}" method="POST">
            @csrf
            @if($items->isNotEmpty())
            <div class="p-8 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
                <h2 class="text-2xl text-center text-white">Actions:</h2>
                <div class="flex justify-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
                @if (in_array("1", $permissions))
                    <x-primary-button nameEnter="Order">Start Order</x-primary-button>
                @endif
                @if (in_array("4", $permissions))
                    <x-primary-button nameEnter="Report">Generate Reports</x-primary-button>
                        @if (isset($error))
                            <p class="text-error">{{ $error }}</p>
                        @endif
                </div>
                @endif
            </div>
            <div class="lg:p-8 md:p-4 p-2 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto text-white">
                <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-1 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr class="text-xl">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Department</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody class="text-lg">
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>£{{ $item->price }}</td>
                                <td>{{ $item->department->name }}</td>
                                <td><input type="checkbox" class="form-checkbox h-5 w-5 bg-stockhive-grey-dark text-accent rounded border-2" name="items[]" value="{{ $item->id }}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-white">No available stock to order</p>
            @endif
            <div class="p-8 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
                <x-paginate :items="$items"/>
            </div>
        </form>
    <br>
</x-app-layout>