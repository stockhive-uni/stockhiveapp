<x-app-layout>
    @php global $permissions; @endphp
    @include('components.get-permissions', ['id' => Auth::User()->id])
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-white text-center leading-tight">
            {{ __('Sales') }}
        </h2>
    </x-slot>
    <div class="bg-stockhive-grey-dark text-white shadow-sm md:rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        @if (in_array("5", $permissions))
            <div class="float-right my-4">
                <form action="{{ route('sales.createSale') }}" method="GET">
                    @csrf
                    <x-primary-button>Create New Sale</x-primary-button>
                </form>
            </div>
        @endif
        @if (count($items) > 0)
            <div class="overflow-x-auto w-full">
                <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Salesperson</th>
                            <th>Date/Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <th>{{ $item->id }}</th>
                            <th>{{ $item->first_name }} {{$item->last_name}}</th>
                            <th>{{ $item->date_time }}</th>
                            <th>
                            <form action="{{ route('sales.viewDetails') }}" method="GET" class="inline-block">
                                <input type="hidden" value="{{ $item->id }}" name="id">
                                <x-primary-button>Show Details</x-primary-button>
                            </form>
                            @if (in_array("6", $permissions))
                                <form action="{{ route('sales.downloadInvoice') }}" method="GET" class="inline-block mt-2 lg:mt-0">
                                    <input type="hidden" value="{{ $item->id }}" name="id">
                                    <x-primary-button>Download</x-primary-button>
                                </form>
                            @endif
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        <p class="text-white text-center font-semibold py-4">No sales available.</p>
        @endif
    </div>
</x-app-layout>
