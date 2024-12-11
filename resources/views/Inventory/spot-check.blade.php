<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-white text-center leading-tight">
            {{ __('Spot Check') }}
        </h1>
    </x-slot>

    <div class='bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg max-w-[1200px] m-auto p-3 mt-2 py-12'>
        <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg text-white">
            <thead>
                <tr>
                    <th>Last Checked</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Stock Count</th>
                    <th>Confirm</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($spotCheckItem as $Item)
                    <form method='POST' action="{{route('inventory.confirmCheck')}}">
                        @csrf
                        <td>{{$Item->last_spot_checked}}</td>
                        <td>{{$Item->item->name}}</td>
                        <td>{{$Item->item->department->name}}</td>
                        <input type='hidden' name='stockID' value='{{$Item->id}}'>
                        <td><input type='number' class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg" name='SpotCheckNum' min ="1" value="1"></td>
                        <td><x-primary-button>Confirm</x-primary-button><td>
                    </form>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>