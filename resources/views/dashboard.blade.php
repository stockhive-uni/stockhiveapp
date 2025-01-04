<x-app-layout>
    @php global $permissions; @endphp
    @include('components.get-permissions', ['id' => Auth::User()->id])
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-center py-4 text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-xl"><span class="font-bold">Name:</span> {{Auth::user()->first_name }} {{Auth::user()->last_name}}</p>
                    <p class="text-xl"><span class="font-bold">Email:</span> {{ Auth::user()->email }}</p>
                    <p class="text-xl"><span class="font-bold">Employee ID:</span> {{ Auth::user()->id}}</p>
                    <form action="{{ route('profile.edit') }}" method="GET">
                        <x-primary-button>Go to Settings</x-primary-button>
                    </form>
                </div>
            </div>


            @if (count($orderHistory) > 0 && in_array("1", $permissions))
            <div class="bg-stockhive-grey-dark text-white shadow-sm my-8 rounded-lg mt-8 w-full m-auto p-4 overflow-x-auto">
                <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg ">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date Time</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <span>Order History:</span>
                        @foreach ($orderHistory as $order)
                        <tr>
                        <form action="{{route('stock-management.ShowOrderHistory')}}" method="GET">
                            <th>{{$order->id}}</th>
                            <th>{{$order->users->first_name}}</th>
                            <th>{{$order->users->last_name}}</th>
                            <input type='hidden' name="order" value='{{$order->id}}'></input>
                            <th>{{$order->date_time}}</th>
                            <th><x-primary-button>Details</x-primary-button></th>
                        </form>
                        @endforeach
                        </tr>
                    </tbody>
                </table>   
        </div>
        @endif

        @if (in_array("5", $permissions))
        <div class="bg-stockhive-grey-dark text-white shadow-sm my-8 rounded-lg mt-8 w-full m-auto p-4">
            <div class="flex flex-wrap justify-between gap-8">
                <div class="w-full lg:w-[45%]">
                    <div class="w-[60%] m-auto">
                        <canvas id="chart-info1"></canvas>
                    </div>
                    <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                        <thead>
                            <tr>
                                <th>Items Sold This Month</th>
                                <th>Items Sold Last Month</th>
                                <th>% Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>{{$ItemsSoldThisMonth}}</th>
                                <th>{{$ItemsSoldLastMonth}}</th>
                                <th>{{ $ItemsSoldLastMonth != 0 ? number_format(($ItemsSoldThisMonth/$ItemsSoldLastMonth)*100, 1) : 'N/A' }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            
                <div class="w-full lg:w-[45%]">
                    <div class="w-[60%] m-auto">
                        <canvas id="chart-info2"></canvas>
                    </div>
                    <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                        <thead>
                            <tr>
                                <th>Sales This Month</th>
                                <th>Sales Last Month</th>
                                <th>% Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>{{$salesThisMonth}}</th>
                                <th>{{$salesLastMonth}}</th>
                                <th>{{ $salesThisMonth != 0 && $salesLastMonth != 0 ? number_format(($salesThisMonth/$salesLastMonth)*100, 1) : 'N/A' }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if (in_array("7", $permissions))
        <div class="bg-stockhive-grey-dark p-8 text-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="w-[65%] m-auto flex self-center">
                <canvas id="chart-info3"></canvas>
            </div>
            <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr>
                        <th>Fulfilled Orders</th>
                        <th>Deliveries needing attention</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{$deliveriesMade}}</th>
                        <th>{{$deliveriesToComplete}}</th>
                    </tr>
                </tbody>

            </table>
        </div>
        @endif

    </div>

    <!-- Chart.JS scripting -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

    

        const permissionsArray = <?php echo json_encode($permissions); ?>;
        // Colours used on doughnut chart.
        const colors = [
            '#ff4959', '#ff8c61', '#ffbf69', '#ffeb75', '#d3f261', '#a0e358', '#6cd352', '#4ccf4d', '#2bc94a', '#00c247', '#00b746', '#00a845'
        ];

        // Generate the chart
        if (permissionsArray.includes(5)) {
            const ItemsSoldThisMonth = <?php echo json_encode($ItemsSoldThisMonth); ?>; // PHP to JS https://www.php.net/manual/en/function.json-encode.php - Adam
            const ItemsSoldLastMonth = <?php echo json_encode($ItemsSoldLastMonth); ?>;

            const labels1 = ["Sold This Month", "Sold Last Month"];
            let data1 = [ItemsSoldThisMonth, ItemsSoldLastMonth];
            const salesOrderInfo1 = document.getElementById('chart-info1').getContext('2d');
        new Chart(
            salesOrderInfo1, {
                type: 'doughnut', // https://www.chartjs.org/docs/latest/charts/doughnut.html - Adam
                data: {
                    labels: labels1,
                    datasets: [{
                        data: data1,
                        backgroundColor: colors,
                        borderColor: '#000000', // Changing the colour from default (white) to black https://stackoverflow.com/a/65503304 - Adam
                    }]
                },
                options: { 
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Items',
                            color: 'white',
                            font: {
                                size: 18
                            }
                        },
                        legend: {
                            labels: {
                                color: 'white',
                                font: {
                                    size: 14
                                }
                            }
                        },
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                }
            }
        )
        }

        if (permissionsArray.includes(5)) {
        const salesThisMonth = <?php echo json_encode($salesThisMonth); ?>; 
        const salesLastMonth = <?php echo json_encode($salesLastMonth); ?>;
        const labels2 = ["Sales This Month", "Sales Last Month"];
        let data2 = [salesThisMonth, salesLastMonth];
            
        const salesOrderInfo2 = document.getElementById('chart-info2').getContext('2d');
        //sales chart
        new Chart(
            salesOrderInfo2, {
                type: 'doughnut', 
                data: {
                    labels: labels2,
                    datasets: [{
                        data: data2,
                        backgroundColor: colors,
                        borderColor: '#000000', 
                    }]
                },
                options: { 
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Sales',
                            color: 'white',
                            font: {
                                size: 18
                            }
                        },
                        legend: {
                            labels: {
                                color: 'white',
                                font: {
                                    size: 14
                                }
                            }
                        },
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                }
            }
        )
        }

        if (permissionsArray.includes(7)) {
            const deliveriesMade = <?php echo json_encode($deliveriesMade); ?>;
            const deliveriesToComplete = <?php echo json_encode($deliveriesToComplete); ?>;
            const labels3 = ["Fulfilled Orders", "Deliveries needing attention"];
        let data3 = [deliveriesMade,deliveriesToComplete];

        const salesOrderInfo3 = document.getElementById('chart-info3').getContext('2d');
        //sales chart
        new Chart(
            salesOrderInfo3, {
                type: 'bar',
                data: {
                    labels: labels3,
                    datasets: [{
                        label: 'Deliveries Overview',
                        data: data3,
                        backgroundColor: colors,
                        borderColor: '#000000',
                    }]
                },
                options: { 
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Orders and deliveries',
                            color: 'white',
                            font: {
                                size: 18
                            }
                        },
                        legend: {
                            labels: {
                                color: 'white',
                                font: {
                                    size: 14
                                }
                            }
                        },
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                }
            }
        )
        }
        });
    </script>

</x-app-layout>
