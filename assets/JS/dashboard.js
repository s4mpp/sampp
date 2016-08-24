	var grafico = new Chart($('#grafico'), {
	type: 'line',
	data: {
		labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
		datasets: [{
			label: 'Gráfico',
			data: [12, 19, 10, 25, 9, 17],
			backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255,99,132,1)',
            ],
			borderWidth: 2,
			offsetWidth: 2,

		}]},
	options: {
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero:true
				}
			}]
		}
	}
});

