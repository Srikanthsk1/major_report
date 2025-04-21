<style>
    /* Fix for footer positioning and content height */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    
    .wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Ensure wrapper takes full viewport height */
    }
    
    .content-wrapper {
        flex: 1 0 auto; /* Grow to fill available space */
        max-height: 600px; /* Reduce content height */
        overflow-y: auto; /* Allow scrolling if content overflows */
        padding: 20px; /* Consistent padding */
        box-sizing: border-box; /* Include padding in height */
       max-width: 600px;
    }
    
    .main-footer {
        background: #343a40;
        color: white;
        padding: 15px;
        position: relative;
        height: 50px;
        text-align: center;
        flex-shrink: 0; /* Prevent footer from shrinking */
    }
    
    /* Adjust for sidebar */
    .sidebar-mini .main-footer {
        margin-left: 250px;
        width: calc(100% - 250px);
    }
    
    /* For collapsed sidebar */
    .sidebar-collapse .main-footer {
        margin-left: 60px;
        width: calc(100% - 60px);
    }
    
    /* Form and card styling */
    .card {
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    /* Ensure inner containers don’t break layout */
    .content, .container-fluid {
        height: auto;
        overflow: visible;
    }
    
    /* Fix potential scrollbar issues */
    .content-wrapper, .content, .card {
        width: 100%;
        max-width: 95%;
    }
</style>

<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "") {
    header('location:../index.php');
}

include_once "header.php";

$select = $pdo->prepare("select sum(total) as gt , count(invoice_id) as invoice from tbl_invoice");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);
$total_order = $row->invoice;
$grand_total = $row->gt;

$select = $pdo->prepare("select count(product) as pname from tbl_product");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);
$total_product = $row->pname;

$select = $pdo->prepare("select count(category) as cate from tbl_category");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);
$total_category = $row->cate;
?>

<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="wrapper">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Admin Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- Breadcrumb items -->
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <!-- Small boxes -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3><?php echo $total_order; ?></h3>
                                        <p>TOTAL INVOICE</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="orderlist.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?php echo number_format($grand_total, 2); ?></h3>
                                        <p>TOTAL REVENUE(INR)</p>
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
                                        <h3><?php echo $total_product; ?></h3>
                                        <p>TOTAL PRODUCT</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                    <a href="productlist.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3><?php echo $total_category; ?></h3>
                                        <p>TOTAL CATEGORY</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-pie-graph"></i>
                                    </div>
                                    <a href="category.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Earning By Date Chart -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Earning By Date</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $select = $pdo->prepare("select order_date, total from tbl_invoice group by order_date LIMIT 50");
                                $select->execute();
                                $ttl = [];
                                $date = [];
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    $ttl[] = $row['total'];
                                    $date[] = $row['order_date'];
                                }
                                ?>
                                <div>
                                    <canvas id="myChart" style="height: 250px"></canvas>
                                </div>
                                <script>
                                    const ctx = document.getElementById('myChart');
                                    new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: <?php echo json_encode($date); ?>,
                                            datasets: [{
                                                label: 'Total Earning',
                                                backgroundColor: 'rgb(255,99,132)',
                                                borderColor: 'rgb(255,99,132)',
                                                data: <?php echo json_encode($ttl); ?>,
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        </div>

                        <!-- Bottom Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5 class="m-0">BEST SELLING PRODUCT</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped table-hover" id="table_bestsellingproduct">
                                            <thead>
                                                <tr>
                                                    <td>Product ID</td>
                                                    <td>Product Name</td>
                                                    <td>QTY</td>
                                                    <td>Rate</td>
                                                    <td>Total</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $select = $pdo->prepare("select product_id,product_name,rate,sum(qty) as q, sum(saleprice) as total from tbl_invoice_details group by product_id order by sum(qty) DESC LIMIT 10");
                                                $select->execute();
                                                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                                    echo '<tr>
                                                        <td>' . $row->product_id . '</td>
                                                        <td><span class="badge badge-dark">' . $row->product_name . '</span></td>
                                                        <td><span class="badge badge-success">' . $row->q . '</span></td>
                                                        <td><span class="badge badge-primary">' . $row->rate . '</span></td>
                                                        <td><span class="badge badge-danger">' . $row->total . '</span></td>
                                                    </tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5 class="m-0">Recent Orders</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_recentorder">
                                                <thead>
                                                    <tr>
                                                        <td>Invoice ID</td>
                                                        <td>Order Date</td>
                                                        <td>Total</td>
                                                        <td>Payment Type</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $select = $pdo->prepare("select * from tbl_invoice order by invoice_id DESC LIMIT 30");
                                                    $select->execute();
                                                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td><a href="editorderpos.php?id=' . $row->invoice_id . '">' . $row->invoice_id . '</a></td>
                                                            <td><span class="badge badge-dark">' . $row->order_date . '</span></td>
                                                            <td><span class="badge badge-danger">' . $row->total . '</span></td>';
                                                        if ($row->payment_type == "Cash") {
                                                            echo '<td><span class="badge badge-warning">' . $row->payment_type . '</span></td>';
                                                        } else if ($row->payment_type == "Card") {
                                                            echo '<td><span class="badge badge-success">' . $row->payment_type . '</span></td>';
                                                        } else {
                                                            echo '<td><span class="badge badge-danger">' . $row->payment_type . '</span></td>';
                                                        }
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "footer.php"; ?>
</div>

<script>
    $(document).ready(function() {
        $('#table_recentorder').DataTable({
            "order": [[0, "desc"]]
        });
        $('#table_bestsellingproduct').DataTable({
            "order": [[3, "desc"]]
        });
    });
</script>