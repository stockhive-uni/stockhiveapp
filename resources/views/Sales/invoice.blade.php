<h1>StockHive Invoice</h1>
<h3>Store: {{ $transaction->location }}</h3>
<h3>Transaction ID: {{ $transaction->id }}</h3>
<h3>Sold by: {{ $transaction->first_name}} {{ $transaction->last_name }}</h3>

@php
$date_time = explode(' ', $transaction->date_time);

$date_explode = explode('-', $date_time[0]);
$date = $date_explode[2] . '/' . $date_explode[1] . '/' . $date_explode[0];

$time = $date_time[1];
$total = 0;
@endphp

<h3>Date: {{ $date }}</h3>
<h3>Time: {{ $time }}</h3>
<h3>Card: {{ $transaction->card }}</h3>

<table>
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