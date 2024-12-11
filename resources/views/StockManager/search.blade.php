<x-app-layout>
    @php global $permissions; @endphp
    @include('components.get-permissions', ['id' => Auth::User()->id])
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-center py-4 text-gray-800 leading-tight">
            {{ __('Search') }}
        </h1>
    </x-slot>

    <div class="p-8 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
        <h2 class="text-2xl text-center text-white">Search:</h2>
        <div class="flex justify-center">
            <form action='{{route('stock-management.search')}}' method='GET'>
                <input type='text' class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all md:mx-2" name='search' value="{{$searchQuery}}"></input>
                <x-primary-button>Search</x-primary-button>
            </form>
        </div>
    </div>
        <form action="{{ route('stock-management.chosenItems') }}" method="POST">
            @csrf
            @if($items->isNotEmpty())
            @if (in_array("4", $permissions))
            <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
                <h2 class="text-center text-2xl text-white">Actions:</h2>
                <div class="flex justify-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
                    <x-primary-button nameEnter="Report">Generate Reports</x-primary-button>
                </div>
            </div>
            @endif
            <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
            <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-1 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr class="text-xl">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Department</th>
                            @if (in_array("4", $permissions))
                                <th>Select</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="text-lg">
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>£{{ $item->price }}</td>
                                <td>{{ $item->department->name }}</td>
                                @if (in_array("4", $permissions))
                                    <td><input type="checkbox" class="form-checkbox h-5 w-5 bg-stockhive-grey-dark text-accent rounded border-2" name="items[]" value="{{ $item->id }}"></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-white">No available stock to order</p>
            @endif
        </form>
        <br>
        <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
            <x-paginate :items="$items"/>
        </div>
        <br>
</x-app-layout>