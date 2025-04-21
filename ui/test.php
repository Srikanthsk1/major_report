
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
//error_reporting(0);
include_once 'connectdb.php';
session_start();
include_once "header.php";


?>
  <!-- daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
 <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Admin Dashboard</h1>
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
              <h5 class="m-0">Featured</h5>
            </div>
            <div class="card-body">
            


            <form  action="" method="post" name="">
								<div class="row">
								 
									<div class="col-md-5">
										<!-- Date -->
										<div class="form-group">
											<div class="input-group date" id="date1" data-target-input="nearest">
												<input type="text" class="form-control date_1" data-target="#date1"   name="date_1"/>
												<div class="input-group-append" data-target="#date1" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="fa fa-calendar"></i></div>
												</div>
											</div>
										</div>
									</div>   
									<div class="col-md-5">
										<!-- Date -->
										<div class="form-group">
											<div class="input-group date" id="date2" data-target-input="nearest">
												<input type="text" class="form-control date_2" data-target="#date2"   name="date_2"/>
												<div class="input-group-append" data-target="#date2" data-toggle="datetimepicker">
													<div class="input-group-text"><i class="fa fa-calendar"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div align="center">
											<input type="submit" name="btndatefilter" value="Filter Records" class="btn btn-success">
                                          
										</div>
									</div>  
                                    
                                    
								</div> 
                
                
					 


              <br>
                     <br>   
                 
                   <?php
    $select=$pdo->prepare("select order_date, sum(total) as price from tbl_invoice  where order_date between :fromdate AND :todate group by order_date");
      $select->bindParam(':fromdate',$_POST['date_1']);  
             $select->bindParam(':todate',$_POST['date_2']);  
            
    $select->execute();
                  
    $total=[];
    $date=[];              
            
while($row=$select->fetch(PDO::FETCH_ASSOC)  ){
    
extract($row);
    
    $total[]=$price;
    $date[]=$order_date;
    
    
}
               // echo json_encode($total);  
                  
                  ?>
                  
                  <div class="chart">
                      <canvas id="myChart" style="height:250px"></canvas>
                      
                      
                  </div>
                  
                  
                  
                    <?php
      $select=$pdo->prepare("select product_name, sum(qty) as q from tbl_invoice_details  where order_date between :fromdate AND :todate group by product_id");
      $select->bindParam(':fromdate',$_POST['date_1']);  
             $select->bindParam(':todate',$_POST['date_2']);  
            
    $select->execute();
                  
    $pname=[];
    $qty=[];              
            
while($row=$select->fetch(PDO::FETCH_ASSOC)  ){
    
extract($row);
    
    $pname[]=$product_name;
    $qty[]=$q;
    
    
}
               // echo json_encode($total);  
                  
                  ?>
    
                    <div class="chart">
                      <canvas id="bestsellingproduct" style="height:250px"></canvas>
                      
                      
                  </div>
                  
                  
                  
                  
              </div>
              </form>
               </div>
        
        
        

    </section>
    <!--/.content -->
  </div>
  <!-- /.content-wrapper -->
  
  

  
 
 
  
  

  















            
             
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

<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>

<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>


<!-- Page specific script -->
<script>

    
    
$(document).ready( function () {
    $('#table_report').DataTable();
} );


    

 //Datemask dd/mm/yyyy
  //  $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
  //  $('#datemask2').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Money Euro
   // $('[data-mask]').inputmask()

    //Date picker
    $('#date1').datetimepicker({
       format: 'YYYY-MM-DD'
    });

     //Date picker
    $('#date2').datetimepicker({
       format: 'YYYY-MM-DD'
    });
    
        $('#reporttable').DataTable({
        
    "order":[[0,"desc"]]    
        
        
        
    });
    
    
    
 </script>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($date);?>,
        datasets: [{
            label: 'Total Earning',
        backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
           
            data:<?php echo json_encode($total);?>
        }]
    },

    // Configuration options go here
    options: {}
});




</script>
 
 
 <script>
var ctx = document.getElementById('bestsellingproduct').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($pname);?>,
        datasets: [{
            label: 'Total Qunatity',
             backgroundColor: 'rgb(102, 255, 102)',
            borderColor: 'rgb(0, 102, 0)',
            data:<?php echo json_encode($qty);?>
        }]
    },

    // Configuration options go here
    options: {}
});




</script>