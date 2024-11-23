<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- jsPDF -->
    <title>Merlie Rice Trading | Sales</title>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

    <div class="dashboard-container">
        <h1>Sales Report</h1>
        <p>Monitor your sales weekly, monthly, or even yearly from this page.</p>

        <!-- Sales Report Buttons and Table -->
        <div class="dashboard-container reports">
            <div class="button-container">
                <button onclick="showSalesReport('today')">Today's Sales</button>
                <button onclick="showSalesReport('week')">This Week's Sales</button>
                <button onclick="showSalesReport('month')">Monthly Sales</button>
                <button onclick="showSalesReport('year')">Annual Sales</button>
                <button onclick="showSalesReport('all')">All Sales</button>
            </div>
            <div id="salesReportTable">
                <!-- Sales report table will be dynamically inserted here -->
            </div>
            <div id="totalSales">
                <!-- Total sales will be displayed here -->
            </div>
        </div>

        <!-- Live Graph Reports -->
        <div class="dashboard-container reports graph-div">
            <h2 id="graphTitle">Today's Sales</h2>
            <canvas id="salesChart" width="200" height="100"></canvas>
            <button onclick="generateReport()">Generate Report</button>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Include the footer -->

    <script>
        let salesChart;

        function showSalesReport(type) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_sales_report.php?type=' + type, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    const response = JSON.parse(this.responseText);
                    let tableHTML = '<table>';
                    tableHTML += '<thead><tr><th>Item Name</th><th>Quantity</th><th>Total</th><th>Date</th><th>Profit</th></tr></thead>';
                    tableHTML += '<tbody>';
                    if (response.sales.length === 0) {
                        tableHTML += '<tr><td colspan="5">No sales made for the selected period.</td></tr>';
                    } else {
                        response.sales.forEach(sale => {
                            const profit = (sale.sub_total - (sale.sub_total / 1.1)).toFixed(2); // Calculate profit
                            tableHTML += `<tr><td>${sale.item_name}</td><td>${sale.quantity}</td><td>${sale.sub_total}</td><td>${sale.date}</td><td>${profit}</td></tr>`;
                        });
                    }
                    tableHTML += '</tbody></table>';
                    document.getElementById('salesReportTable').innerHTML = tableHTML;
                    document.getElementById('totalSales').innerText = 'Total Sales: ' + response.total;

                    // Update graph title
                    const graphTitle = document.getElementById('graphTitle');
                    switch (type) {
                        case 'today':
                            graphTitle.textContent = "Today's Sales";
                            break;
                        case 'week':
                            graphTitle.textContent = "This Week's Sales";
                            break;
                        case 'month':
                            graphTitle.textContent = "Monthly Sales";
                            break;
                        case 'year':
                            graphTitle.textContent = "Annual Sales";
                            break;
                        case 'all':
                            graphTitle.textContent = "All Sales";
                            break;
                    }

                    updateChart(response.sales);
                }
            };
            xhr.send();
        }

        function updateChart(data) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const labels = data.map(sale => sale.date);
            const values = data.map(sale => sale.sub_total);
            const profits = data.map(sale => (sale.sub_total - (sale.sub_total / 1.1)).toFixed(2)); // Calculate profits

            if (values.length === 0) {
                // Ensure graph is always visible with a flat line when no data
                labels.push('');
                values.push(0);
                profits.push(0);
            }

            if (salesChart) {
                salesChart.destroy();
            }

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: values,
                        backgroundColor: 'rgba(34, 139, 34, 0.2)', // Darker green with transparency for fill
                        borderColor: 'rgba(34, 139, 34, 1)', // Darker green for line
                        borderWidth: 3, // Thicker line
                        fill: true, // Fill below the line
                        tension: 0.4 // Smooth curve
                    }, {
                        label: 'Profit',
                        data: profits,
                        backgroundColor: 'rgba(0, 123, 255, 0.2)', // Blue with transparency for fill
                        borderColor: 'rgba(0, 123, 255, 1)', // Blue for line
                        borderWidth: 3, // Thicker line
                        fill: true, // Fill below the line
                        tension: 0.4 // Smooth curve
                    }]
                },
                options: {
                    title: {
                        text: 'Sales and Profit Chart',
                        display: true
                    },
                    events: [],
                    legend: {
                        display: true
                    },
                    tooltips: {
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function generateReport() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.text('Sales Report', 10, 10);
            // Further code to add more details to the PDF

            doc.save('sales_report.pdf');
        }

        // Show today's sales by default when the page loads
        window.onload = function() {
            showSalesReport('today');
        };
    </script>
</body>
</html>
