<style>
    html {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overscroll-behavior: none;  
        /* touch-action: none;  */
    }
</style>

<?php

include_once 'connectdb.php';
session_start();


if($_SESSION['useremail']==""  OR $_SESSION['role']=="Admin"){

header('location:../index.php');

}

include_once "headeruser.php";


?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Vendor Dashboard</h1>
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
              <h5 class="m-0">Welcome!!  <?php echo $_SESSION['username']; ?></h5>
            </div>
            <div class="card-body">
              <h6 class="card-title">Special title treatment</h6>

              <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
              <a href="pos.php" class="btn btn-primary">Start Now</a>
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