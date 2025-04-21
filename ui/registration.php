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

    @media (max-width: 768px) {
        .content-wrapper {
            margin-left: 0;
        }
        .main-footer {
            margin-left: 0;
            width: 100%;
        }
        .main-sidebar {
            transform: translateX(-250px);
            transition: transform 0.3s ease;
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

<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "" || $_SESSION['role'] == "User") {
    header('location:../index.php');
}

if ($_SESSION['role'] == "Admin") {
    include_once "header.php";
} else {
    include_once "headeruser.php";
}

error_reporting(0);

$id = $_GET['id'];

if (isset($id)) {
    $delete = $pdo->prepare("delete from tbl_user where userid = :id");
    $delete->bindParam(':id', $id);
    if ($delete->execute()) {
        $_SESSION['status'] = "Account deleted successfully";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Account Is Not Deleted";
        $_SESSION['status_code'] = "warning";
    }
}

if (isset($_POST['btnsave'])) {
    $username = $_POST['txtname'];
    $useremail = $_POST['txtemail'];
    $userpassword = $_POST['txtpassword'];
    $userrole = $_POST['txtselect_option'];

    // Additional fields for User role
    $company = isset($_POST['txtcompany']) ? $_POST['txtcompany'] : null;
    $companywebsite = isset($_POST['txtcompanywebsite']) ? $_POST['txtcompanywebsite'] : null;
    $phone = isset($_POST['txtphone']) ? $_POST['txtphone'] : null;
    $address = isset($_POST['txtaddress']) ? $_POST['txtaddress'] : null;

    if (isset($_POST['txtemail'])) {
        $select = $pdo->prepare("SELECT useremail FROM tbl_user WHERE useremail = :email");
        $select->bindParam(':email', $useremail);
        $select->execute();

        if ($select->rowCount() > 0) {
            $_SESSION['status'] = "Email already exists. Create Account From New Email";
            $_SESSION['status_code'] = "warning";
        } else {
            // Insert query with additional fields for User role
            $insert = $pdo->prepare("INSERT INTO tbl_user (username, useremail, userpassword, role, company_name, company_website, phone, address) 
                                     VALUES (:name, :email, :password, :role, :company, :companywebsite, :phone, :address)");

            $insert->bindParam(':name', $username);
            $insert->bindParam(':email', $useremail);
            $insert->bindParam(':password', $userpassword);
            $insert->bindParam(':role', $userrole);
            $insert->bindParam(':company', $company);
            $insert->bindParam(':companywebsite', $companywebsite);
            $insert->bindParam(':phone', $phone);
            $insert->bindParam(':address', $address);

            if ($insert->execute()) {
                $_SESSION['status'] = "User registered successfully";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "Error inserting the user into the database";
                $_SESSION['status_code'] = "error";
            }
        }
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
                        <h1 class="m-0">Registration</h1>
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
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">Registration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Name" name="txtname" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="email" class="form-control" placeholder="Enter email" name="txtemail" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control" placeholder="Password" name="txtpassword" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select class="form-control" name="txtselect_option" id="roleSelect" required>
                                            <option value="" disabled selected>Select Role</option>
                                            <option value="Admin">Admin</option>
                                            <option value="User">User</option>
                                        </select>
                                    </div>
                                    <!-- Additional fields for User role -->
                                    <div id="userFields" style="display: none;">
                                        <div class="form-group">
                                            <label for="exampleInputCompany">Company Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Company Name" name="txtcompany">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputCompanywebsite">Company Website</label>
                                            <input type="text" class="form-control" placeholder="Enter Company Website" name="txtcompanywebsite">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPhone">Phone Number</label>
                                            <input type="text" class="form-control" placeholder="Enter Phone Number" name="txtphone">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputAddress">Address</label>
                                            <textarea class="form-control" placeholder="Enter Address" name="txtaddress"></textarea>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary" name="btnsave">Save</button>
                                    </div>
                                </form>

                                <script>
                                    // Show additional fields when "User" role is selected
                                    document.getElementById('roleSelect').addEventListener('change', function() {
                                        const userFields = document.getElementById('userFields');
                                        if (this.value === 'User') {
                                            userFields.style.display = 'block';
                                        } else {
                                            userFields.style.display = 'none';
                                        }
                                    });
                                </script>
                            </div>

                            <div class="col-md-8">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Name</td>
                                            <td>Email</td>
                                            <td>Password</td>
                                            <td>Role</td>
                                            <td>Delete</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select = $pdo->prepare("select * from tbl_user order by userid ASC");
                                        $select->execute();
                                        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                            echo '
                                            <tr>
                                                <td>' . $row->userid . '</td>
                                                <td>' . $row->username . '</td>
                                                <td>' . $row->useremail . '</td>
                                                <td>' . $row->userpassword . '</td>
                                                <td>' . $row->role . '</td>
                                                <td>
                                                    <a href="registration.php?id=' . $row->userid . '" class="btn btn-danger"><i class="fa fa-trash-alt"></i></a>
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