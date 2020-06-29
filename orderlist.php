<?php
session_start();
ob_start();
include_once 'includes/db.php';
include_once 'includes/header.php';

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
}

if (isset($_POST['add_payment'])) {
    if (!empty($_POST)) {
  $payment = $_POST['payment'];
  $invoice_id = $_POST['invoice_id'];
  $due = $_POST['due']-$_POST['payment'];
  $date = date('Y-m-d');
  $insert_payment=$pdo->prepare("INSERT INTO `payments`(`invoice_id`, `payment`, `due`, `date`) VALUES (:invoice_id,:payment,:due,:date)");
  $insert_payment->bindParam(':invoice_id', $invoice_id);
  $insert_payment->bindParam(':payment', $payment);
  $insert_payment->bindParam(':date', $date);
  $insert_payment->bindParam(':due', $due);
  $insert_payment->execute();
  }
}
    

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Order Lists
      <small></small>
    </h1>
    <!--     <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Level</a></li>
        <li class="active">Here</li>
      </ol> -->
  </section>
  <!-- Main content -->
  <section class="content container-fluid">
    <!--------------------------
        | Your Page Content Here |
        -------------------------->
    <div class="box box-warning">
      <div class="box-body">
        <div style="overflow-x:auto;">
          <table id="orderlisttable" class="table table-striped">
            <thead>
              <tr>
                <th>Invoice ID</th>
                <th>CustomerName</th>
                <th>Grunter</th>
                <th>OrderDate</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Payment Type</th>
                <th>Print</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php
    $select=$pdo->prepare("select * from invoice  order by invoice_id desc");
            
    $select->execute();
            
while($row=$select->fetch(PDO::FETCH_OBJ)  ){
      $invoice_id = $row->invoice_id;
      $all_paid = 0;
    $previous_payment=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id"); $previous_payment->bindParam(':invoice_id', $invoice_id);
      $previous_payment->execute();
      while($row_data=$previous_payment->fetch(PDO::FETCH_OBJ)  ){
        $all_paid = $all_paid + $row_data->payment;     
       }
       $all_paid = $all_paid+$row->paid;
       $all_due = $row->total-$all_paid;
    echo'
    <tr>
    <td>'.$row->invoice_id.'</td>
    <td>'.$row->customer_name.'</td>
    <td>'.$row->grunter.'</td>
    <td>'.$row->order_date.'</td>
    <td><span class="badge badge-info">'.$row->total.'</span></td>
    <td><span class="badge badge-success">'.$all_paid.'</span></td>
    <td><span class="badge badge-danger">'.$all_due.'</span></td>
    <td><span class="badge badge-primary">'.$row->payment_type.'</span></td>
    
    
    <td>
  <button id="add_payment_btn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-id="'.$row->invoice_id.'" data-due="'.$all_due.'"data-whatever="@mdo">Add Payment </button> 
<a href="invoice_db.php?id='.$row->invoice_id.'" class="btn btn-success" role="button" target="_blank"><i class="fas fa-print" data-toggle="tooltip"  title="Print Invoice"></i></span></a>  

    
    </td>
    
    
    <td>
<a href="edit_order.php?id='.$row->invoice_id.'" class="btn btn-info" role="button"><i class="fas fa-edit" data-toggle="tooltip"  title="Edit order"></i></a>   
    
    </td>
    
    <td>
<button id='.$row->invoice_id.' class="btn btn-danger btndelete" ><i class="fas fa-trash" data-toggle="tooltip"  title="Delete Order"></i></button>  
    
    
    </td>
     </tr>
     ';
    
}          
?>
            </tbody>
          </table>
        </div>
      </div>
      <!--              </form>-->
    </div>
  </section>
  <!-- /.content -->


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST">
          <div class="form-group">
            <label for="payment" class="col-form-label">Price:</label>
            <input type="number" class="form-control" id="payment" name="payment">
            <input type="hidden" class="form-control" id="invoice_id" name="invoice_id">
            <input type="hidden" class="form-control" id="due_id" name="due">
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="add_payment" value="Add Payment">
      </div>
      </form>
    </div>
  </div>
</div>
</div>
<!-- /.content-wrapper -->
<script>
$(document).ready(function() {
  $('#orderlisttable').DataTable({
    "order": [
      [0, "desc"]
    ]
  });
});

</script>
<script>
$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});

$(document).on('click', '#add_payment_btn', function() {
  console.log($(this));
  $('#invoice_id').val($(this)[0].attributes[5].nodeValue);
  $('#due_id').val($(this)[0].attributes[6].nodeValue);
});

$(document).ready(function() {
  $('.btndelete').click(function() {
    var tdh = $(this);
    var id = $(this).attr("id");
    Swal.fire({
      title: 'Do you want to delete Order?',
      text: "Once Order is deleted, you can not recover it!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'handlers/orderdelete.php',
          type: 'post',
          data: {
            pidd: id
          },
          success: function(data) {
            tdh.parents('tr').hide();
          }
        });

        Swal.fire(
          "success",
          "Your Order has been deleted!",
          "success"
        );
      } else {
        Swal.fire(
          "warning",
          "Your Order is Safe",
          "warning"
        );
      }
    });
  });

});

</script>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php

include_once'footer.php';

?>
