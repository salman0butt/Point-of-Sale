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
	$del_acc = $pdo->prepare("DELETE FROM `customers` WHERE `id`=$del_id");
	$status = $del_acc->execute();
	if ($status) {
		echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "Customer Deleted Successfully",
  "success"
)
    });
    </script>';
	} else {
		echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Customer Not deleted",
  "warning"
)
    });
    </script>';
	}
}

if (isset($_POST['save_btn'])) {

	$customer_name = $_POST['customer_name'];
	$father_name = $_POST['father_name'];
	$contact_no = $_POST['contact_no'];
	$customer_address = $_POST['customer_address'];
	$customer_cnic = $_POST['customer_cnic'];
	$grunter_name = $_POST['grunter_name'];
	$gurnter_father_name = $_POST['gurnter_father_name'];
	$grunter_contact_no = $_POST['grunter_contact_no'];
	$grunter_address = $_POST['grunter_address'];
	$grunter_cnic = $_POST['grunter_cnic'];

	try {
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->beginTransaction();

		$insert = $pdo->prepare("INSERT INTO `customers`(`customer_name`, `father_name`, `contact_no`, `customer_address`, `customer_cnic`, `grunter_name`, `gurnter_father_name`, `grunter_contact_no`, `grunter_address`, `grunter_cnic`)  VALUES(:customer_name,:father_name,:contact_no,:customer_address,:customer_cnic,:grunter_name,:gurnter_father_name,:grunter_contact_no,:grunter_address,:grunter_cnic)");
		$insert->bindParam(':customer_name', $customer_name);
		$insert->bindParam(':father_name', $father_name);
		$insert->bindParam(':contact_no', $contact_no);
		$insert->bindParam(':customer_address', $customer_address);
		$insert->bindParam(':customer_cnic', $customer_cnic);
		$insert->bindParam(':grunter_name', $grunter_name);
		$insert->bindParam(':gurnter_father_name', $gurnter_father_name);
		$insert->bindParam(':grunter_contact_no', $grunter_contact_no);
		$insert->bindParam(':grunter_address', $grunter_address);
		$insert->bindParam(':grunter_cnic', $grunter_cnic);

		$run = $insert->execute();
		$pdo->commit();
	} catch (Exception $e) {
		$pdo->rollback();
	}
	if ($run) {
		echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "Customer Successfully Added",
  "success"
)
    });
    </script>';

	} else {
		echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Customer unable to add failed!",
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
          <h1 class="m-0 text-dark">Manage Customers</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard.php">Dasboard</a></li>
            <li class="breadcrumb-item active">Customers</li>
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
              <h3 class="card-title">Add Customer</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="POST">
              <div class="card-body">
                <div class="form-group">
                  <label for="customer_name">Customer Name</label>
                  <input type="text" class="form-control" id="customer_name" placeholder="customer name" name="customer_name" required>
                </div>
                <div class="form-group">
                  <label for="father_name">Customer Father Name</label>
                  <input type="text" class="form-control" id="father_name" placeholder="father_name" name="father_name">
                </div>
                <div class="form-group">
                  <label for="contact_no">Customer Contact No</label>
                  <input type="text" class="form-control" id="contact_no" placeholder="contact_no" name="contact_no">
                </div>
                <div class="form-group">
                  <label for="customer_cnic">Customer Cnic</label>
                  <input type="text" class="form-control" id="customer_cnic" placeholder="customer cnic" name="customer_cnic">
                </div>
                <div class="form-group">
                  <label for="customer_address">Customer Address</label>
                  <input type="text" class="form-control" id="customer_address" placeholder="address" name="customer_address">
                </div>
                <div class="form-group">
                  <label for="grunter_name">Grunter Name</label>
                  <input type="text" class="form-control" id="grunter_name" placeholder="grunter name" name="grunter_name" >
                </div>
                <div class="form-group">
                  <label for="father_name">Grunter Father Name</label>
                  <input type="text" class="form-control" id="gurnter_father_name" placeholder="father name" name="gurnter_father_name">
                </div>
                <div class="form-group">
                  <label for="grunter_contact_no">Grunter Contact No</label>
                  <input type="text" class="form-control" id="grunter_contact_no" placeholder="contact no" name="grunter_contact_no">
                </div>
                <div class="form-group">
                  <label for="grunter_address">Grunter Address</label>
                  <input type="text" class="form-control" id="grunter_address" placeholder="address" name="grunter_address">
                  <div class="form-group">
                    <label for="grunter_cnic">Grunter Cnic</label>
                    <input type="text" class="form-control" id="grunter_cnic" placeholder="Grunter Cnic" name="grunter_cnic">
                  </div>
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
                <td>Customer Name</td>
                <td>Father Name</td>
                <td>Contact No</td>
                <td>Address</td>
                <td>Customer Cnic</td>
                <td>Grunter Name</td>
                <td>Grunter Father</td>
                <td>Grunter Contact</td>
                <td>Grunter Address</td>
                <td>Grunter Cnic</td>
                <td>Action</td>
              </tr>
            </thead>
            <tbody>
              <?php

$stmt = $pdo->prepare("SELECT * FROM `customers`");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$id_db = $row['id'];
	$customer_name = $row['customer_name'];
	$father_name = $row['father_name'];
	$contact_no = $row['contact_no'];
	$customer_address = $row['customer_address'];
	$customer_cnic = $row['customer_cnic'];
	$grunter_name = $row['grunter_name'];
	$gurnter_father_name = $row['gurnter_father_name'];
	$grunter_contact_no = $row['grunter_contact_no'];
	$grunter_address = $row['grunter_address'];
	$grunter_cnic = $row['grunter_cnic'];

	?>
              <tr>
                <td>
                  <?php echo $id_db; ?>
                </td>
                <td>
                  <?php echo $customer_name; ?>
                </td>
                <td>
                  <?php echo $father_name; ?>
                </td>
                <td>
                  <?php echo $contact_no; ?>
                </td>
                <td>
                  <?php echo ucfirst($customer_address); ?>
                </td>
                <td>
                  <?php echo $customer_cnic; ?>
                </td>
                  <td>
                  <?php echo $grunter_name; ?>
                </td>
                  <td>
                  <?php echo $gurnter_father_name; ?>
                </td>
                   <td>
                  <?php echo $grunter_contact_no; ?>
                </td>
                    <td>
                  <?php echo $grunter_address; ?>
                </td>
                    <td>
                  <?php echo $grunter_cnic; ?>
                </td>
                <td>
                  <a href="customers.php?del_id=<?php echo $id_db; ?>" class="btn btn-danger" role="button"><i class="fas fa-trash-alt"></i></a>
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
