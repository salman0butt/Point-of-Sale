  <!-- jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>



<?php
include_once 'includes/db.php';
session_start();
error_reporting(0);
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $login_query = "SELECT * FROM `users` WHERE `email`='$email' AND `password`='$password'";
  $stmt = $pdo->prepare($login_query);
  $stmt->execute();

  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($data['email'] == $email AND $data['password'] == $password AND $data['role'] == 'admin') {
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['role'] = $data['role'];

    echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job! '.$_SESSION["username"].'",
  "You are Logged In Please Wait!",
  "success"
)
    });
    </script>';
    header('refresh:1 dashboard.php');
  } else if ($data['email'] == $email AND $data['password'] == $password AND $data['role'] == 'user') {
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['role'] = $data['role'];

        echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job! '.$_SESSION["username"].'",
  "You are Logged In Please Wait!",
  "success"
)
    });
    </script>';
    header('refresh:1 user.php');
  } else {
            echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Wrong!",
  "Email Or Password is Wrong",
  "error"
)
    });
    </script>';
  }

}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pos</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <h1><a href="index.php"><b>INVENTORY</b> POS</a></h1>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-8">
              <p class="mb-1">
                <a href="#">I forgot my password</a>
              </p>
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block" name="login">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

</body>

</html>
