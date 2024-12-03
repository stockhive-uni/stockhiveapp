<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spot Check') }}
        </h2>
    </x-slot>
    @foreach ($spotCheckItem as $Item)
    <form method='POST' action="{{route('inventory.confirmCheck')}}">
        @csrf
        Last Checked:<div>{{$Item->last_spot_checked}}</div>
        <div>{{$Item->item->name}}</div>
        <div>{{$Item->item->department->name}}</div>
        <input type='hidden' name='stockID' value='{{$Item->id}}'>
        Stock Count<input type='number' name='SpotCheckNum'>
        <x-primary-button>Confirm</x-primary-button>
    </form>
    @endforeach
</x-app-layout>