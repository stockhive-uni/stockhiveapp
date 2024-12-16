<x-app-layout>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
    <p>Order:</p>
    <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $item)
            <td>{{$item->id}}</td>
            <td>{{$item->users->first_name}}</td>
            <td>{{$item->users->last_name}}</td>
            <td>{{$item->date_time}}</td>
            @endforeach
            </tr>
        </tbody>
    </table>
   
    </div>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <p>Items:</p>
        <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Name</th>
                    <th>Ordered</th>
                    <th>Price per item</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderHistoryItems as $HistoryItem)
                <tr>
                    <td>{{$HistoryItem->order_id}}</td>
                    <td>{{$HistoryItem->name}}</td>
                    <td>{{$HistoryItem->ordered}}</td>
                    <td>£{{$HistoryItem->price}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>