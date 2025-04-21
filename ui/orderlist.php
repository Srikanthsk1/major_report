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
        flex: 1 0 auto; /* Take available space, push footer down */
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Ensure full viewport height */
    }
    .content-wrapper {
        flex: 1 0 auto; /* Fill available space */
        max-height: 600px; /* Reduced height */
        overflow-y: auto; /* Single scrollbar for content */
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
    /* Ensure inner containers adapt */
    .content, .container-fluid, .card, .card-body {
        height: auto;
        overflow: visible; /* Prevent nested scrollbars */
        width: 100%;
    }
    /* Table responsive adjustments */
    .table-responsive {
        overflow-y: visible; /* No vertical scrollbar */
        overflow-x: auto; /* Horizontal scrollbar only if needed */
        width: 100%;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 8px 16px;
        white-space: nowrap; /* Prevent text wrapping */
    }
    /* DataTable styling */
    #table_orderlist_wrapper {
        overflow: visible; /* Ensure DataTable controls don’t scroll separately */
    }
</style>
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

<!-- Content Wrapper. Contains page content -->
<div class="wrapper">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Order List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Order List</li> -->
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
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Orders</h5>
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
                                                <th>Repayment</th>
                                                <th>Payment Type</th>
                                                <th>Action Icons</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $select = $pdo->prepare("select * from tbl_invoice order by invoice_id ASC");
                                            $select->execute();
                                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                                echo '<tr>';
                                                echo '<td>' . $row->invoice_id . '</td>';
                                                echo '<td>' . $row->order_date . '</td>';
                                                echo '<td>' . $row->total . '</td>';
                                                echo '<td>' . $row->paid . '</td>';
                                                echo '<td>' . $row->due . '</td>';
                                                echo '<td>' . $row->repay . '</td>';
                                                if ($row->payment_type == "Cash") {
                                                    echo '<td><span class="badge badge-warning">' . $row->payment_type . '</span></td>';
                                                } else if ($row->payment_type == "Card") {
                                                    echo '<td><span class="badge badge-success">' . $row->payment_type . '</span></td>';
                                                } else {
                                                    echo '<td><span class="badge badge-danger">' . $row->payment_type . '</span></td>';
                                                }
                                                echo '<td>
                                                    <div class="btn-group">
                                                        <a href="printbill.php?id=' . $row->invoice_id . '" class="btn btn-warning" role="button" target="_blank"><span class="fa fa-print" style="color:#ffffff" data-toggle="tooltip" title="Print Bill"></span></a>
                                                        <a href="editorderpos.php?id=' . $row->invoice_id . '" class="btn btn-primary" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Order"></span></a>
                                                        <button id="' . $row->invoice_id . '" class="btn btn-danger btndelete"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Order"></span></button>
                                                    </div>
                                                </td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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



<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize DataTable
    $('#table_orderlist').DataTable({
        "order": [[0, "desc"]],
        "paging": true,
        "searching": true,
        "info": true,
        "scrollX": false // Disable DataTable’s horizontal scrolling to rely on .table-responsive
    });

    // Delete button handler
    $('.btndelete').click(function() {
        var tdh = $(this);
        var id = $(this).attr("id");

        Swal.fire({
            title: 'Do you want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ordertdelete.php',
                    type: 'post',
                    data: { pidd: id },
                    success: function(data) {
                        tdh.parents('tr').hide();
                        Swal.fire(
                            'Deleted!',
                            'Your Invoice has been deleted.',
                            'success'
                        );
                    }
                });
            }
        });
    });
});
</script>