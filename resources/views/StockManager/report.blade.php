<x-app-layout>
    <div class="bg-stockhive-grey-dark text-white shadow-sm lg:rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <!-- Display chart -->
        <canvas id="chart-report" class="w-full h-full"></canvas>
    </div>
    <div class="bg-stockhive-grey-dark text-white shadow-sm lg:rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <!-- Display the data -->
        <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
            <thead>
                <tr class="text-left border-b-2 border-grey">
                    <th class="py-2 px-4 text-center">Name</th>
                    <th class="py-2 px-4 text-center">Total Sales</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allresults as $item)
                    <tr class="hover:bg-stockhive-grey-light transition-all">
                        <td class="py-2 px-4">{{ $item['item_name'] }}</td>
                        <td class="py-2 px-4">£{{ number_format(array_sum(array_column($item['data'], 'total')), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <form action="{{ route('stock-management.downloadReport') }}" method="GET">
        @foreach ($items as $item)
            <input type="hidden" name="items[]" value="{{ $item }}">
        @endforeach
        <x-primary-button>Download Report</x-primary-button>
    </form>


    <!-- Chart.JS scripting -->
    <script>
        // Get data from PHP and parse it through to JSON
        const rawData = JSON.parse('{!! json_encode($allresults, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}'); // https://www.php.net/manual/en/function.json-encode.php - Adam
        // Labels as Months
        const labels = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        let data = [];

        // Colours :)
        const colors = [
            '#ff4959', '#ff8c61', '#ffbf69', '#ffeb75', '#d3f261', '#a0e358', '#6cd352', '#4ccf4d', '#2bc94a', '#00c247', '#00b746', '#00a845'
        ];

        // Iterate through the raw data and push to array
        const datasets = rawData.map((item, index) => ({ // JavaScript map, made with knowledge I have from using React/Next.JS - Adam
            label: item.item_name,
                data: labels.map((month, index) => { // Map the data to the labels
                const monthlyData = Object.values(item.data).find(d => d.month === (index + 1));
                return monthlyData ? monthlyData.total : 0;
            }),
            backgroundColor: colors[index % colors.length], // Chooses a colour based on the item (index)
            borderColor: colors[index % colors.length],
            borderWidth: 4
        }));

        // Generate the chart
        const reportChart = document.getElementById('chart-report').getContext('2d');
        new Chart( // https://www.chartjs.org/docs/latest/getting-started/usage.html - Adam
            reportChart, {
                type: 'line', // https://www.chartjs.org/docs/latest/charts/line.html - Adam
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: { // https://www.chartjs.org/docs/latest/configuration/ - Adam
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Sales Report',
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
                    scales: { 
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: false
                        }
                    }
                }
            }
        )
    </script>

</x-app-layout>