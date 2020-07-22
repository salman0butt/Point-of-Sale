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
          <h1 class="m-0 text-dark">Invoice Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dasboard</a></li>
            <li class="breadcrumb-item active">Invoice Details</li>
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
        <?php 
            if (isset($_GET['invoice_id'])) {

      $invoice_id = $_GET['invoice_id'];
        $stmt = $pdo->prepare("SELECT * FROM `purchase_invoice` WHERE `invoice_id` = :invid");
        $stmt->bindParam(':invid',$invoice_id);
        $stmt->execute();
       $row = $stmt->fetch();
       // var_dump($row);

        $supplier_name = $row['supplier_name'];
        $order_date = $row['order_date'];
        $subtotal = $row['subtotal'];
        $discount = $row['discount'];
        $total = $row['total'];
		$paid = $row['paid'];
         $all_paid = 0;
         $all_due = 0;
    
      $previous_payment=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id");
       $previous_payment->bindParam(':invoice_id', $invoice_id);
      $previous_payment->execute();
         // $previous_payment->debugDumpParams();

      while($row_data=$previous_payment->fetch(PDO::FETCH_OBJ)  ){
        $all_paid = $all_paid + $row_data->payment;     
       }
       $all_paid = $all_paid+$paid;
       $all_due = $total-$all_paid;
       // echo '<script>alert('.$all_due.');</script>';



        ?>
       <div class="row col-md-12">
         <div class="col-md-4" style="font-size: 20px;font-weight: bold;">Supplier name: <?php echo $supplier_name; ?></div>
         <div class="col-md-2" style="font-size: 20px;font-weight: bold;">Subtotal: <?php echo $subtotal; ?></div>
         <div class="col-md-2" style="font-size: 20px;font-weight: bold;">Discount: <?php echo $discount; ?></div>
         <div class="col-md-2" style="font-size: 20px;font-weight: bold;">Paid: <?php echo $all_paid; ?></div>
         <div class="col-md-2" style="font-size: 20px;font-weight: bold;">Due: <?php echo $all_due; ?></div>

         
       </div>
 <?php } ?>
        <div class="col-md-12">
           <table class="table table-striped table-bordered" id="customers">
            <thead>
              <tr>
                <td>#no</td>
                <td>Product Name</td>
                <td>Product IMEI</td>
                <td>QTY</td>
                <td>Price</td>
                <td>Order Date</td>
              </tr>
            </thead>
            <tbody>
                 <?php
  if (isset($_GET['invoice_id'])) {

      $invoice_id = $_GET['invoice_id'];
        $stmt = $pdo->prepare("SELECT * FROM `purchase_invoice_details` WHERE `invoice_id` = :id");
        $stmt->bindParam(':id',$invoice_id);
        $stmt->execute();
       while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $pid = $row['product_id'];
        $product_name = $row['product_name'];
        $product_imei = $row['product_imei'];
        $qty = $row['qty'];
        $price = $row['price'];
        $order_date = $row['purchase_date'];


        ?>              <tr>
                <td>
                  <?php echo $pid; ?>
                </td>
                  <td>
                  <?php echo $product_name; ?>
                </td>
                     <td>
                  <?php echo $product_imei; ?>
                </td>
                    <td>
                  <?php echo $qty; ?>
                </td>
                    <td>
                  <?php echo $price; ?>
                </td>
                     <td>
                  <?php echo $order_date; ?>
                </td>

              </tr>
            <?php }
            } ?>
            </tbody>
          </table>
        </div>

      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php 
include_once('includes/footer.php');

 ?>
