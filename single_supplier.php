<?php
session_start();
include_once 'includes/db.php';
include_once 'includes/header.php';

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
}
function suppliers($pdo) {

  $output = '';
  $select = $pdo->prepare("SELECT * FROM `suppliers` ORDER BY `supplier_name` ASC");
  $select->execute();
  $result = $select->fetchAll();
  // var_dump($result);
  foreach ($result as $row) {
    $output .= '<option value="' . $row['supplier_name'] . " (" . $row["father_name"] . ")" . '" data-id="' . $row["id"] . '">' . $row["supplier_name"] . " (" . $row["father_name"] . ")" . '</option>';
  }

  return $output;

} 

?>
<style type="text/css" media="screen">
.select2-selection {
  padding: 20px !important;
}

.select2-selection__rendered {
  line-height: 13px !important;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Supplier Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dasboard</a></li>
            <li class="breadcrumb-item active">Supplier Details</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <form action="" method="post" id="customer_data">
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"> <i class="fa fa-calendar"></i></span>
                </div>
                <select class="form-control supplier" id="supplier" name="supplier[]">
                  <option value="">Select Option</option>
                  <?php echo suppliers($pdo); ?>
                </select>
                <input type="hidden" value="" id="supplier_id" name="supplier_id">
                
              </div>
            </div>
            <div class="col-md-6">
              <input type="submit" value="Submit" id="submit" class="btn btn-success" name="submit">
            </div>
          </div>
          <br>
          <table id="salesreporttable" class="table table-striped">
            <thead>
              <tr>
                <th>Invoice ID</th>
                <th>SupplierName</th>
                <th>Subtotal</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Due</th>
                <th>OrderDate</th>
                <th>Payment Type</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $supplier_id = $_POST['supplier_id'];

$select = $pdo->prepare("select * from purchase_invoice where supplier_id = :supplier_id");
$select->bindParam(':supplier_id', $supplier_id);

$select->execute();

while ($row = $select->fetch(PDO::FETCH_OBJ)) {
   $invoice_id = $row->invoice_id;
      $all_paid = 0;
    // $previous_payment=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id"); $previous_payment->bindParam(':invoice_id', $invoice_id);
    //   $previous_payment->execute();
    //   while($row_data=$previous_payment->fetch(PDO::FETCH_OBJ)  ){
    //     $all_paid = $all_paid + $row_data->payment;     
    //    }
       $all_paid = $all_paid+$row->paid;
       $all_due = $row->total-$all_paid;

  echo '
    <tr>
    <td>' . $row->invoice_id . '</td>
    <td>' . $row->supplier_name . '</td>
   <td>' . $row->subtotal . '</td>
     <td>' . $row->discount . '</td>
    <td><span class="badge badge-success">' . $row->total . '</span></td>
     <td>' . $all_paid. '</td>
      <td><span class="badge badge-danger">' . $all_due . '</span></td>
     <td>' . $row->purchase_date . '</td>

     ';

  if ($row->payment_type == "Cash") {

    echo '<td><span class="badge badge-primary">' . $row->payment_type . '</span></td>';
  } elseif ($row->payment_type == "Card") {
    echo '<td><span class="badge badge-warning">' . $row->payment_type . '</span></td>';
  } else {
    echo '<td><span class="badge badge-info">' . $row->payment_type . '</span></td>';
  }
  echo '<td><a href="single_supplier_invoice.php?invoice_id='. $row->invoice_id.'" class="btn btn-primary btn-sm">View Details</a></td>';
}

?>
            </tbody>
          </table>
        </div>
      </form>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
$(document).ready(function() {

  $('#salesreporttable').DataTable({
    dom: 'Bfrtip',
    buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "order": [
      [0, "desc"]
    ]

  });
  //initialze select 2 plugin
  $('.supplier').select2();
  $('#supplier').on('select2:select', function(e) {
    var data = e.params.data.element.dataset.id;
    console.log(data);
    $('#supplier_id').val(data);
   
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
