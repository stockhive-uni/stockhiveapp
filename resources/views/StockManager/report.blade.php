<x-app-layout>

    @foreach ($allresults as $result)
    <div>{{ $result['item_name'] }}</div>
    @foreach ($result['data'] as $key => $data)
        <div>£{{ $data['total'] }}</div>
        <div>{{ $data['month'] }}</div>
    @endforeach
    @endforeach

    <div>
        <h1>Chart test:</h1>
        <canvas id="chart-report" width="400px"></canvas>
    </div>

    <!-- Chart.JS scripting -->
    <script>
        // Get data from PHP and parse it
        const rawData = JSON.parse('{!! json_encode($allresults, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}');

        // Create arrays for labels and data
        const labels = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        let data = [];

        // Iterate through the raw data and push to array
        const datasets = rawData.map(item => ({
            label: item.item_name,
                data: labels.map((month, index) => { // Map the data to the labels
                const monthlyData = Object.values(item.data).find(d => d.month === (index + 1).toString());
                return monthlyData ? monthlyData.total : 0;
            }),
            // Set the background and border colours
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }));

        // Generate the chart
        const reportChart = document.getElementById('chart-report').getContext('2d');
        new Chart(
            reportChart, {
                type: 'bar', // https://www.chartjs.org/docs/latest/charts/bar.html
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true, // Make the chart responsive
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Sales Report'
                        }
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
                            stacked: true
                        }
                    }
                }
            }
        )
    </script>

</x-app-layout>