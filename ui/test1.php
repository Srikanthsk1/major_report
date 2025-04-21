
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



include_once "header.php";


?>
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
          <!-- <h1 class="m-0">TABLE REPORT </h1> -->
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Starter Page</li> -->
          </ol>
        </div><!-- /.col -->
      </div>
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
          
              <h3 class="box-title">TABLE REPORT---From Date : <?php echo $_POST['date_1']?> -- To Date : <?php echo $_POST['date_2']?></h3>
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
							</form>   
							<hr style="height:2px; border-width:0; color:black; background-color:black;"> 
                





              <?php
                  
                
                  $select=$pdo->prepare("select sum(total) as total , sum(subtotal) as stotal,count(invoice_id) as invoice from tbl_invoice  where order_date between :fromdate AND :todate");
                    $select->bindParam(':fromdate',$_POST['date_1']);  
                           $select->bindParam(':todate',$_POST['date_2']);  
                          
                  $select->execute();
                          
              $row=$select->fetch(PDO::FETCH_OBJ);
                  
              $net_total=$row->total;
                                  
              $stotal=$row->stotal;
                                  
              $invoice=$row->invoice;                    
                                
                                
                                
                                
                                ?>
                                                           
                                                                         
                                    
                                                           

   <!-- Info boxes -->
   <div class="row">

   <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-book"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">TOTAL INVOICE</span>
                <span class="info-box-number"><?php echo number_format($invoice,2); ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>



          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">SUBTOTAL</span>
                <span class="info-box-number">
                <?php echo number_format($stotal,2); ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-file"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">GRAND TOTAL</span>
                <span class="info-box-number"><?php echo number_format($net_total,2); ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

      
         
          <!-- /.col -->
        </div>
        <!-- /.row -->



                                                
                                
                                   
                     <br>
                               
















							<div class="row">
								<div class="col-md-12">
									<table id="table_report" class="table table-striped">
										<thead>
											<tr>
                      <th>Invoice ID</th>
      
            <th>Subtotal</th>  
               <th>SGST(%)</th> 
               <th>CGST(%)</th> 
                  <th>Discount</th>  
           
           <th>Total</th>   
            <th>Paid</th>
                  <th>Due</th>
                  <th>OrderDate</th> 
                  <th>Payment Type</th>
                
        </tr>    
            
        </thead> 
           
              
                 
        <tbody>
        
            <?php
    $select=$pdo->prepare("select * from tbl_invoice  where order_date between :fromdate AND :todate");
      $select->bindParam(':fromdate',$_POST['date_1']);  
             $select->bindParam(':todate',$_POST['date_2']);  
            
    $select->execute();
            
while($row=$select->fetch(PDO::FETCH_OBJ)  ){
    
    echo'
    <tr>
    <td>'.$row->invoice_id.'</td>
   
   <td>'.$row->subtotal.'</td>
    <td>'.$row->sgst.'</td>
    <td>'.$row->cgst.'</td>
     <td>'.$row->discount.'</td>
    <td><span class="badge badge-danger">'.$row->total.'</span></td>
     <td>'.$row->paid.'</td>
      <td>'.$row->due.'</td>
   
     <td>'.$row->order_date.'</td>
    
    
  
   
     ';
    
     if($row->payment_type=="Cash"){
      echo'<td><span class="badge badge-warning">'.$row->payment_type.'</td></span></td>';
    }else if($row->payment_type=="Card"){
    
      echo'<td><span class="badge badge-success">'.$row->payment_type.'</td></span></td>';
    }else{
      echo'<td><span class="badge badge-danger">'.$row->payment_type.'</td></span></td>';
    
    }
        
        
        
        
   
    
}          
?>        
       
                
 </tbody>                
									</table>   
								</div>
							</div>
							<br>
							<br> 



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