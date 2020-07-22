<?php

session_start();
include_once 'includes/db.php';
if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
	header('Location: index.php');
}
if ($_SESSION['role'] == 'admin') {
	include_once 'includes/header.php';
} else {
	include_once 'includes/header_user.php';
}
if (isset($_GET['del_id'])) {
	$del_id = $_GET['del_id'];
	try {
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->beginTransaction();

		$del_acc = $pdo->prepare("DELETE FROM `suppliers` WHERE `id`=$del_id");
		$status = $del_acc->execute();
		$pdo->commit();
	} catch (Exception $e) {
		$pdo->rollback();
	}
	if ($status) {
		echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "suppliers Deleted Successfully",
  "success"
)
    });
    </script>';
	} else {
		echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "suppliers Not deleted",
  "warning"
)
    });
    </script>';
	}
}

if (isset($_POST['save_btn'])) {

	$supplier_name = $_POST['supplier_name'];
	$father_name = $_POST['father_name'];
	$contact_no = $_POST['contact_no'];
	$supplier_address = $_POST['supplier_address'];
	try {
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->beginTransaction();

		$insert = $pdo->prepare("INSERT INTO `suppliers`(`supplier_name`, `father_name`, `contact_no`, `supplier_address`)  VALUES(:supplier_name,:father_name,:contact_no,:supplier_address)");
		$insert->bindParam(':supplier_name', $supplier_name);
		$insert->bindParam(':father_name', $father_name);
		$insert->bindParam(':contact_no', $contact_no);
		$insert->bindParam(':supplier_address', $supplier_address);

		$run = $insert->execute();
		// $insert->debugDumpParams();
		$pdo->commit();
	} catch (Exception $e) {
		$pdo->rollback();
	}
	if ($run) {
		echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "supplier Successfully Added",
  "success"
)
    });
    </script>';

	} else {
		echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "supplier unable to add failed!",
  "warning"
)
    });
    </script>';
	}
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Manage Suppliers</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard.php">Dasboard</a></li>
            <li class="breadcrumb-item active">Suppliers</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row col-md-12">
        <div class="col-md-3">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Supplier</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="POST">
              <div class="card-body">
                <div class="form-group">
                  <label for="customer_name">Supplier Name</label>
                  <input type="text" class="form-control" id="customer_name" placeholder="customer name" name="supplier_name" required>
                </div>
                <div class="form-group">
                  <label for="father_name">Supplier Father Name</label>
                  <input type="text" class="form-control" id="father_name" placeholder="father_name" name="father_name">
                </div>
                <div class="form-group">
                  <label for="contact_no">Supplier Contact No</label>
                  <input type="text" class="form-control" id="contact_no" placeholder="contact_no" name="contact_no">
                </div>
                   <div class="form-group">
                  <label for="contact_no">Supplier Address</label>
                  <input type="text" class="form-control" id="contact_no" placeholder="address" name="supplier_address">
                </div>
                  </div>

              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-info" name="save_btn">Save</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
        <div class="col-md-9" style="overflow-y: scroll;">
          <table class="table table-striped table-bordered" id="customers">
            <thead>
              <tr>
                <td>#no</td>
                <td>Supplier Name</td>
                <td>Father Name</td>
                <td>Contact No</td>
                <td>Address</td>
                <td>Action</td>
              </tr>
            </thead>
            <tbody>
              <?php

$stmt = $pdo->prepare("SELECT * FROM `suppliers`");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$id_db = $row['id'];
	$supplier_name = $row['supplier_name'];
	$father_name = $row['father_name'];
	$contact_no = $row['contact_no'];
	$supplier_address = $row['supplier_address'];

	?>
              <tr>
                <td>
                  <?php echo $id_db; ?>
                </td>
                <td>
                  <?php echo $supplier_name; ?>
                </td>
                <td>
                  <?php echo $father_name; ?>
                </td>
                <td>
                  <?php echo $contact_no; ?>
                </td>
                <td>
                  <?php echo ucfirst($supplier_address); ?>
                </td>
                <td>
                  <a href="suppliers.php?del_id=<?php echo $id_db; ?>" class="btn btn-danger" role="button"><i class="fas fa-trash-alt"></i></a>
                </td>
              </tr>
              <?php }?>
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
<script>
$(document).ready(function() {
  $('#customers').DataTable({
    "order": [
      [0, "desc"]
    ]
  });
});

</script>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php
include_once 'includes/footer.php';

?>
