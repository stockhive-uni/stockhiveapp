<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales') }}
        </h2>
    </x-slot>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <div class="float-right my-4">
            <form action="{{ route('sales.createSale') }}" method="GET">
                @csrf
                <x-primary-button>Create New Sale</x-primary-button>
            </form>
        </div>
        <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Salesperson</th>
                    <th>Date/Time</th>
                    <th>Details</th>
                    <th>Invoice</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <th>{{ $item->id }}</th>
                    <th>{{ $item->first_name }} {{$item->last_name}}</th>
                    <th>{{ $item->date_time }}</th>
                    <th>
                    <form action="{{ route('sales.viewDetails') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $item->id }}" name="id">
                        <x-primary-button>Show Details</x-primary-button>
                    </form>
                    </th>
                    <th>
                    <form action="{{ route('sales.downloadInvoice') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $item->id }}" name="id">
                        <x-primary-button>Download</x-primary-button>
                    </form>
                    </th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
