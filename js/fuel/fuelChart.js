// wykres tankowan
function openFuelHistoryChart(carId) {
    selectedCarId = carId;

    fetch("", { 
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `car_id_history_chart=${selectedCarId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const labels = data.data.map(entry => entry.refueling_date);
            const values = data.data.map(entry => entry.consumption_100_km);

            const ctx = document.getElementById("fuelChart").getContext("2d");
            
            if (window.fuelChartInstance) {
                window.fuelChartInstance.destroy(); // Usuwamy stary wykres, jeśli istnieje
            }

            window.fuelChartInstance = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Spalanie (l/100km)",
                        data: values,
                        borderColor: "rgba(75, 192, 192, 1)",
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            document.getElementById("chart-fuel-history").style.display = "block";
  document.getElementById('overlay').style.display = 'block';
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error("Błąd pobierania danych wykresu:", error);
        alert("Błąd pobierania danych.");
    });
}

function closeFuelHistoryChart() {
    document.getElementById("chart-fuel-history").style.display = "none";
  document.getElementById('overlay').style.display = 'none';
}