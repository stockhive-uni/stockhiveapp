<x-app-layout>

    @dd($allresults)

    @foreach ($allresults as $result)
    <div>{{ $result }}</div>
    @endforeach
</x-app-layout>