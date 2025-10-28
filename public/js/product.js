//window.addEventListener('load', displayChart)
//window.addEventListener('ready', displayChart)
function displayChart() {

    var chart = document.getElementById('price_history');
    var data = JSON.parse(chart.getAttribute('data-price-information'));
    new Chart(chart, {
        type: 'line',
        data: {
            labels: data.map(row => row.price_start_date),
            datasets: [
                {
                    borderColor: '#FFCC00',
                    borderWidth: 5,
                    data: data.map(row => row.price)
                }
            ]
        },
        options: {
            events: [],
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    display: false,
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}