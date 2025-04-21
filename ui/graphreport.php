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
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<!-- daterange picker -->
<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">

<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Total Earnings Graph</h1>
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
          <form method="post" action="" name="">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h5 class="m-0">FROM: <?php echo $_POST['date_1']; ?> -- TO: <?php echo $_POST['date_2']; ?></h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <div class="input-group date" id="date_1" data-target-input="nearest">
                        <input type="text" class="form-control date_1" data-target="#date_1" name="date_1" />
                        <div class="input-group-append" data-target="#date_1" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-5">
                    <div class="form-group">
                      <div class="input-group date" id="date_2" data-target-input="nearest">
                        <input type="text" class="form-control date_2" data-target="#date_2" name="date_2" />
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

                <?php
                if (isset($_POST['btnfilter'])) {
                    $select = $pdo->prepare("SELECT order_date, SUM(total) AS grandtotal FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate GROUP BY order_date");
                    $select->bindParam(':fromdate', $_POST['date_1']);
                    $select->bindParam(':todate', $_POST['date_2']);
                    $select->execute();

                    $total = [];
                    $date = [];

                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $total[] = $grandtotal;
                        $date[] = $order_date;
                    }
                }
                ?>

                <div class="row">
                  <div class="col-md-10">
                    <div class="card" style="height: 500px;">
                      <div class="card-header">
                        <h3 class="card-title">Total Earnings</h3>
                      </div>
                      <div class="card-body" style="position: relative; height: 400px;">
                        <canvas id="myChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>

                <style>
                  #myChart {
                    width: 100% !important;
                    height: 100% !important;
                  }
                </style>

                <script>
                  const ctx = document.getElementById('myChart').getContext('2d');

                  new Chart(ctx, {
                    type: 'bar',
                    data: {
                      labels: <?php echo json_encode($date ?? []); ?>,
                      datasets: [{
                        label: 'Total Earning',
                        backgroundColor: 'rgb(255,99,132)',
                        borderColor: 'rgb(255,99,132)',
                        data: <?php echo json_encode($total ?? []); ?>,
                        borderWidth: 1
                      }]
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                </script>

              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once "footer.php"; ?>

<!-- Moment JS -->
<script src="../plugins/moment/moment.min.js"></script>

<!-- Date Range Picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  //Date picker
  $('#date_1').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  $('#date_2').datetimepicker({
    format: 'YYYY-MM-DD'
  });
</script>
