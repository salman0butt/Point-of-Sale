<?php 
session_start();
include_once 'includes/db.php';
include_once('includes/header.php');



if (isset($_POST['save_cat'])) {

  $cat_name = $_POST['name'];

  if (isset($_POST['name'])) {
    $cat_name_check = $pdo->prepare("SELECT * FROM `category` WHERE `name` = '$cat_name'");
    $cat_name_check->execute();
    if ($cat_name_check->rowCount() > 0) {
      echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Category already added!",
  "warning"
)
    });
    </script>';
    } else {

      $insert = $pdo->prepare("INSERT INTO `category`(`name`) VALUES(:name)");
      $insert->bindParam(':name', $cat_name);
      $run = $insert->execute();
      if ($run) {
        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "Category Saved Successfully",
  "success"
)
    });
    </script>';

      } else {
        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Category Not Saved",
  "warning"
)
    });
    </script>';
      }
    }
  }

}

if (isset($_POST['update_cat'])) {

       $cat_id = $_POST['cat_id'];
       $cat_name = $_POST['name'];

      $update = $pdo->prepare("UPDATE `category` SET `name`=:name WHERE `cat_id`=:id");
      $update->bindParam(':name', $cat_name);
      $update->bindParam(':id', $cat_id);
      $run = $update->execute();
      if ($run) {
        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "Category Updated Successfully",
  "success"
)
    });
    </script>';

      } else {
        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Category Not Updated",
  "warning"
)
    });
    </script>';
      }

}


if ($_SESSION['role'] == 'admin') {
  include_once 'includes/header.php';
} else {
  include_once 'includes/header_user.php';
}
if (isset($_GET['cat_del'])) {
  $del_id = $_GET['cat_del'];
  $del_cat = $pdo->prepare("DELETE FROM `category` WHERE `cat_id`=$del_id");
  $status = $del_cat->execute();
  if ($status) {
        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "Category Deleted Successfully",
  "success"
)
    });
    </script>';
  }else {
          echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Category Not deleted",
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
          <h1 class="m-0 text-dark">Manage Category</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard.php">Dasboard</a></li>
            <li class="breadcrumb-item active">Category</li>
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
        <div class="col-md-4">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Category Form</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <?php if (isset($_GET['cat_edit'])) {
              $cat_id = $_GET['cat_edit'];

            $cat_name = $pdo->prepare("SELECT * FROM `category` WHERE `cat_id` = $cat_id");
            $cat_name->execute();
           $row = $cat_name->fetch(PDO::FETCH_ASSOC);
           $cate_id = $row['cat_id'];
           $cate_name = $row['name'];
             ?>
              
            <form action="" method="POST">
              <div class="card-body">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" id="name" placeholder="name" name="name" value="<?php echo $cate_name; ?>" required>
                  <input type="hidden" class="form-control" name="cat_id" value="<?php echo $cate_id; ?>" required>
                </div>
                <button type="submit" class="btn btn-info" name="update_cat">Update</button>
              </div>
            </form>
            <?php }else { ?>
                   <form action="" method="POST">
              <div class="card-body">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" id="name" placeholder="name" name="name" required>
                </div>
                <button type="submit" class="btn btn-info" name="save_cat">Save</button>
              </div>
            </form>
          <?php } ?>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
        <div class="col-md-8">
          <table class="table table-striped table-bordered" id="category_table">
            <thead>
              <tr>
                <td>#no</td>
                <td>Name</td>
                <td>Edit</td>
                <td>Delete</td>
              </tr>
            </thead>
            <tbody>
              <?php

$stmt = $pdo->prepare("SELECT * FROM `category` ORDER BY `cat_id` DESC");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $cat_id = $row['cat_id'];
  $name = $row['name'];


  ?>
              <tr>
                <td><?php echo $cat_id; ?></td>
                <td><?php echo $name; ?></td>
                <td><a href="category.php?cat_edit=<?php echo $cat_id; ?>" class="btn btn-info">Edit</a></td>
                <td><a href="category.php?cat_del=<?php echo $cat_id; ?>" class="btn btn-danger">Delete</a></td>
                

              </tr>

       <?php } ?>       
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
<script>
  $(document).ready( function () {
    $('#category_table').DataTable();
});
</script>