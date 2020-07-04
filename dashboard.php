<?php

include_once 'includes/db.php';
session_start();

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
	header('Location: index.php');
}
$purchase_query = $pdo->prepare("select sum(total) as total_purchase from purchase_invoice");
$purchase_query->execute();
$purchase_total = $purchase_query->fetch(PDO::FETCH_OBJ);

$total_purchase = $purchase_total->total_purchase;



$select = $pdo->prepare("select sum(total) as t, count(invoice_id) as inv from invoice");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

$total_order = $row->inv;

$net_total = $row->t;

$select = $pdo->prepare("select order_date, total from invoice group by order_date LIMIT 30");

$select->execute();

$ttl = [];
$date = [];

while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

	extract($row);

	$ttl[] = $total;
	$date[] = $order_date;

}

include_once 'includes/header.php';

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Admin Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Home</li>
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
       
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>
                <?php echo  number_format($net_total, 2); ?><sup style="font-size: 20px"></sup></h3>
              <p>Total Sale</p>
            </div>
            <div class="icon">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <?php
// $select = $pdo->prepare("select count(p_name) as p from products");
// $select->execute();
// $row = $select->fetch(PDO::FETCH_OBJ);

// $total_product = $row->p;
        $select = $pdo->prepare("select * from invoice");

$select->execute();
$total_paid = 0;
$total_due = 0;
while ($row = $select->fetch(PDO::FETCH_OBJ)) {
   $invoice_id = $row->invoice_id;
      $all_paid = 0;
    $previous_payment=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id"); $previous_payment->bindParam(':invoice_id', $invoice_id);
      $previous_payment->execute();
      while($row_data=$previous_payment->fetch(PDO::FETCH_OBJ)  ){
        $all_paid = $all_paid + $row_data->payment;     
       }
       $all_paid = $all_paid+$row->paid;
       $all_due = $row->total-$all_paid;
       $total_paid = $total_paid+$all_paid;
       $total_due = $total_due+$all_due;


     }

?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>
                <?php echo number_format($total_paid,2); ?>
              </h3>
              <p>Total Paid</p>
            </div>
            <div class="icon">
              <i class="fas fa-money-bill-wave"></i>
             
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <?php
// $select = $pdo->prepare("select count(name) as cate from category");
// $select->execute();
// $row = $select->fetch(PDO::FETCH_OBJ);

// $total_category = $row->cate;



?>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>
                <?php echo number_format($total_due,2); ?>
              </h3>
              <p>Total Due</p>
            </div>
            <div class="icon">
            <i class="fas fa-chart-pie"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>
                <?php echo $total_purchase; ?>
              </h3>
              <p>Total Orders</p>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Earning By Date</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
          <div class="chart">
            <canvas id="earningbydate" style="height:250px"></canvas>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Best Selling Product</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <table id="bestsellingproductlist" class="table table-striped">
                <thead>
                  <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
$select = $pdo->prepare("select product_id,product_name,price,sum(qty) as q , sum(qty*price) as total from invoice_details group by product_id order by sum(qty) DESC LIMIT 15");

$select->execute();

while ($row = $select->fetch(PDO::FETCH_OBJ)) {

	echo '
    <tr>
    <td>' . $row->product_id . '</td>
    <td>' . $row->product_name . '</td>
    <td><span class="badge badge-primary">' . $row->q . '</span></td>
    <td><span class="badge badge-info">' . "R.S " . $row->price . '</span></td>
     <td><span class="badge badge-danger">' . "R.S " . $row->total . '</span></td>



        </tr>
     ';

}
?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Orders</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <table id="orderlisttable" class="table table-striped">
                <thead>
                  <tr>
                    <th>Invoice ID</th>
                    <th>CustomerName</th>
                    <th>OrderDate</th>
                    <th>Total</th>
                    <th>due</th>
                    <th>Payment Type</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
$select = $pdo->prepare("select * from invoice order by invoice_id desc LIMIT 15");

$select->execute();

while ($row = $select->fetch(PDO::FETCH_OBJ)) {

	echo '
    <tr>
    <td><a href="editorder.php?id=' . $row->invoice_id . '">' . $row->invoice_id . '</a></td>
    <td>' . $row->customer_name . '</td>
    <td>' . $row->order_date . '</td>
    <td><span class="badge badge-success">' . "R.S " . $row->total . '</span></td>';
    echo '<td><span class="badge badge-danger">' . "R.S " . $row->due . '</span></td>';
	if ($row->payment_type == "Cash") {
		echo '<td><span class="badge badge-warning">' . $row->payment_type . '</span></td>';

	} elseif ($row->payment_type == "Card") {
		echo '<td><span class="badge badge-success">' . $row->payment_type . '</span></td>';
	} else {
		echo '<td><span class="badge badge-primary">' . $row->payment_type . '</span></td>';
	}

}
?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
var ctx = document.getElementById('earningbydate').getContext('2d');
var chart = new Chart(ctx, {
  // The type of chart we want to create
  type: 'bar',

  // The data for our dataset
  data: {
    labels: <?php echo json_encode($date); ?>,
    datasets: [{
      label: 'Total Earning',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',

      data: <?php echo json_encode($ttl); ?>
    }]
  },

  // Configuration options go here
  options: {}
});

</script>
<!--
    <script>
  $(document).ready( function () {
    $('#bestsellingproductlist').DataTable({
         "order":[[0,"asc"]]

     });
} );


</script>


  <script>
  $(document).ready( function () {
    $('#orderlisttable').DataTable({
        "order":[[0,"desc"]]
     });
} );


</script>
-->
<?php
include_once 'includes/footer.php';

?>
