<canvas id="chart-report" height="100%"></canvas>

<script>
    // Get data from PHP and parse it
    const rawData = JSON.parse('{!! json_encode($allresults, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}');

    // Create arrays for labels and data
    const labels = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    let data = [];

    // Colours used on bar chart.
    const colors = [
        '#ff4959', '#ff8c61', '#ffbf69', '#ffeb75', '#d3f261', '#a0e358', '#6cd352', '#4ccf4d', '#2bc94a', '#00c247', '#00b746', '#00a845'
    ];

    // Iterate through the raw data and push to array
    const datasets = rawData.map((item, index) => ({
        label: item.item_name,
            data: labels.map((month, index) => { // Map the data to the labels
            const monthlyData = Object.values(item.data).find(d => d.month === (index + 1).toString());
            return monthlyData ? monthlyData.total : 0;
        }),
        backgroundColor: colors[index % colors.length], // Chooses a colour based on the item (index)
        borderColor: colors[index % colors.length],
        borderWidth: 4
    }));

    // Generate the chart
    const reportChart = document.getElementById('chart-report').getContext('2d');
    new Chart(
        reportChart, {
            type: 'line', // https://www.chartjs.org/docs/latest/charts/line.html
            data: {
                labels: labels,
                datasets: datasets
            },
            options: { // https://www.chartjs.org/docs/latest/configuration/
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
                        stacked: true
                    }
                }
            }
        }
    )
</script>