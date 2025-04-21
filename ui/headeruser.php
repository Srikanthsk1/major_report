<style>
    html {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overscroll-behavior: none;  
        /* touch-action: none;  */
    }
</style>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS | BARCODE SYSTEMS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
 <!-- Select2 -->
<link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">


  

  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">


 <!-- SweetAlert2 -->
 <link rel="stylesheet" href="../plugins/sweetalert2/sweetalert2.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">



  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="user.php" class="brand-link">
      <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">BILLZ</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="user.php" class="d-block"><?php echo $_SESSION['username']; ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
      
          <li class="nav-item">
            <a href="pos.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                POS
                
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="orderlist.php" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Order List
                
              </p>
            </a>

          </li>

          <li class="nav-item">
            <a href="credit.php" class="nav-link">
            <i class="nav-icon fas fa-sync-alt"></i>
              <p>
               Credit
              
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="category.php" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Category
                
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="addproduct.php" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
              <p>
               Product
              
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="productlist.php" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
              <p>
               Product List
              
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
              Sales Report
              <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href="graphreport_fixed.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                Graph Report
                </p>
              </a>
              </li>
              <li class="nav-item">
              <a href="tablereport.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                Table Report
                </p>
              </a>
              </li>
              <li class="nav-item">
                <a href="Analysis Dashboard.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Analysis Dashboard</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item">
            <a href="predict.php" class="nav-link">
            <i class="nav-icon fas fa-chart-bar"></i>
              <p>
             Predict
              
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="taxdis.php" class="nav-link">
            <i class="nav-icon fas fa-calculator"></i>
              <p>
               Tax & Discount
              
              </p>
            </a>
          </li>

       
          <li class="nav-item">
            <a href="changepassword.php" class="nav-link">
              <i class="nav-icon fas fa-user-lock"></i>
              <p>
                Change Password
                
              </p>
            </a>
          </li>

        

          <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
               
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
