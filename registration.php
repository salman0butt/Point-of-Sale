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
  $del_acc = $pdo->prepare("DELETE FROM `users` WHERE `id`=$del_id");
  $status = $del_acc->execute();
  if ($status) {
        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "User Deleted Successfully",
  "success"
)
    });
    </script>';
  }else {
          echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "User Not deleted",
  "warning"
)
    });
    </script>';
  }
}

if (isset($_POST['save_btn'])) {

  $username = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  if (isset($_POST['email'])) {
    $email_check = $pdo->prepare("SELECT * FROM `users` WHERE `email` = '$email'");
    $email_check->execute();
    if ($email_check->rowCount() > 0) {
      echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "User already added!",
  "warning"
)
    });
    </script>';
    } else {
      $insert = $pdo->prepare("INSERT INTO `users`(`username`,`email`,`password`,`role`) VALUES(:name,:email,:password,:role)");
      $insert->bindParam(':name', $username);
      $insert->bindParam(':email', $email);
      $insert->bindParam(':password', $password);
      $insert->bindParam(':role', $role);
      $run = $insert->execute();
      if ($run) {
        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "registration Successfully",
  "success"
)
    });
    </script>';

      } else {
        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Registration failed!",
  "warning"
)
    });
    </script>';
      }
    }
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
          <h1 class="m-0 text-dark">Manage Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard.php">Dasboard</a></li>
            <li class="breadcrumb-item active">Registration</li>
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
              <h3 class="card-title">Add User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="POST">
              <div class="card-body">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" id="name" placeholder="name" name="name" required>
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" placeholder="email" name="email" required>
                </div>
                <div class="form-group">
                  <label for="pass">Password</label>
                  <input type="password" class="form-control" id="pass" placeholder="Password" name="password" required>
                </div>
                <div class="form-group">
                  <label for="role">Role</label>
                  <select name="role" id="role" class="form-control">
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                  </select>
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
        <div class="col-md-8">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <td>#no</td>
                <td>Name</td>
                <td>Email</td>
                <td>Role</td>
                <td>Action</td>
              </tr>
            </thead>
            <tbody>
              <?php

$stmt = $pdo->prepare("SELECT * FROM `users`");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $id_db = $row['id'];
  $username_db = $row['username'];
  $email_db = $row['email'];
  $role_db = $row['role'];

  ?>
              <tr>
                <td>
                  <?php echo $id_db; ?>
                </td>
                <td>
                  <?php echo $username_db; ?>
                </td>
                <td>
                  <?php echo $email_db; ?>
                </td>
                <td>
                  <?php echo ucfirst($role_db); ?>
                </td>
                <td>
                    <a href="registration.php?del_id=<?php echo $id_db; ?>" class="btn btn-danger" role="button"><i class="fas fa-trash-alt"></i></a>
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
<?php
include_once 'includes/footer.php';

?>
