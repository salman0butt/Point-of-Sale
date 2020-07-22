<?php
session_start();
include_once 'includes/db.php';
include_once 'includes/header.php';

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
}
function customers($pdo) {

  $output = '';
  $select = $pdo->prepare("SELECT * FROM `customers` ORDER BY `customer_name` ASC");
  $select->execute();
  $result = $select->fetchAll();
  // var_dump($result);
  foreach ($result as $row) {
    $output .= '<option value="' . $row['customer_name'] . " (" . $row["father_name"] . ")" . '" data-id="' . $row["id"] . '">' . $row["customer_name"] . " (" . $row["father_name"] . ")" . '</option>';
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
          <h1 class="m-0 text-dark">Customer Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dasboard</a></li>
            <li class="breadcrumb-item active">Customer Details</li>
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
                <select class="form-control customer" id="customer" name="customer[]">
                  <option value="">Select Option</option>
                  <?php echo customers($pdo); ?>
                </select>
                <input type="hidden" value="" id="customer_id" name="customer_id">
                
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
                <th>CustomerName</th>
                <th>Subtotal</th>
                <th>Grunter</th>
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
              $customer_id = $_POST['customer_id'];

$select = $pdo->prepare("select * from invoice where customer_id = :customer_id");
$select->bindParam(':customer_id', $customer_id);

$select->execute();

while ($row = $select->fetch(PDO::FETCH_OBJ)) {
   $invoice_id = $row->invoice_id;
      $all_paid = 0;
    $previous_payment=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id"); $previous_payment->bindParam(':invoice_id', $invoice_id);
      $previous_payment->execute();
      while($row_data=$previous_payment->fetch(PDO::FETCH_OBJ)  ){
        $all_paid = $all_paid + $row_data->payment;     
       }
       $all_paid = $all_paid+$row->paid;
       $all_due = $row->total-$all_paid;

  echo '
    <tr>
    <td>' . $row->invoice_id . '</td>
    <td>' . $row->customer_name . '</td>
   <td>' . $row->subtotal . '</td>
    <td>' . $row->grunter . '</td>
     <td>' . $row->discount . '</td>
    <td><span class="badge badge-success">' . $row->total . '</span></td>
     <td>' . $all_paid. '</td>
      <td><span class="badge badge-danger">' . $all_due . '</span></td>

     <td>' . $row->order_date . '</td>

     ';

  if ($row->payment_type == "Cash") {

    echo '<td><span class="badge badge-primary">' . $row->payment_type . '</span></td>';
  } elseif ($row->payment_type == "Card") {
    echo '<td><span class="badge badge-warning">' . $row->payment_type . '</span></td>';
  } else {
    echo '<td><span class="badge badge-info">' . $row->payment_type . '</span></td>';
  }
  echo '<td><a href="single_customer_invoice.php?invoice_id='. $row->invoice_id.'" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a></td>';
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
  $('.customer').select2();
  $('#customer').on('select2:select', function(e) {
    var data = e.params.data.element.dataset.id;
    console.log(data);
    $('#customer_id').val(data);
   

    // $.ajax({
    //     method: "GET",
    //     url: "handlers/customer_details.php",
    //     data: { "id": data }
    //   })
    //   .done(function(msg) {
    //      data = jQuery.parseJSON(msg);
    //      $('.dataTables_empty').parent().remove();
    //      $.each( data, function( key, value ) {
    //           console.log(value);
    //            var html = '';
    //     html += '<tr>';
    //     html += '<td>'+value.invoice_id+'</td>';
    //     html += '<td>'+value.customer_name+'</td>';
    //     html += '<td>'+value.subtotal+'</td>';
    //     html += '<td>'+value.grunter+'</td>';
    //     html += '<td>'+value.discount+'</td>';
    //     html += '<td><span class="badge badge-success">'+value.total+'</span></td>';
    //     html += '<td><span class="badge badge-primary">'+value.paid+'</span></td>';
    //     html += '<td><span class="badge badge-danger">'+value.due+'</span></td>';
    //     html += '<td>'+value.order_date+'</td>';
    //     html += '<td><span class="badge badge-info">'+value.payment_type+'</span></td>';
    //     html += '</tr>';
    //     $('#salesreporttable').append(html);
      // });
 
      // });
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
