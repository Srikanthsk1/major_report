<style>
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow-x: hidden;
        overscroll-behavior: none;
    }
    body {
        display: flex;
        flex-direction: column;
    }
    .wrapper {
        flex: 1 0 auto; /* Push footer down */
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Full viewport height */
    }
    .content-wrapper {
        flex: 1 0 auto; /* Fill available space */
        max-height: 600px; /* Reduced height */
        overflow-y: auto; /* Single scrollbar */
        padding: 20px; /* Consistent padding */
        box-sizing: border-box; /* Include padding in height */
    }
    .main-footer {
        flex-shrink: 0; /* Keep footer at bottom */
        width: 100%;
        background-color: #343a40; /* Dark footer */
        color: white;
        padding: 15px;
        text-align: center;
    }
    /* Prevent nested scrollbars */
    .content, .container-fluid, .card, .card-body, .chart {
        height: auto;
        overflow: visible;
        width: 100%;
    }
    /* Stat boxes */
    .small-box {
        overflow: visible;
        height: auto;
    }
    /* Charts */
    canvas {
        max-height: 250px !important;
        width: 100%;
    }
    /* Responsive adjustments */
    .row {
        margin: 0;
        width: 100%;
    }
</style>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "") {
    header('location:../index.php');
    exit();
}

if ($_SESSION['role'] == "Admin") {
    include_once 'header.php';
} else {
    include_once 'headeruser.php';
}

// Get date range from user input or use default (current year)
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : date('Y-01-01');
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : date('Y-12-31');
?>

<!-- Content Wrapper. Contains page content -->
<div class="wrapper">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Analytics Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Date Range Filter -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Date Range Filter
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="post" action="">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <input type="date" name="from_date" class="form-control"
                                                    value="<?= htmlspecialchars($from_date) ?>" max="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <input type="date" name="to_date" class="form-control"
                                                    value="<?= htmlspecialchars($to_date) ?>" max="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="margin-top: 32px;">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                                <a href="?" class="btn btn-default">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Get data for all charts based on date range
                // Total Sales Data
                $select_sales = $pdo->prepare("
                    SELECT DATE_FORMAT(order_date, '%Y-%m') as month, 
                           SUM(total) as total_sales 
                    FROM tbl_invoice 
                    WHERE order_date BETWEEN :from_date AND :to_date
                    GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                    ORDER BY month
                ");
                $select_sales->bindParam(':from_date', $from_date);
                $select_sales->bindParam(':to_date', $to_date);
                $select_sales->execute();

                $sales_months = [];
                $sales_totals = [];
                while ($row = $select_sales->fetch(PDO::FETCH_ASSOC)) {
                    $sales_months[] = $row['month'];
                    $sales_totals[] = (float) $row['total_sales'];
                }

                // Payment Methods Data
                $select_payments = $pdo->prepare("
                    SELECT payment_type, COUNT(*) as count, SUM(total) as total 
                    FROM tbl_invoice 
                    WHERE order_date BETWEEN :from_date AND :to_date
                    GROUP BY payment_type
                ");
                $select_payments->bindParam(':from_date', $from_date);
                $select_payments->bindParam(':to_date', $to_date);
                $select_payments->execute();

                $payment_types = [];
                $payment_counts = [];
                $payment_amounts = [];
                while ($row = $select_payments->fetch(PDO::FETCH_ASSOC)) {
                    $payment_types[] = $row['payment_type'];
                    $payment_counts[] = $row['count'];
                    $payment_amounts[] = (float) $row['total'];
                }

                // Top Selling Products
                $select_products = $pdo->prepare("
                    SELECT p.product, SUM(id.qty) as quantity 
                    FROM tbl_invoice_details id
                    JOIN tbl_product p ON id.product_id = p.pid
                    JOIN tbl_invoice i ON id.invoice_id = i.invoice_id
                    WHERE i.order_date BETWEEN :from_date AND :to_date
                    GROUP BY p.product
                    ORDER BY quantity DESC
                    LIMIT 5
                ");
                $select_products->bindParam(':from_date', $from_date);
                $select_products->bindParam(':to_date', $to_date);
                $select_products->execute();

                $top_products = [];
                $product_quantities = [];
                while ($row = $select_products->fetch(PDO::FETCH_ASSOC)) {
                    $top_products[] = $row['product'];
                    $product_quantities[] = (int) $row['quantity'];
                }

                // Product Wise Sales
                $select_product_sales = $pdo->prepare("
                    SELECT p.product, SUM(id.saleprice * id.qty) as total_sales
                    FROM tbl_invoice_details id
                    JOIN tbl_product p ON id.product_id = p.pid
                    JOIN tbl_invoice i ON id.invoice_id = i.invoice_id
                    WHERE i.order_date BETWEEN :from_date AND :to_date
                    GROUP BY p.product
                    ORDER BY total_sales DESC
                    LIMIT 5
                ");
                $select_product_sales->bindParam(':from_date', $from_date);
                $select_product_sales->bindParam(':to_date', $to_date);
                $select_product_sales->execute();

                $product_names = [];
                $product_sales = [];
                while ($row = $select_product_sales->fetch(PDO::FETCH_ASSOC)) {
                    $product_names[] = $row['product'];
                    $product_sales[] = (float) $row['total_sales'];
                }

                // Customer Demographics
                $select_customers = $pdo->prepare("
                    SELECT gender, COUNT(*) as count 
                    FROM tbl_customer 
                    GROUP BY gender
                ");
                $select_customers->execute();

                $genders = [];
                $gender_counts = [];
                while ($row = $select_customers->fetch(PDO::FETCH_ASSOC)) {
                    $genders[] = $row['gender'];
                    $gender_counts[] = (int) $row['count'];
                }

                // Customer Age Distribution
                $select_age = $pdo->prepare("
                    SELECT 
                        CASE 
                            WHEN age < 20 THEN 'Under 20'
                            WHEN age BETWEEN 20 AND 29 THEN '20-29'
                            WHEN age BETWEEN 30 AND 39 THEN '30-39'
                            WHEN age BETWEEN 40 AND 49 THEN '40-49'
                            WHEN age BETWEEN 50 AND 59 THEN '50-59'
                            ELSE '60+'
                        END as age_range,
                        COUNT(*) as count
                    FROM tbl_customer
                    GROUP BY age_range
                    ORDER BY age_range
                ");
                $select_age->execute();

                $age_ranges = [];
                $age_counts = [];
                while ($row = $select_age->fetch(PDO::FETCH_ASSOC)) {
                    $age_ranges[] = $row['age_range'];
                    $age_counts[] = (int) $row['count'];
                }

                // Inventory Status
                $select_inventory = $pdo->prepare("
                    SELECT product, stock 
                    FROM tbl_product 
                    ORDER BY stock DESC
                    LIMIT 5
                ");
                $select_inventory->execute();

                $inventory_products = [];
                $inventory_stocks = [];
                while ($row = $select_inventory->fetch(PDO::FETCH_ASSOC)) {
                    $inventory_products[] = $row['product'];
                    $inventory_stocks[] = (int) $row['stock'];
                }

                // Get summary statistics
                $select_stats = $pdo->prepare("
                    SELECT 
                        (SELECT COUNT(*) FROM tbl_customer) as total_customers,
                        (SELECT COUNT(*) FROM tbl_product) as total_products,
                        (SELECT SUM(total) FROM tbl_invoice WHERE order_date BETWEEN :from_date AND :to_date) as total_sales,
                        (SELECT COUNT(*) FROM tbl_invoice WHERE order_date BETWEEN :from_date AND :to_date) as total_orders
                ");
                $select_stats->bindParam(':from_date', $from_date);
                $select_stats->bindParam(':to_date', $to_date);
                $select_stats->execute();
                $stats = $select_stats->fetch(PDO::FETCH_ASSOC);
                ?>

                <!-- Small boxes (Stat boxes) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= number_format($stats['total_sales'] ?? 0, 2) ?></h3>
                                <p>Total Sales</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $stats['total_orders'] ?? 0 ?></h3>
                                <p>Total Orders</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $stats['total_customers'] ?? 0 ?></h3>
                                <p>Customer Registrations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= $stats['total_products'] ?? 0 ?></h3>
                                <p>Products</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-7 connectedSortable">
                        <!-- Sales Trend Chart -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-line mr-1"></i>
                                    Sales Trend (<?= date('M d, Y', strtotime($from_date)) ?> to
                                    <?= date('M d, Y', strtotime($to_date)) ?>)
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="salesChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Top Selling Products -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    Top Selling Products
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="productsChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Right col -->
                    <section class="col-lg-5 connectedSortable">
                        <!-- Payment Methods -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    Payment Methods
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="paymentChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Product Wise Sales -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Product Wise Sales
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="productSalesChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Second row -->
                <div class="row">
                    <!-- Customer Demographics -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-users mr-1"></i>
                                    Customer Demographics
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="genderChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Age Distribution -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-user-friends mr-1"></i>
                                    Customer Age Distribution
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="ageChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third row -->
                <div class="row">
                    <!-- Inventory Status -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-boxes mr-1"></i>
                                    Inventory Status (Top 5)
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="inventoryChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include_once "footer.php"; ?>
</div>



<!-- Load jQuery first (required for AdminLTE) -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function () {
    // Debug data in console
    console.log('Sales Data:', {
        months: <?= json_encode($sales_months) ?>,
        totals: <?= json_encode($sales_totals) ?>
    });

    // Sales Trend Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($sales_months) ?>,
                datasets: [{
                    label: 'Sales',
                    data: <?= json_encode($sales_totals) ?>,
                    backgroundColor: 'rgba(60, 141, 188, 0.2)',
                    borderColor: 'rgba(60, 141, 188, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentChart');
    if (paymentCtx) {
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($payment_types) ?>,
                datasets: [{
                    data: <?= json_encode($payment_amounts) ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });
    }

    // Top Products Chart
    const productsCtx = document.getElementById('productsChart');
    if (productsCtx) {
        new Chart(productsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($top_products) ?>,
                datasets: [{
                    label: 'Quantity Sold',
                    data: <?= json_encode($product_quantities) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Product Wise Sales Chart
    const productSalesCtx = document.getElementById('productSalesChart');
    if (productSalesCtx) {
        if (<?= json_encode($product_names) ?>.length > 0 && <?= json_encode($product_sales) ?>.length > 0) {
            new Chart(productSalesCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($product_names) ?>,
                    datasets: [{
                        label: 'Sales Amount',
                        data: <?= json_encode($product_sales) ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    label += '₹' + parseFloat(context.raw).toLocaleString(undefined, { minimumFractionDigits: 2 });
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: {
                                callback: function (value) {
                                    return '₹' + value.toLocaleString();
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        } else {
            productSalesCtx.parentNode.innerHTML = '<div class="alert alert-info">No product sales data available for the selected period.</div>';
        }
    }

    // Customer Gender Chart
    const genderCtx = document.getElementById('genderChart');
    if (genderCtx) {
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($genders) ?>,
                datasets: [{
                    data: <?= json_encode($gender_counts) ?>,
                    backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });
    }

    // Customer Age Chart
    const ageCtx = document.getElementById('ageChart');
    if (ageCtx) {
        new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($age_ranges) ?>,
                datasets: [{
                    label: 'Customers',
                    data: <?= json_encode($age_counts) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Inventory Status Chart
    const inventoryCtx = document.getElementById('inventoryChart');
    if (inventoryCtx) {
        new Chart(inventoryCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($inventory_products) ?>,
                datasets: [{
                    label: 'Stock',
                    data: <?= json_encode($inventory_stocks) ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>