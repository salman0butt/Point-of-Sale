<?php 
session_start();
include_once 'includes/db.php';
include_once('includes/header.php');

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
}

 ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Sales & reports</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dasboard</a></li>
            <li class="breadcrumb-item active">Sales & reports</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <form action="" method="post" name="">
        <div class="box-header with-border">
          <h3 class="box-title">From :
            <?php echo $_POST['date_1']?> -- To :
            <?php echo $_POST['date_2']?>
          </h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-5">
            <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"> <i class="fa fa-calendar"></i></span>
                  </div>
                <input type="text" class="form-control pull-right" id="datepicker1" name="date_1" data-date-format="yyyy-mm-dd">
              </div>
            </div>
            <div class="col-md-5">
              <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"> <i class="fa fa-calendar"></i></span>
                  </div>
                     <input type="text" class="form-control pull-right" id="datepicker2" name="date_2" data-date-format="yyyy-mm-dd">
                </div>

            </div>
            <div class="col-md-2">
              <div align="left">
                <input type="submit" name="btndatefilter" value="Filter By Date" class="btn btn-success">
              </div>
            </div>
          </div>
          <br>
          <br>
          <?php
                  
                
    $select=$pdo->prepare("select sum(total) as total , sum(subtotal) as stotal,count(invoice_id) as invoice from invoice  where order_date between :fromdate AND :todate");
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
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-signal"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Total Invoice</span>
                  <span class="info-box-number">
                    <h2>
                      <?php echo number_format($invoice); ?>
                    </h2>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fas fa-chart-pie"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Sub Total</span>
                  <span class="info-box-number">
                    <h2>
                      <?php echo number_format($stotal,2); ?>
                    </h2>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fas fa-file-invoice-dollar"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Net Total</span>
                  <span class="info-box-number">
                    <h2>
                      <?php echo number_format($net_total,2); ?>
                    </h2>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
          <br>
          <table id="salesreporttable" class="table table-striped">
            <thead>
              <tr>
                <th>Invoice ID</th>
                <th>CustomerName</th>
                <th>Subtotal</th>
                <th>Grunter</th>
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
    $select=$pdo->prepare("select * from invoice  where order_date between :fromdate AND :todate");
      $select->bindParam(':fromdate',$_POST['date_1']);  
             $select->bindParam(':todate',$_POST['date_2']);  
            
    $select->execute();
            
while($row=$select->fetch(PDO::FETCH_OBJ)  ){
       $invoice_id = $row->invoice_id;
      $all_paid = 0;
    $previous_payment=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id"); $previous_payment->bindParam(':invoice_id', $invoice_id);
      $previous_payment->execute();
      while($row_data=$previous_payment->fetch(PDO::FETCH_OBJ)  ){
        $all_paid = $all_paid + $row_data->payment;     
       }
       $all_paid = $all_paid+$row->paid;
       $all_due = $row->total-$all_paid;
    
    echo'
    <tr>
    <td>'.$row->invoice_id.'</td>
    <td>'.$row->customer_name.'</td>
   <td>'.$row->subtotal.'</td>
    <td>'.$row->grunter.'</td>
     <td>'.$row->discount.'</td>
    <td><span class="badge badge-success">'.$row->total.'</span></td>
     <td>'.$all_paid.'</td>
      <td><span class="badge badge-danger">'.$all_due.'</span></td>

     <td>'.$row->order_date.'</td>
 
     ';
    
    if($row->payment_type=="Cash"){
        
      echo'<td><span class="badge badge-primary">'.$row->payment_type.'</span></td>';  
    }elseif($row->payment_type=="Card"){
        echo'<td><span class="badge badge-warning">'.$row->payment_type.'</span></td>';  
    }else{
         echo'<td><span class="badge badge-info">'.$row->payment_type.'</span></td>';
    }
        

}          
?>
            </tbody>
          </table>
        </div>
      </form>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>

//Date picker
$('#datepicker1').datepicker({
  autoclose: true
});



//Date picker
$('#datepicker2').datepicker({
  autoclose: true
});



  $(document).ready(function() {
    $('#salesreporttable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
          "order": [
    [0, "desc"]
  ]

    } );
} );


</script>
<?php 
include_once('includes/footer.php');

 ?>
