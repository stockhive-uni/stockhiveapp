<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales') }}
        </h2>
    </x-slot>
    <form action="{{ route('sales.createSale') }}" method="GET">
        <x-primary-button>Create New Sale</x-primary-button>
    </form>
    <h2>Transaction History</h2>
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>User</th>
                <th>Date/Time</th>
                <th>Details</th>
                <th>Invoice</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <th>{{ $item->id }}</th>
                <th>{{ $item->first_name }}</th>
                <th>{{ $item->date_time }}</th>
                <th>
                <form action="" method="POST">
                    <input type="hidden" value="{{ $item->id }}" name="id">
                    <x-primary-button>Show Details</x-primary-button>
                </form>
                </th>
                <th>
                <form action="" method="POST">
                    <input type="hidden" value="{{ $item->id }}" name="id">
                    <x-primary-button>Generate</x-primary-button>
                </form>
                </th>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>
