<?php
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1);
include_once 'connectdb.php';
session_start();
if ($_SESSION['useremail'] == "") {
    header('location:../index.php');
    exit;
}

if ($_SESSION['role'] == "Admin") {
    include_once 'header.php';
} else {
    include_once 'headeruser.php';
}

// Set default date range if not provided


$fromDate = null;
$toDate = null;
$dateFilterApplied = false;

if (!empty($_POST['date_1']) && !empty($_POST['date_2'])) {
    $fromDateTemp = DateTime::createFromFormat('Y-m-d', $_POST['date_1']);
    $toDateTemp = DateTime::createFromFormat('Y-m-d', $_POST['date_2']);
    if ($fromDateTemp && $toDateTemp && $toDateTemp >= $fromDateTemp) {
        $fromDate = $_POST['date_1'];
        $toDate = $_POST['date_2'];
        $dateFilterApplied = true;
    }
}
?>

<?php
if (strtotime($toDate) < strtotime($fromDate)) {
    $toDate = $fromDate;
}
?>
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<!-- daterange picker -->
<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">

<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

<style>
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
    }

    .wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .main-sidebar {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 250px;
        height: 100vh;
        overflow: hidden !important;
        z-index: 1000;
        background: #343a40;
    }

    .sidebar {
        height: 100%;
        max-height: 100vh;
        overflow: hidden !important;
        padding-bottom: 0;
    }

    .sidebar * {
        overflow: hidden !important;
    }

    .content-wrapper {
        flex: 1;
        overflow-y: auto;
        margin-left: 250px;
        padding-bottom: 60px;
        min-height: calc(100vh - 60px);
    }

    .main-footer {
        position: fixed;
        bottom: 0;
        width: calc(100% - 250px);
        margin-left: 250px;
        background: #f4f6f9;
        padding: 10px;
        border-top: 1px solid #dee2e6;
        z-index: 999;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px 0 rgba(0,0,0,0.1);
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 25px;
        border: none;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-weight: 600;
        color: #2c3e50;
        border-radius: 12px 12px 0 0 !important;
    }

    .date-filter-container {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-radius: 0 8px 8px 0;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        border: none;
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 20px;
        box-shadow: 0 4px 10px rgba(107, 115, 255, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(107, 115, 255, 0.4);
    }

    .chart-container {
        position: relative;
        height: 100%;
        min-height: 300px;
        padding: 15px;
    }

    .date-range-display {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
    }

    .no-data-message {
        text-align: center;
        color: #6c757d;
        font-size: 1.2rem;
        padding: 20px;
    }

    @media (max-width: 768px) {
        .chart-container {
            min-height: 250px;
        }
        .content-wrapper {
            margin-left: 0;
        }
        .main-footer {
            margin-left: 0;
            width: 100%;
        }
        .main-sidebar {
            width: 250px;
            transform: translateX(-250px);
            transition: transform 0.3s ease;
            height: 100vh;
            overflow: hidden !important;
        }
        .main-sidebar.active {
            transform: translateX(0);
        }
        .sidebar {
            max-height: 100vh;
            overflow: hidden !important;
        }
    }
</style>

<!-- Wrapper for layout -->
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Enhanced Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="dashboard-header">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0"><i class="fas fa-chart-line mr-2"></i>Analysis Report Dashboard</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <div class="date-range-display">
                                <i class="far fa-calendar-alt mr-2"></i>
                                FROM: <?php echo htmlspecialchars($fromDate); ?> — TO: <?php echo htmlspecialchars($toDate); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Date Filter Form -->
                <form method="post" action="" name="dateFilterForm">
                    <div class="date-filter-container">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">From Date</label>
                                    <div class="input-group date" id="date_1" data-target-input="nearest">
                                        <input type="text" class="form-control date_1" data-target="#date_1" name="date_1" placeholder="Select start date" value="<?php echo htmlspecialchars($fromDate); ?>"/>
                                        <div class="input-group-append" data-target="#date_1" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">To Date</label>
                                    <div class="input-group date" id="date_2" data-target-input="nearest">
                                        <input type="text" class="form-control date_2" data-target="#date_2" name="date_2" placeholder="Select end date" value="<?php echo htmlspecialchars($toDate); ?>"/>
                                        <div class="input-group-append" data-target="#date_2" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block" name="btnfilter">
                                    <i class="fas fa-filter mr-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <?php
                try {
                    // Daily Sales Total
                    $select = $pdo->prepare("SELECT order_date, SUM(total) as grandtotal FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate GROUP BY order_date");
                    $select->bindParam(':fromdate', $fromDate);
                    $select->bindParam(':todate', $toDate);
                    $select->execute();

                    $total = [];
                    $date = [];

                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        $total[] = $row['grandtotal'];
                        $date[] = $row['order_date'];
                    }
                    if (empty($total)) {
                        $total = [0];
                        $date = [$fromDate];
                    }

                    // Best Selling Products (by quantity)
                    $select = $pdo->prepare("SELECT product_name, SUM(qty) as q FROM tbl_invoice_details WHERE order_date BETWEEN :fromdate AND :todate GROUP BY product_id");
                    $select->bindParam(':fromdate', $fromDate);
                    $select->bindParam(':todate', $toDate);
                    $select->execute();

                    $pname = [];
                    $qty = [];

                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        $pname[] = $row['product_name'];
                        $qty[] = $row['q'];
                    }
                    if (empty($pname)) {
                        $pname = ['No Data'];
                        $qty = [0];
                    }

                    // Gender Distribution
                    $select = $pdo->prepare("SELECT gender, COUNT(*) as count FROM tbl_customer WHERE order_date BETWEEN :fromdate AND :todate GROUP BY gender");
                    $select->bindParam(':fromdate', $fromDate);
                    $select->bindParam(':todate', $toDate);
                    $select->execute();

                    $gender = [];
                    $count = [];

                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        $gender[] = $row['gender'];
                        $count[] = $row['count'];
                    }
                    if (empty($gender)) {
                        $gender = ['Male', 'Female'];
                        $count = [0, 0];
                    }

                    // Gender Distribution Over Time
                    $select = $pdo->prepare("SELECT DATE_FORMAT(order_date, '%Y-%m') as month, 
                                                   SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as male_count, 
                                                   SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as female_count 
                                            FROM tbl_customer 
                                            WHERE order_date BETWEEN :fromdate AND :todate 
                                            GROUP BY month");
                    $select->bindParam(':fromdate', $fromDate);
                    $select->bindParam(':todate', $toDate);
                    $select->execute();

                    $months = [];
                    $maleCounts = [];
                    $femaleCounts = [];

                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        $months[] = $row['month'];
                        $maleCounts[] = $row['male_count'];
                        $femaleCounts[] = $row['female_count'];
                    }
                    if (empty($months)) {
                        $months = [date('Y-m', strtotime($fromDate))];
                        $maleCounts = [0];
                        $femaleCounts = [0];
                    }

                    // City Based Sales Percentage
                    $select = $pdo->prepare("SELECT DATE_FORMAT(tbl_invoice.order_date, '%Y-%m') as month, 
                                                   tbl_customer.city, 
                                                   SUM(tbl_invoice.total) as sales 
                                            FROM tbl_customer 
                                            JOIN tbl_invoice ON tbl_customer.customer_id = tbl_invoice.customer_id 
                                            WHERE tbl_invoice.order_date BETWEEN :fromdate AND :todate 
                                            GROUP BY month, tbl_customer.city");
                    $select->bindParam(':fromdate', $fromDate);
                    $select->bindParam(':todate', $toDate);
                    $select->execute();

                    $citySalesData = [];
                    $cities = [];

                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        $month = $row['month'];
                        $city = $row['city'];
                        $sales = $row['sales'];
                        $citySalesData[$month][$city] = $sales;
                        if (!in_array($city, $cities)) {
                            $cities[] = $city;
                        }
                    }

                    // Generate all months between fromdate and todate
                    $months = [];
                    if (!empty($fromDate) && !empty($toDate)) {
                        $start = new DateTime($fromDate);
                        $end = new DateTime($toDate);
                        $interval = DateInterval::createFromDateString('1 month');
                        $period = new DatePeriod($start, $interval, $end->modify('+1 month'));
                        foreach ($period as $dt) {
                            $months[] = $dt->format('Y-m');
                        }
                    }
                    if (empty($months)) {
                        $months = [date('Y-m', strtotime($fromDate))];
                    }

                    // Calculate percentages
                    $salesPercentageData = [];
                    foreach ($months as $month) {
                        $totalMonthSales = array_sum($citySalesData[$month] ?? []);
                        $monthlyPercentages = [];
                        foreach ($cities as $city) {
                            $sales = $citySalesData[$month][$city] ?? 0;
                            $percentage = $totalMonthSales > 0 ? ($sales / $totalMonthSales) * 100 : 0;
                            $monthlyPercentages[] = round($percentage, 2);
                        }
                        $salesPercentageData[] = $monthlyPercentages;
                    }
                    if (empty($cities)) {
                        $cities = ['No City'];
                        $salesPercentageData = [[0]];
                    }

                    // Year-over-Year Sales Growth
                    $select = $pdo->prepare("SELECT YEAR(order_date) AS year, 
                                                   MONTH(order_date) AS month, 
                                                   SUM(total) AS sales 
                                            FROM tbl_invoice 
                                            WHERE order_date BETWEEN :fromdate AND :todate 
                                            GROUP BY year, month 
                                            ORDER BY year, month");
                    $select->bindParam(':fromdate', $fromDate);
                    $select->bindParam(':todate', $toDate);
                    $select->execute();

                    $yoyData = [];
                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        $yoyData[$row['year']][$row['month']] = $row['sales'];
                    }

                    $yoyMonths = [];
                    $lastYearSales = [];
                    $currentYearSales = [];

                    foreach ($yoyData as $year => $data) {
                        foreach ($data as $month => $sales) {
                            $monthLabel = date('F', mktime(0, 0, 0, $month, 10));
                            if (!in_array($monthLabel, $yoyMonths)) {
                                $yoyMonths[] = $monthLabel;
                            }
                            if ($year == date('Y', strtotime($fromDate)) - 1) {
                                $lastYearSales[] = $sales;
                            } elseif ($year == date('Y', strtotime($fromDate))) {
                                $currentYearSales[] = $sales;
                            }
                        }
                    }
                    if (empty($yoyMonths)) {
                        $yoyMonths = [date('F', strtotime($fromDate))];
                        $lastYearSales = [0];
                        $currentYearSales = [0];
                    }

                    // Customer Segmentation
                    $select = $pdo->prepare("SELECT tbl_customer.name, 
                                                   tbl_customer.phone, 
                                                   COUNT(tbl_invoice.invoice_id) AS purchase_count, 
                                                   SUM(tbl_invoice.total) AS total_spent
                                            FROM tbl_customer
                                            JOIN tbl_invoice ON tbl_customer.customer_id = tbl_invoice.customer_id
                                            WHERE tbl_invoice.order_date BETWEEN :fromdate AND :todate
                                            GROUP BY tbl_customer.customer_id
                                            ORDER BY total_spent DESC");
                    $select->bindParam(':fromdate', $fromDate);
                    $select->bindParam(':todate', $toDate);
                    $select->execute();

                    $customerNames = [];
                    $customerPhones = [];
                    $purchaseCounts = [];
                    $totalSpent = [];

                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        $customerNames[] = $row['name'];
                        $customerPhones[] = $row['phone'];
                        $purchaseCounts[] = $row['purchase_count'];
                        $totalSpent[] = $row['total_spent'];
                    }
                    if (empty($customerNames)) {
                        $customerNames = ['No Customers'];
                        $customerPhones = ['N/A'];
                        $purchaseCounts = [0];
                        $totalSpent = [0];
                    }
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                    // Initialize fallback data
                    $total = [0];
                    $date = [$fromDate];
                    $pname = ['No Data'];
                    $qty = [0];
                    $gender = ['Male', 'Female'];
                    $count = [0, 0];
                    $months = [date('Y-m', strtotime($fromDate))];
                    $maleCounts = [0];
                    $femaleCounts = [0];
                    $cities = ['No City'];
                    $salesPercentageData = [[0]];
                    $yoyMonths = [date('F', strtotime($fromDate))];
                    $lastYearSales = [0];
                    $currentYearSales = [0];
                    $customerNames = ['No Customers'];
                    $customerPhones = ['N/A'];
                    $purchaseCounts = [0];
                    $totalSpent = [0];
                }
                ?>

                <!-- Charts Section -->
                <div class="row">
                    <!-- Best Selling Products -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-star mr-2"></i>Best Selling Products</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <?php if (empty($pname) || $pname[0] == 'No Data') : ?>
                                        <div class="no-data-message">No data available for the selected date range.</div>
                                    <?php else : ?>
                                        <canvas id="bestsellingproduct"></canvas>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Quantity Distribution -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Product Quantity Distribution</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <?php if (empty($pname) || $pname[0] == 'No Data') : ?>
                                        <div class="no-data-message">No data available for the selected date range.</div>
                                    <?php else : ?>
                                        <canvas id="myPieChart"></canvas>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Gender Distribution -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-venus-mars mr-2"></i>Gender Distribution</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <?php if (empty($gender) || array_sum($count) == 0) : ?>
                                        <div class="no-data-message">No gender data available for the selected date range.</div>
                                    <?php else : ?>
                                        <canvas id="demograph1"></canvas>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gender Distribution Over Time -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Gender Distribution Over Time</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <?php if (empty($months) || (array_sum($maleCounts) == 0 && array_sum($femaleCounts) == 0)) : ?>
                                        <div class="no-data-message">No gender data available for the selected date range.</div>
                                    <?php else : ?>
                                        <canvas id="genderChart"></canvas>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- City Based Sales Percentage -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-city mr-2"></i>City Based Sales Percentage by Month</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="min-height: 400px;">
                                    <?php if (empty($cities) || $cities[0] == 'No City') : ?>
                                        <div class="no-data-message">No city sales data available for the selected date range.</div>
                                    <?php else : ?>
                                        <canvas id="citySalesPercentageChart"></canvas>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Year-over-Year Growth -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Year-over-Year (YoY) Sales Growth</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="min-height: 400px;">
                                    <?php if (empty($yoyMonths) || (array_sum($lastYearSales) == 0 && array_sum($currentYearSales) == 0)) : ?>
                                        <div class="no-data-message">No sales data available for YoY comparison.</div>
                                    <?php else : ?>
                                        <canvas id="yoyGrowthChart"></canvas>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Segmentation -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-users mr-2"></i>Customer Segmentation</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="min-height: 450px;">
                                    <?php if (empty($customerNames) || $customerNames[0] == 'No Customers') : ?>
                                        <div class="no-data-message">No customer data available for the selected date range.</div>
                                    <?php else : ?>
                                        <canvas id="customerSegmentationChart"></canvas>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once "footer.php"; ?>
</div>

<script>
    // Debugging: Log data to console
    console.log('Best Selling Products:', <?php echo json_encode($pname); ?>, <?php echo json_encode($qty); ?>);
    console.log('Gender Distribution:', <?php echo json_encode($gender); ?>, <?php echo json_encode($count); ?>);
    console.log('Gender Over Time:', <?php echo json_encode($months); ?>, <?php echo json_encode($maleCounts); ?>, <?php echo json_encode($femaleCounts); ?>);
    console.log('City Sales:', <?php echo json_encode($cities); ?>, <?php echo json_encode($salesPercentageData); ?>);
    console.log('YoY Sales:', <?php echo json_encode($yoyMonths); ?>, <?php echo json_encode($lastYearSales); ?>, <?php echo json_encode($currentYearSales); ?>);
    console.log('Customer Segmentation:', <?php echo json_encode($customerNames); ?>, <?php echo json_encode($totalSpent); ?>);

    // Best Selling Products Chart
    if (document.getElementById('bestsellingproduct')) {
        const ctx1 = document.getElementById('bestsellingproduct');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($pname); ?>,
                datasets: [{
                    label: 'Product Quantity',
                    backgroundColor: 'rgb(102,255,102)',
                    borderColor: 'rgb(0,102,0)',
                    data: <?php echo json_encode($qty); ?>,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Best Selling Products'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Product Quantity Distribution Pie Chart
    if (document.getElementById('myPieChart')) {
        const ctxPie = document.getElementById('myPieChart');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($pname); ?>,
                datasets: [{
                    label: 'Product Quantity',
                    data: <?php echo json_encode($qty); ?>,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Product Quantity Distribution'
                    }
                }
            }
        });
    }

    // Gender Distribution Doughnut Chart
    if (document.getElementById('demograph1')) {
        const ctxPie1 = document.getElementById('demograph1');
        new Chart(ctxPie1, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($gender); ?>,
                datasets: [{
                    label: 'Gender Distribution',
                    data: <?php echo json_encode($count); ?>,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(75, 192, 192)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Gender Distribution'
                    }
                }
            }
        });
    }

    // Gender Distribution Over Time Line Chart
    if (document.getElementById('genderChart')) {
        const ctxGender = document.getElementById('genderChart').getContext('2d');
        new Chart(ctxGender, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [
                    {
                        label: 'Male',
                        data: <?php echo json_encode($maleCounts); ?>,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.2)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Female',
                        data: <?php echo json_encode($femaleCounts); ?>,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.2)',
                        borderWidth: 2,
                        fill: true
                    }
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Gender Distribution by Month'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        });
    }

    // City Based Sales Percentage Stacked Area Chart
    if (document.getElementById('citySalesPercentageChart')) {
        const ctxCityPercentage = document.getElementById('citySalesPercentageChart').getContext('2d');
        const months = <?php echo json_encode($months); ?>;
        const cities = <?php echo json_encode($cities); ?>;
        const salesPercentageData = <?php echo json_encode($salesPercentageData); ?>;

        const datasets = cities.map((city, index) => {
            const cityPercentages = salesPercentageData.map(monthlyPercentages => monthlyPercentages[index]);
            return {
                label: city,
                data: cityPercentages,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ][index % 6],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ][index % 6],
                borderWidth: 1,
                fill: false,
            };
        });

        new Chart(ctxCityPercentage, {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'City Based Sales Percentage by Month'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y.toFixed(2) + '%';
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        stacked: true,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            },
                            font: {
                                size: 14
                            }
                        },
                        title: {
                            display: true,
                            text: 'Percentage of Sales'
                        }
                    },
                    x: {
                        stacked: true,
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 0,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    }

    // Year-over-Year Growth Line Chart
    if (document.getElementById('yoyGrowthChart')) {
        const ctxYoY = document.getElementById('yoyGrowthChart').getContext('2d');
        const yoyMonths = <?php echo json_encode($yoyMonths); ?>;
        const lastYearSales = <?php echo json_encode($lastYearSales); ?>;
        const currentYearSales = <?php echo json_encode($currentYearSales); ?>;

        new Chart(ctxYoY, {
            type: 'line',
            data: {
                labels: yoyMonths,
                datasets: [
                    {
                        label: `Sales for ${new Date().getFullYear() - 1}`,
                        data: lastYearSales,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.2)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: `Sales for ${new Date().getFullYear()}`,
                        data: currentYearSales,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 255, 0, 0.2)',
                        borderWidth: 2,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Year-over-Year (YoY) Sales Growth'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    }

    // Customer Segmentation Bar Chart
    if (document.getElementById('customerSegmentationChart')) {
        const ctxCustomerSegmentation = document.getElementById('customerSegmentationChart').getContext('2d');
        const customerNames = <?php echo json_encode($customerNames); ?>;
        const totalSpent = <?php echo json_encode($totalSpent); ?>;

        function generateColor(index, total) {
            const hue = (index * (360 / total)) % 360;
            return `hsl(${hue}, 70%, 50%)`;
        }

        const dynamicColors = customerNames.map((_, index) => generateColor(index, customerNames.length));

        new Chart(ctxCustomerSegmentation, {
            type: 'bar',
            data: {
                labels: customerNames,
                datasets: [
                    {
                        label: 'Total Spent ($)',
                        data: totalSpent,
                        backgroundColor: dynamicColors.map(color => color.replace(')', ', 0.6)')).map(color => color.replace('hsl', 'hsla')),
                        borderColor: dynamicColors,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    title: {
                        display: true,
                        text: 'Customer Segmentation (Total Spent)'
                    }
                }
            }
        });
    }
</script>

<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
    // Date picker initialization
    $(document).ready(function() {
        $('#date_1').datetimepicker({
            format: 'YYYY-MM-DD',
            
        });

        $('#date_2').datetimepicker({
            format: 'YYYY-MM-DD',
            
        });
    });
</script>