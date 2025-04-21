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
    .content, .container-fluid, .card, .card-body {
        height: auto;
        overflow: visible;
        width: 100%;
    }
    /* Table styling */
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 8px 16px;
        white-space: nowrap; /* Prevent wrapping */
    }
    /* DataTable wrapper */
    #table_report_wrapper {
        overflow: visible; /* No separate scrollbar */
    }
    /* Responsive table */
    .dataTables_scrollBody {
        overflow-y: visible !important; /* Prevent DataTable scrolling */
    }
</style>
<?php
error_reporting(0);
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "") {
    header('location:../index.php');
}

if ($_SESSION['role'] == "Admin") {
    include_once 'header.php';
} else {
    include_once 'headeruser.php';
}
?>

<!-- daterange picker -->
<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

<!-- Content Wrapper. Contains page content -->
<div class="wrapper">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Table Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Table Report</li> -->
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
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h5 class="m-0">FROM: <?php echo htmlspecialchars($_POST['date_1'] ?? 'Not set'); ?> -- TO: <?php echo htmlspecialchars($_POST['date_2'] ?? 'Not set'); ?></h5>
                            </div>
                            <form action="" method="post" name="reportForm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="input-group date" id="date_1" data-target-input="nearest">
                                                    <input type="text" class="form-control date_1" data-target="#date_1" name="date_1"/>
                                                    <div class="input-group-append" data-target="#date_1" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="input-group date" id="date_2" data-target-input="nearest">
                                                    <input type="text" class="form-control date_2" data-target="#date_2" name="date_2"/>
                                                    <div class="input-group-append" data-target="#date_2" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary" name="btnfilter">Filter Records</button>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                    $from_date = $_POST['date_1'] ?? date('Y-m-d', strtotime('-30 days'));
                                    $to_date = $_POST['date_2'] ?? date('Y-m-d');
                                    $select = $pdo->prepare("select sum(total) as grandtotal, sum(subtotal) as stotal, count(invoice_id) as invoice from tbl_invoice where order_date between :fromdate AND :todate");
                                    $select->bindParam(':fromdate', $from_date);
                                    $select->bindParam(':todate', $to_date);
                                    $select->execute();
                                    $row = $select->fetch(PDO::FETCH_OBJ);
                                    $grand_total = $row->grandtotal ?? 0;
                                    $subtotal = $row->stotal ?? 0;
                                    $invoice = $row->invoice ?? 0;
                                    ?>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">TOTAL INVOICES</span>
                                                    <span class="info-box-number"><h2><?php echo number_format($invoice, 0); ?></h2></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <div class="info-box mb-3">
                                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-file"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">SUBTOTAL</span>
                                                    <span class="info-box-number"><h2><?php echo number_format($subtotal, 2); ?></h2></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix hidden-md-up"></div>
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <div class="info-box mb-3">
                                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">GRAND TOTAL</span>
                                                    <span class="info-box-number"><h2><?php echo number_format($grand_total, 2); ?></h2></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <table class="table table-striped table-hover" id="table_report">
                                        <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>Order Date</th>
                                                <th>Subtotal</th>
                                                <th>Discount(%)</th>
                                                <th>SGST(%)</th>
                                                <th>CGST(%)</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Due</th>
                                                <th>Payment Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $select = $pdo->prepare("select * from tbl_invoice where order_date between :fromdate AND :todate");
                                            $select->bindParam(':fromdate', $from_date);
                                            $select->bindParam(':todate', $to_date);
                                            $select->execute();
                                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($row->invoice_id) . '</td>';
                                                echo '<td>' . htmlspecialchars($row->order_date) . '</td>';
                                                echo '<td>' . htmlspecialchars($row->subtotal) . '</td>';
                                                echo '<td>' . htmlspecialchars($row->discount) . '</td>';
                                                echo '<td>' . htmlspecialchars($row->sgst) . '</td>';
                                                echo '<td>' . htmlspecialchars($row->cgst) . '</td>';
                                                echo '<td><span class="badge badge-danger">' . htmlspecialchars($row->total) . '</span></td>';
                                                echo '<td>' . htmlspecialchars($row->paid) . '</td>';
                                                echo '<td>' . htmlspecialchars($row->due) . '</td>';
                                                if ($row->payment_type == "Cash") {
                                                    echo '<td><span class="badge badge-warning">' . htmlspecialchars($row->payment_type) . '</span></td>';
                                                } else if ($row->payment_type == "Card") {
                                                    echo '<td><span class="badge badge-success">' . htmlspecialchars($row->payment_type) . '</span></td>';
                                                } else {
                                                    echo '<td><span class="badge badge-danger">' . htmlspecialchars($row->payment_type) . '</span></td>';
                                                }
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.col-md-12 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include_once "footer.php"; ?>
</div>



<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script>
// Date picker
$('#date_1').datetimepicker({
    format: 'YYYY-MM-DD'
});
$('#date_2').datetimepicker({
    format: 'YYYY-MM-DD'
});
</script>

<script>
$(document).ready(function() {
    $('#table_report').DataTable({
        responsive: true,
        "order": [[0, "desc"]],
        "paging": true,
        "searching": true,
        "info": true,
        "scrollX": false // Disable DataTable horizontal scrolling
    });
});
</script>