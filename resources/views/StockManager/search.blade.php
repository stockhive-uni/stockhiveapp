<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>

    <form action="{{ route('stock-management.search') }}" method="GET">
        <x-paginate :items="$items"/>
    </form>

    <form action='{{route('stock-management.search')}}' method='GET'>
        <input type='text' name='search' value="{{$searchQuery}}"></input>
        <x-primary-button>Search</x-primary-button>
    </form>
    
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <form action="{{ route('stock-management.chosenItems') }}" method="POST">
            @csrf
            @if($items->isNotEmpty())
            <div class="flex justify-between items-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
                <x-primary-button nameEnter="Report">Generate Reports</x-primary-button>
            </div>
                <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Department</th>
                            <th>Generate Report</th>
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
                                <td><form method='POST' action="{{route('stock-management.chosenItems')}}">
                                    @csrf
                                    <input type="hidden" name="item" value="{{ $item->id }}"></input>
                                    <x-primary-button>Generate Report</x-primary-button>
                                </form></td>
                                <td><input type="checkbox" name="items[]" value="{{ $item->id }}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-white">No available stock to order</p>
            @endif
        </form>
    </div>
</x-app-layout>