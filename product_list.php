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
          <h1 class="m-0 text-dark">Manage Products</h1>
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
        <div class="col-lg-12">
            <div style="overflow-x: auto;">
          <table class="table table-striped table-bordered" id="product_table">
            <thead>
              <tr>
                <td>#</td>
                <td>image</td>
                <td>Product Name</td>
                <td>Category</td>
                <td>Purchase Price</td>
                <td>Sale Price</td>
                <td>Stock</td>
                <td>Description</td>
                <td>View</td>
                <td>Edit</td>
                <td>Delete</td>
              </tr>
            </thead>
            <tbody>
              <?php

$stmt = $pdo->prepare("SELECT * FROM `products` ORDER BY `pid` DESC");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $pid = $row['pid'];
  $p_name = $row['p_name'];
  $p_category = $row['p_category'];
  $purchase_price = $row['purchase_price'];
  $sale_price = $row['sale_price'];
  $pstock = $row['pstock'];
  $pdescription = $row['pdescription'];
  $pimage = $row['pimage'];


  ?>
              <tr>
                <td>
                  <?php echo $pid; ?>
                </td>
                <td><img src="/uploads/<?php echo $pimage; ?>" width="50px"></td>
                <td>
                  <?php echo $p_name; ?>
                </td>
                <td>
                  <?php echo $p_category; ?>
                </td>
                <td>
                  <?php echo $purchase_price; ?>
                </td>
                <td>
                  <?php echo $sale_price; ?>
                </td>
                <td>
                  <?php echo $pstock; ?>
                </td>
                <td>
                  <?php echo $pdescription; ?>
                </td>
                <td><a href="view_product.php?view_id=<?php echo $pid; ?>" data-toggle="tooltip" title="View Product" class="btn btn-success"><i class="fas fa-eye"></i></a></td>
                <td><a href="edit_product.php?edit_id=<?php echo $pid; ?>" data-toggle="tooltip" title="Edit Product" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                <td><button id="<?php echo $pid; ?>" data-toggle="tooltip" title="Delete Product" class="btn btn-danger btn_delete"><i class="fas fa-trash-alt"></i></button></td>
              </tr>
              <?php } ?>
            </tbody>
          </table></div>
        </div>
        <!-- /.col-md-6 -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<script>
$(document).ready(function() {
  $('#product_table').DataTable({
    "order": [
      [0, 'desc']
    ]
  });
  $('[data-toggle="tooltip"]').tooltip();
});

</script>
<script>
$(document).ready(function() {
  $('.btn_delete').click(function() {
    var tdh = $(this);
    var id = $(this).attr('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'product_delete.php',
          type: 'post',
          data: { "idd": id },
          success: function(response) {
            tdh.parents('tr').hide();
          }
        });
        Swal.fire(
          'Deleted!',
          'Your Product has been deleted.',
          'success'
        );
      }
    });

  });

});

</script>
<!-- /.content-wrapper -->
<?php 
include_once('includes/footer.php');

 ?>
