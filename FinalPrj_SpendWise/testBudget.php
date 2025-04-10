<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Management</title>
    <?php require_once 'Topbar.php';?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            max-width: 300px;
            height: 350px;
            padding: 20px;
            border: 2px solid black;
            border-radius: 10px;
            text-align: center;
            display: flow-root;
        }
        
        .chart-container h2,
        .chart-container p {
          margin: 0 auto;
          line-height: 1.5;
        }
        
        #donutChart {
            width: 100% !important;
            height: 100% !important;
        }
        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            pointer-events: none;
            }
        
        .chart-wrapper {
            position: relative;
            width: 100%;
            max-width: 300px;
            aspect-ratio: 1 / 1;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<?php
require_once 'dbConfig.php';
require_once 'Budget.class.php'; // Asegúrate de que la ruta sea correcta

// Crear instancia de la clase Budget
$budget = new Budget($connection);

// Obtener los datos del presupuesto
$budgetData = $budget->getBudgetData();
$data = $budgetData['data'];
$total = $budgetData['total'];

// Calcular los porcentajes
$percentages = $budget->calculatePercentages($data, $total);

// Codificar los datos para JavaScript
$labels = json_encode(array_keys($percentages));
$values = json_encode(array_values($percentages));
?>

    <div class="chart-container">
        <h2>My Expenses</h2>
		<p><strong><script>document.write(new Date().toLocaleString('es-ES',{month:'short',year:'numeric'}));</script></strong></p>
            <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>        
        <div class="chart-wrapper">
          <canvas id="donutChart" width="600" height="600"></canvas>
        </div>

    </div>
    
    
<script>
    const labels = <?php echo $labels; ?>;
    const values = <?php echo $values; ?>;
    
    const ctx = document.getElementById('donutChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Budget Distribution',
                data: values,
                backgroundColor: ['#152a38', '#3E606F', '#2f5241', '#40514E', '#d6cfb9', '#C7B198', '#C7B198', '#e4e5db' ],
                hoverOffset: 4
            }]
        },
        options: {
            cutout: '60%',
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 0 
            },
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `${tooltipItem.label}: ${tooltipItem.raw}%`;
                        }
                    }
                }
            }
        }
    });
        
</script>

</body>
</html>
