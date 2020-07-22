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
          <h1 class="m-0 text-dark">Graph Reports</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dasboard</a></li>
            <li class="breadcrumb-item active">Graph Reports</li>
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
    $select=$pdo->prepare("select order_date, sum(total) as price from invoice where order_date between :fromdate AND :todate group by order_date");
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
    $select=$pdo->prepare("select product_name, sum(qty) as q from invoice_details where order_date between :fromdate AND :todate group by product_name");
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
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
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

      data: <?php echo json_encode($total);?>
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
      data: <?php echo json_encode($qty);?>
    }]
  },

  // Configuration options go here
  options: {}
});

</script>
<script>
//Date picker
$('#datepicker1').datepicker({
  autoclose: true
});



//Date picker
$('#datepicker2').datepicker({
  autoclose: true
});

</script>
<?php 
include_once('includes/footer.php');

 ?>
