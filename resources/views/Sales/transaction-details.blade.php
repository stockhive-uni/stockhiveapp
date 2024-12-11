<x-app-layout>
    <div class="bg-stockhive-grey-dark text-white shadow-sm md:rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <h1 class="text-center text-3xl">Transaction details</h1>
        <h3 class="text-xl py-1">Transaction ID: {{ $transaction->id }}</h3>
        <h3 class="text-xl py-1">Sold by: {{ $transaction->first_name}} {{ $transaction->last_name }}</h3>

        @php
        $date_time = explode(' ', $transaction->date_time);

        $date_explode = explode('-', $date_time[0]);
        $date = $date_explode[2] . '/' . $date_explode[1] . '/' . $date_explode[0];

        $time = $date_time[1];
        $total = 0;
        @endphp

        <h3 class="text-xl py-1">Date: {{ $date }}</h3>
        <h3 class="text-xl py-1">Time: {{ $time }}</h3>
        <h3 class="text-xl py-1">Card: {{ $transaction->card }}</h3>

        <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                @php
                $lineTotal = $item->quantity * $item->price;
                $total += $lineTotal;
                @endphp
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>£{{ $item->price }}</td>
                <td>£{{ $lineTotal }}</td>
                @endforeach
                </tr>
            </tbody>
        </table>
        <h3>Total: £{{ $total }}</h3>

        <form action="{{ route('sales.downloadInvoice') }}" method="POST">
            @csrf
            <input type="hidden" value="{{ $transaction->id }}" name="id">
            <x-primary-button>Download</x-primary-button>
        </form>
    </div>
</x-app-layout>