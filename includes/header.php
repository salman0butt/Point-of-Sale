<?php error_reporting(0); ?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>INVENTORY | POS</title>



  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../plugins/bootstrap-datepicker-1.9.0-dist_2/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
  <style>
    nav.main-header.navbar.navbar-expand.navbar-white.navbar-light {
          background-color: #3c8dbc;
    }
  .card-primary:not(.card-outline)>.card-header {
    background-color: #6993c0;
}  .dropdown-item-title {
    color: white !important;
  }
  </style>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script src="../../plugins/sweetalert2/sweetalert2@9.js"></script>
<script src="../../plugins/bootstrap-datepicker-1.9.0-dist_2/bootstrap-datepicker.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>
 <script src="Chart.js-2.8.0/dist/Chart.min.js"></script>

<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.flash.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
              <img src="dist/img/avatar5.png" alt="User Avatar" style="width: 30px;display:block;float: left;" class="img-size-50 mr-3 img-circle">
                <h3 class="dropdown-item-title" style="display:block;float: right;">
                 <?php echo $_SESSION['username'] ?>
                </h3>

        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
             <center> <img src="dist/img/avatar5.png" alt="User Avatar" class="mr-3 img-circle" style="width: 50%;"></center>
            </div>
            <!-- Message End -->
          </a>
    

          <div class="dropdown-divider"></div>
          <div class="text-center"><?php echo $_SESSION['email'] ?></div>
          <div class="dropdown-divider"></div>
          <div class="row">
          <a href="change_password.php" class="btn btn-primary col-md-5 btn-sm m-1" style="margin-left: 8px !important;">Change Password</a>
          <a href="logout.php" class="btn btn-danger col-md-6 btn-sm m-1">Logout</a>
          </div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link text-center">
      <span class="brand-text font-weight-bold">INVENTORY POS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><b>Welcome!</b>  <?php echo $_SESSION['username'] ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                         <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
                 <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashoboard
            
              </p>
            </a>
          </li>
                <li class="nav-item">
            <a href="category.php" class="nav-link">
           <i class="fas fa-folder-open"></i>
              <p>
                Category
              </p>
            </a>
          </li>
                <li class="nav-item">
            <a href="customers.php" class="nav-link">
         <i class="fas fa-users"></i>
              <p>
                Customers
              </p>
            </a>
          </li>
             <li class="nav-item">
            <a href="suppliers.php" class="nav-link">
         <i class="fas fa-users"></i>
              <p>
                Suppliers
              </p>
            </a>
          </li>


          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link">
              <i class="fas fa-bars"></i>
              <p>
                Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_product.php" class="nav-link">
                 <i class="fas fa-plus"></i> 
                  <p>Add Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="product_list.php" class="nav-link"> 
                  <i class="fas fa-eye"></i> 
                  <p>All Products</p>
                </a>
              </li>
            </ul>
          </li>
                    <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link">
              <i class="fas fa-bars"></i>
              <p>
                Orders
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="create_order.php" class="nav-link">
                 <i class="fas fa-plus"></i> 
                  <p>Create Order</p>
                </a>
              </li>
              <li class="nav-item">
                    <a href="orderlist.php" class="nav-link">
            <i class="fas fa-folder"></i>
              <p>
                 Order List
              </p>
            </a>
              </li>
            </ul>
          </li>
               <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link">
              <i class="fas fa-bars"></i>
              <p>
                Purchase
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="create_purchase.php" class="nav-link">
                 <i class="fas fa-plus"></i> 
                  <p>Add Purchase</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="purchase_list.php" class="nav-link"> 
                  <i class="fas fa-eye"></i> 
                  <p>All Purchase</p>
                </a>
              </li>
            </ul>
          </li>
  
      
           <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link">
              <i class="fas fa-bars"></i>
              <p>
                Sales Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                   <li class="nav-item">
                <a href="date_reports.php" class="nav-link">
                <i class="fas fa-signal"></i>
                  <p>Date Reports</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="table_reports.php" class="nav-link">
                <i class="fas fa-signal"></i>
                  <p>Table Reports</p>
                </a>
              </li>
              <li class="nav-item">
                    <a href="graph_reports.php" class="nav-link">
            <i class="fas fa-chart-pie"></i>
              <p>
                 Graph Reports
              </p>
            </a>
              </li>
            </ul>
          </li>
                  <li class="nav-item">
            <a href="single_customer.php" class="nav-link">
              <i class="fas fa-users"></i>
              <p>
                Customer Ledger
              </p>
            </a>
          </li>
            <li class="nav-item">
            <a href="single_supplier.php" class="nav-link">
              <i class="fas fa-users"></i>
              <p>
                Suplier Ledger
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="registration.php" class="nav-link">
              <i class="fas fa-users"></i>
              <p>
                Registration
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>