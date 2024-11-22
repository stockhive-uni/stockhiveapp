<x-app-layout>

    @dd ($allresults)

    @foreach ($request->input('reports') as $report)
        <div>{{$report}}</div>
    @endforeach
</x-app-layout>