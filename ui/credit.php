<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "" || $_SESSION['role'] == "") {
    header('location:../index.php');
}

if ($_SESSION['role'] == "Admin") {
    include_once 'header.php';
} else {
    include_once 'headeruser.php';
}
?>

<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden; /* Prevent page scroll */
}

.wrapper {
    display: flex;
    height: 100vh;
    width: 100%;
}

/* Sidebar fixed */
.main-sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    background: #343a40;
    color: #fff;
    z-index: 1000;
}

/* Content section */
.content-wrapper {
    margin-left: 250px;
    flex: 1;
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden;
}

/* Scrollable main area */
.content {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f4f7fa;
}

/* Sticky footer */
.main-footer {
    padding: 10px 20px;
    background: #fff;
    border-top: 1px solid #dee2e6;
    position: sticky;
    bottom: 0;
    z-index: 1;
    margin-left: 250px;
    width: calc(100% - 250px);
}

/* Responsive sidebar handling */
@media (max-width: 768px) {
    .main-sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .content-wrapper,
    .main-footer {
        margin-left: 0;
        width: 100%;
    }
}

/* Table responsiveness */
.table-responsive {
    overflow-x: auto;
}

/* Card styling */
.card {
    margin-bottom: 20px;
}

.table.dataTable {
    width: 100% !important;
}
</style>

<div class="wrapper">
    <!-- Sidebar is already handled via included header -->

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Credit Orders</h5>
                                <button id="btnNotifyAll" class="btn btn-info float-right">
                                    <span class="fa fa-bell" style="color:#ffffff"></span> Send Notifications
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table_orderlist">
                                        <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>Order Date</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Due</th>
                                                <th>Payment Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $select = $pdo->prepare("
                                                SELECT i.invoice_id, i.order_date, i.total, i.paid, i.due, i.payment_type
                                                FROM tbl_invoice i
                                                INNER JOIN customer_ids c ON i.invoice_id = c.invoice_id
                                                WHERE i.payment_type = 'CREDIT'
                                                ORDER BY i.invoice_id DESC
                                            ");
                                            $select->execute();

                                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                                echo '
                                                <tr data-invoice-id="'.$row->invoice_id.'">
                                                    <td>'.$row->invoice_id.'</td>
                                                    <td>'.$row->order_date.'</td>
                                                    <td>'.$row->total.'</td>
                                                    <td>'.$row->paid.'</td>
                                                    <td>'.$row->due.'</td>
                                                    <td><span class="badge badge-danger">'.$row->payment_type.'</span></td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm btndelete" data-id="'.$row->invoice_id.'">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="m-2 p-2">Credit Payment History</h3>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table_history">
                                        <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>Order Date</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Due</th>
                                                <th>Payment Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $select_history = $pdo->prepare("
                                                SELECT * FROM tbl_invoice_history 
                                                WHERE payment_type = 'CREDIT' 
                                                ORDER BY invoice_id DESC
                                            ");
                                            $select_history->execute();

                                            while ($row = $select_history->fetch(PDO::FETCH_OBJ)) {
                                                echo '
                                                <tr>
                                                    <td>'.$row->invoice_id.'</td>
                                                    <td>'.$row->order_date.'</td>
                                                    <td>'.$row->total.'</td>
                                                    <td>'.$row->paid.'</td>
                                                    <td>'.$row->due.'</td>
                                                    <td><span class="badge badge-danger">'.$row->payment_type.'</span></td>
                                                </tr>';
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

        <?php include_once "footer.php"; ?>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#table_orderlist').DataTable({
        "order": [[0, "desc"]]
    });

    $('#table_history').DataTable({
        "order": [[0, "desc"]]
    });

    $('#btnNotifyAll').click(function () {
        if (confirm('Send notifications for all credit payments?')) {
            $.ajax({
                url: 'run_batch_file.php',
                type: 'POST',
                success: function (response) {
                    alert('Notifications sent successfully');
                },
                error: function () {
                    alert('Error sending notifications');
                }
            });
        }
    });

    $('#table_orderlist').on('click', '.btndelete', function () {
        var invoiceId = $(this).data('id');
        var row = $(this).closest('tr');

        if (confirm('Remove this invoice from notifications?')) {
            $.ajax({
                url: 'delete_invoice.php',
                type: 'POST',
                data: { invoice_id: invoiceId },
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res.success) {
                        row.remove();
                        alert(res.message);
                    } else {
                        alert(res.message);
                    }
                }
            });
        }
    });
});
</script>
