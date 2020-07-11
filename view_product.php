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
          <h1 class="m-0 text-dark">Product Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dasboard</a></li>
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
        <div class="col-lg-6">
                  <?php
  if (isset($_GET['view_id'])) {
      $view_id = $_GET['view_id'];
        $stmt = $pdo->prepare("SELECT * FROM `products` WHERE `pid` = $view_id");
        $stmt->execute();
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $pid = $row['pid'];
        $p_name = $row['p_name'];
        $p_category = $row['p_category'];
        $purchase_price = $row['purchase_price'];
        $sale_price = $row['sale_price'];
        $pstock = $row['pstock'];
        $pdescription = $row['pdescription'];
        $pimage = $row['pimage'];
        $profit = $sale_price-$purchase_price;
}

        ?>
          <ul class="list-group">
            <li class="bg bg-info p-2"><h5 class="mb-1 text-center">Product Details</h5></li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>ID</strong>
              <span class="badge badge-info badge-pill"><?php if(isset($pid)) echo $pid; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
             <strong>Product Name</strong>
              <span class="badge badge-primary badge-pill"><?php if(isset($p_name)) echo $p_name; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Category</strong>
              <span class="badge badge-primary badge-pill"><?php if(isset($p_category)) echo $p_category; ?></span>
            </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Purchase Price</strong>
              <span class="badge badge-warning badge-pill"><?php if(isset($purchase_price)) echo $purchase_price; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Sale Price</strong>
              <span class="badge badge-primary badge-pill"><?php if(isset($sale_price)) echo $sale_price; ?></span>
            </li>
               <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Product Profit</strong>
              <span class="badge badge-success badge-pill"><?php if(isset($profit)) echo $profit; ?></span>
            </li>
               <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Stock</strong>
              <span class="badge badge-danger badge-pill"><?php if(isset($pstock)) echo $pstock; ?></span>
            </li>
                 <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Description:</strong> &emsp; <?php if(isset($pdescription)) echo $pdescription; ?>

            </li>
          </ul>
        </div>
        <div class="col-lg-6">
                 <ul class="list-group">
            <li class="bg bg-success p-2"><h5 class="mb-1 text-center">Product Image</h5></li>
              <img src="<?php if(isset($pimage)) echo "uploads/".$pimage; ?>" alt="product image" style="max-height:400px;">
          </ul>
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
include_once('includes/footer.php');

 ?>
