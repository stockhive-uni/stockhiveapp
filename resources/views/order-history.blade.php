<x-app-layout>
    @foreach ($orders as $order)
        <div>{{$order->id}}</div>
        <div>{{$order->users->first_name}}</div>
        <div>{{$order->users->last_name}}</div>
        <div>Types of Items: {{$order->order_item->count()}}</div>
        <div>{{$order->date_time}}</div> <!-- maybe change this for diffForHumans() command? -->
    @endforeach


        <span>Items:</span>
        @foreach ($orderHistoryItems as $HistoryItem)
                <div>{{$HistoryItem->id}}</div>
                <div>{{$HistoryItem->name}}</div>
                <div>{{$HistoryItem->ordered}}</div>
                <div>£{{$HistoryItem->price}}</div>
        @endforeach
</x-app-layout>