<x-app-layout>
    <form action='{{ route('stock.chosenItems')}}' method="POST">
    @csrf

    <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($items -> isNotEmpty())
                <table class="border-separate border-2 m-auto mb-12 w-[90%] text-center border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Department</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>£{{ $item->price }}</td>
                        <td>{{ $item->department->name }}</td>
                        <td><input type="checkbox" name="items[]" value="{{ $item->id }}"></td>
                    </tr>
                    @endforeach
                @else
                    <p class="text-white">No available stock to order</p>
                @endif
                <div class="m-auto w-[90%] text-right"><x-primary-button>Create Order</x-primary-button></div>
                </form>
        </div>
    </div>
</div>
</x-app-layout>