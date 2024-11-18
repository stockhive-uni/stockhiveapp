<x-app-layout>
    <form action="{{route('stock-management.chosenItems')}}" method="POST">
    @csrf
    <ul>
        <dt>ID</dt>
        <dt>name</dt>
        <dt>price</dt>
        <dt>department id</dt>
    @forelse ($items as $item)
            <li>{{$item->id}}</li>
            <li>{{$item->name}}</li>
            <li>£{{$item->price}}</li>
            <li>{{$item->department->name}}</li>
            <input type="checkbox" name='items[]' value='{{$item->id}}'>
        </ul>
    @empty
    <div>No Stock Available To Order</div>
    @endforelse
    <x-primary-button>Create Order</x-primary-button>
    </form>
</x-app-layout>