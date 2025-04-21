<style>
    html {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overscroll-behavior: none;
    }

    body {
        margin: 0;
        padding: 0;
        height: 100vh;
    }

    /* Wrapper to handle layout */
    .wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Full viewport height */
    }

    /* Sidebar - fixed and non-scrollable */
    .main-sidebar {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 250px; /* AdminLTE sidebar width */
        height: 100vh; /* Full viewport height */
        overflow: hidden !important; /* Prevent scrolling */
        z-index: 1000;
        background: #343a40; /* AdminLTE sidebar background */
    }

    /* Ensure sidebar content doesn't scroll */
    .sidebar {
        height: 100%;
        max-height: 100vh;
        overflow: hidden !important;
        padding-bottom: 0;
    }

    /* Prevent sidebar children from scrolling */
    .sidebar * {
        overflow: hidden !important;
    }

    /* Main content area - scrollable */
    .content-wrapper {
        flex: 1;
        overflow-y: auto; /* Enable content scrolling */
        margin-left: 250px; /* Offset for sidebar */
        padding-bottom: 60px; /* Space for footer */
        min-height: calc(100vh - 60px); /* Stretch to footer */
    }

    /* Footer - fixed and non-scrollable */
    .main-footer {
        position: fixed;
        bottom: 0;
        width: calc(100% - 250px); /* Adjust for sidebar */
        margin-left: 250px; /* Align with content */
        background: #f4f6f9; /* AdminLTE footer background */
        padding: 10px;
        border-top: 1px solid #dee2e6;
        z-index: 999;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .content-wrapper {
            margin-left: 0; /* Full width on mobile */
        }
        .main-footer {
            margin-left: 0;
            width: 100%; /* Full width */
        }
        .main-sidebar {
            transform: translateX(-250px); /* Hide sidebar off-screen */
            transition: transform 0.3s ease;
        }
        .main-sidebar.active {
            transform: translateX(0); /* Show when toggled */
        }
        .sidebar {
            max-height: 100vh;
            overflow: hidden !important;
        }
    }
</style>

<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "") {
    header('location:../index.php');
}

if ($_SESSION['role'] == "Admin") {
    include_once "header.php";
} else {
    include_once "headeruser.php";
}

// When user clicks update password button
if (isset($_POST['btnupdate'])) {
    $oldpassword_txt = $_POST['txt_oldpassword'];
    $newpassword_txt = $_POST['txt_newpassword'];
    $rnewpassword_txt = $_POST['txt_rnewpassword'];

    // Get database records for useremail
    $email = $_SESSION['useremail'];
    $select = $pdo->prepare("select * from tbl_user where useremail=:email");
    $select->bindParam(':email', $email);
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);

    $useremail_db = $row['useremail'];
    $password_db = $row['userpassword'];

    // Compare user inputs to database values
    if ($oldpassword_txt == $password_db) {
        if ($newpassword_txt == $rnewpassword_txt) {
            // Update password
            $update = $pdo->prepare("update tbl_user set userpassword=:pass where useremail=:email");
            $update->bindParam(':pass', $rnewpassword_txt);
            $update->bindParam(':email', $email);

            if ($update->execute()) {
                $_SESSION['status'] = "Password Updated successfully";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "Password Not Updated successfully";
                $_SESSION['status_code'] = "error";
            }
        } else {
            $_SESSION['status'] = "New Password Does Not Match";
            $_SESSION['status_code'] = "error";
        }
    } else {
        $_SESSION['status'] = "Old Password Does Not Match";
        $_SESSION['status_code'] = "error";
    }
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
                        <h1 class="m-0">Change Password</h1>
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
                        <!-- Horizontal Form -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Change Password</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal" action="" method="post">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Old Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="inputPassword3" placeholder="Old Password" name="txt_oldpassword">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">New Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="inputPassword3" placeholder="New Password" name="txt_newpassword">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Repeat New Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="inputPassword3" placeholder="Repeat New Password" name="txt_rnewpassword">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" name="btnupdate">Update Password</button>
                                </div>
                                <!-- /.card-footer -->
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include_once "footer.php"; ?>
</div>

<?php
if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
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