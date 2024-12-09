<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>

    <form action="{{ route('stock-management.search') }}" method="GET">
        <x-paginate :items="$searchAnswers"/>
    </form>

    <form action='{{route('stock-management.search')}}' method='GET'>
        <input type='text' name='search' value="{{$searchQuery}}"></input>
        <x-primary-button>Search</x-primary-button>
    </form>
    
    @forelse ($searchAnswers as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>£{{ $item->price }}</td>
            <td>{{ $item->department->name }}</td>
            <td><form method='POST' action="{{route('stock-management.chosenItems')}}">
                @csrf
                <input type="hidden" name="reports[]" value="{{ $item->id }}"></input>
                <x-primary-button>Generate Report</x-primary-button>
            </form></td>
            <td><input type="checkbox" name="items[]" value="{{ $item->id }}"></td>
        </tr>
        @empty
        <div>No search results.</div>
    @endforelse
</x-app-layout>