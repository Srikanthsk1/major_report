<?php
include_once 'connectdb.php';
session_start();

if($_SESSION['role']=="Admin"){
    include_once'header.php';
}else{
    include_once'headeruser.php';
}

if(isset($_POST['btnsave'])){
    // Your existing form processing code remains the same
    // ...
}
?>

<style>
    /* Fix for footer positioning */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    
    .wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Use viewport height */
    }
    
    .content-wrapper {
        flex: 1;
        padding-bottom: 60px; /* Space for footer */
    }
    
    .main-footer {
        background:rgb(255, 255, 255);
        color: white;
        padding: 15px;
        position: relative;
        margin-top: -50px; /* Negative margin to pull footer up */
        height: 50px;
        color: black; /* Updated color to match footer changes */
        text-align: center;
        clear: both;
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
    
    /* Form styling */
    .card {
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Product</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- Breadcrumb items if needed -->
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
                            <h5 class="m-0">Product</h5>
                        </div>
                        
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Barcode</label>
                                            <input type="text" class="form-control" placeholder="Enter Barcode" name="txtbarcode" autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Name" name="txtproductname" autocomplete="off" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control" name="txtselect_option" required>
                                                <option value="" disabled selected>Select Category</option>
                                                <?php
                                                $select=$pdo->prepare("select * from tbl_category order by catid desc");
                                                $select->execute();
                                                while($row=$select->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option>'.$row['category'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" placeholder="Enter Description" name="txtdescription" rows="4" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stock Quantity</label>
                                            <input type="number" min="1" step="any" class="form-control" placeholder="Enter Stock" name="txtstock" autocomplete="off" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Purchase Price</label>
                                            <input type="number" min="1" step="any" class="form-control" placeholder="Enter Purchase Price" name="txtpurchaseprice" autocomplete="off" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Sale Price</label>
                                            <input type="number" min="1" step="any" class="form-control" placeholder="Enter Sale Price" name="txtsaleprice" autocomplete="off" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Product image</label>
                                            <input type="file" class="form-control-file" name="myfile" required>
                                            <small class="form-text text-muted">Upload product image (jpg, jpeg, png, gif)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" name="btnsave">Save Product</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include_once "footer.php"; ?>

<?php
if(isset($_SESSION['status']) && $_SESSION['status']!='') {
?>
<script>
    Swal.fire({
        icon: '<?php echo $_SESSION['status_code']; ?>',
        title: '<?php echo $_SESSION['status']; ?>'
    });
</script>
<?php
    unset($_SESSION['status']);
}
?>