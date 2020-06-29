<?php
session_start();
ob_start();
include_once 'includes/db.php';
include_once 'includes/header.php';

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
}

function fill_products($pdo) {

  $output = '';
  $select = $pdo->prepare("SELECT * FROM `products` ORDER BY `p_name` ASC");
  $select->execute();
  $result = $select->fetchAll();
  // var_dump($result);
  foreach ($result as $row) {
    $output .= '<option value="' . $row['pid'] . '">' . $row["p_name"] . '</option>';
  }

  return $output;

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

if (isset($_POST['save_order'])) {

  $customer_name = $_POST['customer_name'];
  $customer_id = $_POST['customer_id'];

  $grunters = $pdo->prepare("SELECT * FROM `customers` Where id=:customer_id");
  $grunters->bindParam(':customer_id', $customer_id);
  $grunters->execute();
  $row = $grunters->fetch(PDO::FETCH_OBJ);

  $grunter = $row->grunter_name;
  $order_date = date('Y-m-d', strtotime($_POST['order_date']));
  $subtotal = $_POST["sub-total"];
  $tax = $_POST['tax'];
  $discount = $_POST['discount'];
  $total = $_POST['total'];
  $paid = $_POST['paid'];
  $due = $_POST['due'];
  $payment_type = $_POST['rb'];

 
  $arr_productid = $_POST['product_id'];
  $arr_productname = $_POST['product_name'];
  $arr_productiemi = $_POST['product_imei'];
  echo '<script>console.log("'.$arr_productiemi.');</script>';
  $arr_stock = $_POST['stock'];
  $arr_qty = $_POST['qty'];
  $arr_price = $_POST['price'];
  $arr_total = $_POST['total'];

  $insert = $pdo->prepare("insert into `invoice`(`customer_name`,`customer_id`,`grunter`, `order_date`, `subtotal`, `tax`, `discount`, `total`, `paid`, `due`, `payment_type`) values(:cust,:customer_id ,:grunter ,:orderdate,:stotal,:tax,:disc,:total,:paid,:due,:ptype)");
  
  $insert->bindParam(':cust', $customer_name);
  $insert->bindParam(':customer_id', $customer_id);
  $insert->bindParam(':grunter', $grunter);
  $insert->bindParam(':orderdate', $order_date);
  $insert->bindParam(':stotal', $subtotal);
  $insert->bindParam(':tax', $tax);
  $insert->bindParam(':disc', $discount);
  $insert->bindParam(':total', $total);
  $insert->bindParam(':paid', $paid);
  $insert->bindParam(':due', $due);
  $insert->bindParam(':ptype', $payment_type);

  $insert->execute();
   //$insert->debugDumpParams();


  //2nd  insert query for tbl_invoice_details

  $invoice_id = $pdo->lastInsertId();
  if ($invoice_id != null) {
   
    for ($i = 0; $i < count($arr_productid); $i++) {

      $rem_qty = $arr_stock[$i] - $arr_qty[$i];

      if ($rem_qty < 0) {

        return "Order Is Not Complete";
      } else {

        $update = $pdo->prepare("update `products` SET pstock ='$rem_qty' where pid='" . $arr_productid[$i] . "'");

        $update->execute();

      }

      $insert = $pdo->prepare("insert into `invoice_details`(`invoice_id`, `product_id`, `product_name`, `product_imei`, `qty`, `price`, `order_date`) values(:invid,:pid,:pname,:imei,:qty,:price,:orderdate)");

      $insert->bindParam(':invid', $invoice_id);
      $insert->bindParam(':pid', $arr_productid[$i]);
      $insert->bindParam(':pname', $arr_productname[$i]);
      $insert->bindParam(':imei', $arr_productiemi[$i]);
      $insert->bindParam(':qty', $arr_qty[$i]);
      $insert->bindParam(':price', $arr_price[$i]);
      $insert->bindParam(':orderdate', $order_date);

      $insert->execute();


    }
    //  echo"success fully created order";
     header('location:orderlist.php');    
    //echo '<script>alert("Order Placed Successfully")</script>';

  }
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
          <h1 class="m-0 text-dark">Create Order</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dasboard</a></li>
            <li class="breadcrumb-item active">Create Order</li>
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
        <div class="col-lg-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Create Order</h3>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="card-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="product_name">Customer Name</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                      </div>
                    <select class="form-control customer" id="customer_name" name="customer_name"> <option value="">Select Option</option> <?php echo customers($pdo); ?> </select>
                    <input type="hidden" name="customer_id" id="customer_id" value="">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="stock">Order Date</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                      <input type="date" class="form-control" name="order_date" value="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy/mm/dd">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" style="overflow-x: hidden">
                <div class="col-md-12">
                  <div style="overflow-x: auto;">
                    <table class="table table-striped table-bordered" id="order_table">
                      <thead>
                        <tr>
                          <td>#</td>
                          <td>Search Product</td>
                          <td>IMEI</td>
                          <td>Stock</td>
                          <td>Price</td>
                          <td>Enter Quantity</td>
                          <td>Total</td>
                          <td><button type="button" class="btn btn-success" id="add_btn"><i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody id="produt_table">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="row card-body">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="product_name">Sub Total</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" class="form-control" name="sub-total" id="sub-total" readonly placeholder="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name">Tax (5%)</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" name="tax" readonly id="tax" placeholder="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name">Discount</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" name="discount" id="discount" placeholder="">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="product_name">Total</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" readonly name="total" id="total" placeholder="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name">Paid</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" name="paid" id="paid" placeholder="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name">Due</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" readonly name="due" id="due" placeholder="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name" class="d-block">Payment Method</label>
                    <label>
                      <input type="radio" name="rb" class="minimal-red" value="Cash" checked> CASH
                    </label>
                    <label>
                      <input type="radio" name="rb" class="minimal-red" value="Card"> CARD
                    </label>
                    <label>
                      <input type="radio" name="rb" class="minimal-red" value="Check"> CHECK
                    </label>
                  </div>
                  <input type="submit" name="save_order" class="btn btn-info" value="Save Order">
                </div>
            </form>
          </div>
        </div>
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
include_once 'includes/footer.php';
ob_end_flush();
?>
<script>
jQuery(document).ready(function($) {

  $('.customer').select2();
   $('#customer_name').on('select2:select', function(e) {
    var data = e.params.data.element.dataset.id;
    console.log(data);
    $('#customer_id').val(data);
   });
  $('.datepicker').datepicker({
    format: 'mm/dd/yyyy'
  });
  $('#add_btn').click(function() {
    var html = '';
    html += '<tr>';
    html += '<td><input type="hidden" class="form-control pname" name="product_name[]" readonly/></td>';
    html += '<td><select class="form-control product_id" style="width:250px;" name="product_id[]"> <option value="">Select Option</option> <?php echo fill_products($pdo); ?> </select></td>';
    html += '<td><input type="text" class="form-control imei" name="product_imei[]"/></td>';
    html += '<td><input type="text" class="form-control stock" name="stock[]" readonly/></td>';
    html += '<td><input type="text" class="form-control price" name="price[]" readonly/></td>';
    html += '<td><input type="number" min="1" id="qty" onclick="calculateQtyPrice($(this))" class="form-control qty" name="qty[]"/></td>';
    html += '<td><input type="text" class="form-control total" name="total[]" readonly/></td>';
    html += '<td><center> <button type="button" name="remove" id="btn_remove" class="btn btn-danger btn-sm btn_remove"><i class="fas fa-times"></i></button></center></td>';
    html += '</tr>';
    $('#produt_table').append(html);

    //initialze select 2 plugin
    $('.product_id').select2();


    $(".product_id").on('change', function(e) {
      e.stopPropagation();
      var product_id = this.value;
      var tr = $(this).parent().parent();
      $.ajax({
        url: "handlers/get_product.php",
        method: 'get',
        data: { id: product_id },
        success: function(response) {
          data = JSON.parse(response);
           tr.find(".pname").val(data["p_name"]);
          tr.find(".stock").val(data["pstock"]);
          tr.find(".price").val(data["sale_price"]);
          tr.find(".qty").val(1);
          tr.find(".total").val(tr.find('.qty').val() * tr.find('.price').val());
          calculateTotal();
        }
      });

    });

  });

});
jQuery(document).on('click', '.btn_remove', function() {
  $(this).closest('tr').remove();
  $('#paid').val(0);
  calculateTotal();
});

function calculateQtyPrice(this_obj) {
  var quantity = this_obj;
  var tr = this_obj.parent().parent();
  if ((quantity.val() - 0) > (tr.find(".stock").val() - 0)) {

    Swal.fire(
      "Warning",
      "Sorry! This much Quantity is not available",
      "warning"
    );
    quantity.val(1);
    tr.find('.total').val(quantity.val() * tr.find('.price').val());
    calculateTotal();
  } else {
    tr.find('.total').val(quantity.val() * tr.find('.price').val());
    calculateTotal();
  }
}

function calculateTotal(disc=0, paid=0) {
  var subtotal = 0;
  var tax = 0;
  var discount = disc;
  var net_total = 0;
  var paid_amt = paid;
  var due = 0;

  $('.total').each(function() {
    subtotal = subtotal + ($(this).val()*1);
  });
  // tax * subtotal
  tax = 0;
  net_total = tax+subtotal;
  net_total = net_total-discount;
  due = net_total-paid_amt;

  $('#sub-total').val(subtotal.toFixed(2));
  $('#tax').val(tax.toFixed(2));
  $('#total').val(net_total.toFixed(2));
  $('#discount').val(discount);
  $('#due').val(due.toFixed(2));

}
$('#discount').keyup(function(event) {
  var discount = $(this).val();
  calculateTotal(discount);
  $('#paid').val(0);
});

$('#paid').keyup(function(event) {
  var paid = $(this).val();
  var discount  = $('#discount').val();
  calculateTotal(discount,paid);
});



</script>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>