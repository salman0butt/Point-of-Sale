<?php
session_start();
ob_start();
include_once 'includes/db.php';
include_once 'includes/header.php';

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
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
    
    echo'
    <tr>
    <td>'.$row->invoice_id.'</td>
    <td>'.$row->customer_name.'</td>
    <td>'.$row->order_date.'</td>
    <td>'.$row->total.'</td>
    <td>'.$row->paid.'</td>
    <td>'.$row->due.'</td>
    <td>'.$row->payment_type.'</td>
    
    
    <td>
<a href="invoice_80mm.php?id='.$row->invoice_id.'" class="btn btn-warning" role="button" target="_blank"><i class="fas fa-print" data-toggle="tooltip"  title="Print Invoice"></i></span></a>   
    
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
<?php

include_once'footer.php';

?>
