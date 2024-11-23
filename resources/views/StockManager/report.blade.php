<x-app-layout>

    @foreach ($allresults as $result)
    <div>{{ $result['item_name'] }}</div>
    @foreach ($result['data'] as $key => $data)
        <div>£{{ $data['total'] }}</div>
        <div>{{ $data['month'] }}</div>
    @endforeach
    @endforeach
</x-app-layout>