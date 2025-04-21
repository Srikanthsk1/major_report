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
        max-height: 600px; /* Reduce content height */
        overflow-y: auto; /* Allow scrolling if content overflows */
        padding: 20px; /* Consistent padding */
        box-sizing: border-box; /* Include padding in height */
    }
    .main-footer {
        flex-shrink: 0; /* Keep footer at bottom */
        width: 100%;
        background-color: #343a40; /* Dark footer for visibility */
        color: white;
        padding: 15px;
        text-align: center;
    }
    /* Ensure inner containers adapt */
    .content, .container-fluid, .card {
        height: auto;
        overflow: visible;
        width: 100%;
    }
    /* Table responsiveness */
    .table-responsive {
        max-width: 100%;
        overflow-x: auto;
    }
</style>
<?php
include_once 'connectdb.php';
session_start();

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
                        <!-- Optional: Add title here if needed -->
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Starter Page</li> -->
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Product List :</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table_product">
                                        <thead>
                                            <tr>
                                                <td>Barcode</td>
                                                <td>Product</td>
                                                <td>Category</td>
                                                <td>Description</td>
                                                <td>Stock</td>
                                                <td>PurchasePrice</td>
                                                <td>SalePrice</td>
                                                <td>Image</td>
                                                <td>ActionIcons</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $select = $pdo->prepare("select * from tbl_product order by pid ASC");
                                            $select->execute();

                                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                                echo '
                                                <tr>
                                                    <td>' . $row->barcode . '</td>
                                                    <td>' . $row->product . '</td>
                                                    <td>' . $row->category . '</td>
                                                    <td>' . $row->description . '</td>
                                                    <td>' . $row->stock . '</td>
                                                    <td>' . $row->purchaseprice . '</td>
                                                    <td>' . $row->saleprice . '</td>
                                                    <td><img src="productimages/' . $row->image . '" class="img-rounded" width="40px" height="40px" alt="Product Image"></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="printbarcode.php?id=' . $row->pid . '" class="btn btn-dark btn-s" role="button"><span class="fa fa-barcode" style="color:#ffffff" data-toggle="tooltip" title="PrintBarcode"></span></a>
                                                            <a href="viewproduct.php?id=' . $row->pid . '" class="btn btn-warning btn-s" role="button"><span class="fa fa-eye" style="color:#ffffff" data-toggle="tooltip" title="View Product"></span></a>
                                                            <a href="editproduct.php?id=' . $row->pid . '" class="btn btn-success btn-s" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"></span></a>
                                                            <button id="' . $row->pid . '" class="btn btn-danger btn-s btndelete"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
                                                        </div>
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
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
     
<?php
include_once "footer.php";
?>
</div>
<!-- /.wrapper -->


<script>
$(document).ready(function() {
    $('#table_product').DataTable();
    $('[data-toggle="tooltip"]').tooltip();

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
                    url: 'productdelete.php',
                    type: 'post',
                    data: {
                        pidd: id
                    },
                    success: function(data) {
                        tdh.parents('tr').hide();
                    }
                });

                Swal.fire(
                    'Deleted!',
                    'Your Product has been deleted.',
                    'success'
                );
            }
        });
    });
});
</script>