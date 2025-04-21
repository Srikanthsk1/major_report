<style>
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow-x: hidden;
        overscroll-behavior: none;
    }

    /* Wrapper to handle layout */
    .wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Sidebar - fixed and non-scrollable */
    .main-sidebar {
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    /* Content area - scrollable */
    .content-wrapper {
        flex: 1;
        margin-left: 250px; /* Match sidebar width */
        overflow-y: auto;
        padding-bottom: 60px; /* Space for footer */
    }

    /* Footer - fixed at bottom */
    .main-footer {
        position: fixed;
        bottom: 0;
        width: calc(100% - 250px); /* Account for sidebar */
        margin-left: 250px;
        z-index: 1000;
        background: #f4f6f9;
        padding: 15px;
        border-top: 1px solid #dee2e6;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .content-wrapper {
            margin-left: 0;
        }
        .main-footer {
            width: 100%;
            margin-left: 0;
        }
    }

    /* DataTable adjustments */
    .dataTables_wrapper {
        padding-bottom: 40px; /* Extra space for footer */
    }

    /* Form styling */
    .card-body {
        padding-bottom: 20px;
    }
</style>

<?php
include_once 'connectdb.php';
session_start();

// if($_SESSION['useremail']=="" OR $_SESSION['role']=="User") {
//     header('location:../index.php');
// }

if($_SESSION['role']=="Admin") {
    include_once'header.php';
} else {
    include_once'headeruser.php';
}

if(isset($_POST['btnsave'])) {
    $sgst = $_POST['txtsgst'];
    $cgst = $_POST['txtcgst'];
    $discount = $_POST['txtdiscount'];

    if(empty($sgst)) {
        $_SESSION['status']="Field is Empty";
        $_SESSION['status_code']="warning";
    } else {
        $insert=$pdo->prepare("insert into tbl_taxdis (sgst,cgst,discount) values(:sgst,:cgst,:discount)");
        $insert->bindParam(':sgst',$sgst);
        $insert->bindParam(':cgst',$cgst);
        $insert->bindParam(':discount',$discount);

        if($insert->execute()) {
            $_SESSION['status']="Tax And Discount Added successfully";
            $_SESSION['status_code']="success";
        } else {
            $_SESSION['status']="Failed";
            $_SESSION['status_code']="warning";
        }
    }
}

if(isset($_POST['btnupdate'])) {
    $sgst = $_POST['txtsgst'];
    $cgst = $_POST['txtcgst'];
    $discount = $_POST['txtdiscount'];
    $id = $_POST['txtid'];
    
    if(empty($sgst)) {
        $_SESSION['status']="Field is Empty";
        $_SESSION['status_code']="warning";
    } else {
        $update=$pdo->prepare("update tbl_taxdis set sgst=:sgst,cgst=:cgst,discount=:dis where taxdis_id=".$id);
        $update->bindParam(':sgst',$sgst);
        $update->bindParam(':cgst',$cgst);
        $update->bindParam(':dis',$discount);

        if($update->execute()) {
            $_SESSION['status']="Tax And Dis Update successfully";
            $_SESSION['status_code']="success";
        } else {
            $_SESSION['status']="Failed";
            $_SESSION['status_code']="warning";
        }
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">TAX AND DISCOUNT</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0">Tax And Discount Form</h5>
                </div>
                
                <form action="" method="post">
                <div class="card-body">
                    <div class="row">
                        <?php
                        if(isset($_POST['btnedit'])) {
                            $select=$pdo->prepare("select * from tbl_taxdis where taxdis_id =".$_POST['btnedit']);
                            $select->execute();

                            if($select) {
                                $row=$select->fetch(PDO::FETCH_OBJ);
                                echo'<div class="col-md-4">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" placeholder="Enter Category" value="'.$row->taxdis_id.'" name="txtid">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">SGST(%)</label>
                                            <input type="text" class="form-control" placeholder="Enter SGST" value="'.$row->sgst.'" name="txtsgst">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">CGST(%)</label>
                                            <input type="text" class="form-control" placeholder="Enter CGST" value="'.$row->cgst.'" name="txtcgst">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Discount(%)</label>
                                            <input type="text" class="form-control" placeholder="Enter Discount" value="'.$row->discount.'" name="txtdiscount">
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary" name="btnupdate">Update</button>
                                        </div>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo'<div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">SGST(%)</label>
                                    <input type="text" class="form-control" placeholder="Enter SGST" name="txtsgst">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">CGST(%)</label>
                                    <input type="text" class="form-control" placeholder="Enter CGST" name="txtcgst">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Discount(%)</label>
                                    <input type="text" class="form-control" placeholder="Enter Discount" name="txtdiscount">
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" name="btnsave">Save</button>
                                </div>
                            </div>';
                        }
                        ?>

                        <div class="col-md-8">
                            <table id="table_tax" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>SGST</td>
                                        <td>CGST</td>
                                        <td>Discount</td>
                                        <td>Edit</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $select = $pdo->prepare("select * from tbl_taxdis order by taxdis_id ASC");
                                    $select->execute();

                                    while($row=$select->fetch(PDO::FETCH_OBJ)) {
                                        echo'
                                        <tr>
                                            <td>'.$row->taxdis_id.'</td>
                                            <td>'.$row->sgst.'</td>
                                            <td>'.$row->cgst.'</td>
                                            <td>'.$row->discount.'</td>
                                            <td>
                                                <button type="submit" class="btn btn-warning" value="'.$row->taxdis_id.'" name="btnedit">Edit</button>
                                            </td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>#</td>
                                        <td>SGST</td>
                                        <td>CGST</td>
                                        <td>Discount</td>
                                        <td>Edit</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>

<?php
if(isset($_SESSION['status']) && $_SESSION['status']!='') {
?>
<script>
    Swal.fire({
        icon: '<?php echo $_SESSION['status_code'];?>',
        title: '<?php echo $_SESSION['status'];?>'
    });
</script>


<script>
$(document).ready(function() {
    $('#table_tax').DataTable({
        responsive: true,
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false
    });
});
</script>
<?php
    unset($_SESSION['status']);
}
?>