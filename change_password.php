<?php 

session_start();
include_once('includes/db.php');
if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
}
if ($_SESSION['role'] == 'admin') {
  include_once('includes/header.php');
}else {
  include_once('includes/header_user.php');
}


if (isset($_POST['update_pass'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_new_pass = $_POST['confirm_new_pass'];

    $email = $_SESSION['email'];
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = '$email'");
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $db_username = $row['username'];
    $db_pass = $row['password'];

    if ($old_pass == $db_pass) {
      if ($new_pass == $confirm_new_pass) {
        $update = $pdo->prepare("UPDATE `users` SET `password`=:pass WHERE `email`=:email");
        $update->bindParam(':pass',$confirm_new_pass);
        $update->bindParam(':email', $email);
        $run = $update->execute();

        if ($run) {
               echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job! '.$_SESSION["username"].'",
  "Password Updated Successfully!",
  "success"
)
    });
    </script>';
        }else {
                      echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Wrong!",
  "Password is not Updated",
  "error"
)
    });
    </script>';
        }

      }else {
      echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Wrong!",
  "new and Confirm password not matched",
  "error"
)
    });
    </script>';
      }

    }else {
  echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Wrong!",
  "Password not matched",
  "error"
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
          <h1 class="m-0 text-dark">Change Password</h1>
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
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Change Password</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="POST">
              <div class="card-body">
                <div class="form-group">
                  <label for="old_pass">Old Password</label>
                  <input type="password" class="form-control" id="old_pass" placeholder="Old Password" name="old_pass required">
                </div>
                <div class="form-group">
                  <label for="new_pass">New Password</label>
                  <input type="password" class="form-control" id="new_pass" placeholder="New Password" name="new_pass" required>
                </div>
                <div class="form-group">
                  <label for="new_pass">Confirm Password</label>
                  <input type="password" class="form-control" id="new_pass" placeholder="Confirm Password" name="confirm_new_pass" required>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-info" name="update_pass">Update</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
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
