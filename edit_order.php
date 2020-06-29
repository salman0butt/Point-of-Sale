<?php
session_start();
ob_start();
include_once 'includes/db.php';
include_once 'includes/header.php';

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
	header('Location: index.php');
}

function fill_products($pdo, $pid = '') {

	$output = '';
	if (!empty($pid)) {

		$select = $pdo->prepare("select * from `products` order by p_name asc");
		$select->execute();

		$result = $select->fetchAll();

		foreach ($result as $row) {

			$output .= '<option value="' . $row["pid"] . '"';
			if ($pid == $row['pid']) {
				$output .= 'selected';

			}
			$output .= '>' . $row["p_name"] . '</option>';

		}

		return $output;
	} else {
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

$id = $_GET['id'];
$select = $pdo->prepare("SELECT * FROM `invoice` WHERE `invoice_id` =$id");
$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC);

$customer_name = $row['customer_name'];
$order_date = date('Y-m-d', strtotime($row['order_date']));
$subtotal = $row["subtotal"];
$tax = $row['tax'];
$discount = $row['discount'];
$total = $row['total'];
$paid = $row['paid'];
$due = $row['due'];
$payment_type = $row['payment_type'];

$select = $pdo->prepare("select * from invoice_details where invoice_id =$id");
$select->execute();

$row_invoice_details = $select->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['btnupdateorder'])) {

//Steps for btnupdateorder button.
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

// 2) Write update query for tbl_product stock.

	foreach ($row_invoice_details as $item_invoice_details) {

		$updateproduct = $pdo->prepare("update products set pstock=pstock+" . $item_invoice_details['qty'] . " where pid='" . $item_invoice_details['product_id'] . "'");
		$updateproduct->execute();
	}

// 3) Write delete query for tbl_invoice_details table data where invoice_id =$id .

	$delete_invoice_details = $pdo->prepare("delete from invoice_details where invoice_id=$id");

	$delete_invoice_details->execute();

	// 4) Write update query for tbl_invoice table data.
	$update_invoice = $pdo->prepare("update invoice set customer_name=:cust,order_date=:orderdate,subtotal=:stotal,tax=:tax,discount=:disc,total=:total,paid=:paid,due=:due,payment_type=:ptype where invoice_id=$id");

	$update_invoice->bindParam(':cust', $txt_customer_name);
	$update_invoice->bindParam(':orderdate', $txt_order_date);
	$update_invoice->bindParam(':stotal', $txt_subtotal);
	$update_invoice->bindParam(':tax', $txt_tax);
	$update_invoice->bindParam(':disc', $txt_discount);
	$update_invoice->bindParam(':total', $txt_total);
	$update_invoice->bindParam(':paid', $txt_paid);
	$update_invoice->bindParam(':due', $txt_due);
	$update_invoice->bindParam(':ptype', $txt_payment_type);

	$update_invoice->execute();

	$invoice_id = $pdo->lastInsertId();
	if ($invoice_id != null) {

		for ($i = 0; $i < count($arr_productid); $i++) {

// 5) Write select query for tbl_product table to get out stock value.

			$selectpdt = $pdo->prepare("select * from `products` where pid='" . $arr_productid[$i] . "'");
			$selectpdt->execute();

			while ($rowpdt = $selectpdt->fetch(PDO::FETCH_OBJ)) {

				$db_stock[$i] = $rowpdt->pstock;

				$rem_qty = $db_stock[$i] - $arr_qty[$i];

				if ($rem_qty < 0) {

					return "Order Is Not Complete";
				} else {

					// 6) Write update query for tbl_product table to update stock values.

					$update = $pdo->prepare("update `products` SET pstock ='$rem_qty' where pid='" . $arr_productid[$i] . "'");

					$update->execute();

				}

			}

			// 7) Write insert query for tbl_invoice_details for insert new records.

			$insert = $pdo->prepare("insert into invoice_details(invoice_id,product_id,product_name,qty,price,order_date) values(:invid,:pid,:pname,:qty,:price,:orderdate)");

			$insert->bindParam(':invid', $id);
			$insert->bindParam(':pid', $arr_productid[$i]);
			$insert->bindParam(':pname', $arr_productname[$i]);
			$insert->bindParam(':qty', $arr_qty[$i]);
			$insert->bindParam(':price', $arr_price[$i]);
			$insert->bindParam(':orderdate', $txt_order_date);

			$insert->execute();

		}

		//  echo"success fully created order";
		header('location:orderlist.php');

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
                      <input type="text" name="customer_name" class="form-control" value="<?php if (isset($customer_name)) {
	echo $customer_name;
}
?>" placeholder="Customer Name">
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
                      <input type="date" class="form-control" name="order_date" value="<?php if (isset($order_date)) {
	echo $order_date;
}
?>" data-date-format="yyyy/mm/dd">
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
                          <td>Stock</td>
                          <td>Price</td>
                          <td>Enter Quantity</td>
                          <td>Total</td>
                          <td><button type="button" class="btn btn-success" id="add_btn"><i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody id="produt_table">
                        <?php
foreach ($row_invoice_details as $item_invoice_details) {

	$select = $pdo->prepare("select * from `products` where pid ='{$item_invoice_details['product_id']}'");
	$select->execute();

	$row_product = $select->fetch(PDO::FETCH_ASSOC);

	?>
                        <tr>
                          <?php
echo '<td><input type="hidden" class="form-control pname" name="product_name[]" value="' . $row_product['p_name'] . '" readonly></td>';

	echo '<td><select id="product_id" class="form-control product_id" name="product_id[]" style="width: 250px";><option value="">Select Option</option>' . fill_products($pdo, $item_invoice_details['product_id']) . ' </select></td>';

	echo '<td><input type="text" class="form-control stock" name="stock[]" value="' . $row_product['pstock'] . '" readonly></td>';
	echo '<td><input type="text" class="form-control price" name="price[]" value="' . $row_product['sale_price'] . '" readonly></td>';
	echo '<td><input type="number" min="1" id="qty" class="form-control qty" name="qty[]" onclick="calculateQtyPrice($(this))" value="' . $item_invoice_details['qty'] . '" ></td>';
	echo '<td><input type="text" class="form-control total" name="total[]" value="' . $row_product['sale_price'] * $item_invoice_details['qty'] . '" readonly></td>';
	echo '<td><center><button type="button" name="remove" id="btn_remove" class="btn btn-danger btn-sm btn_remove"><i class="fas fa-times"></i></button><center></td></center>';

	?>
                        </tr>
                        <?php }?>
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
                      <input type="number" class="form-control" name="sub-total" id="sub-total" readonly value="<?php if (isset($subtotal)) {
	echo $subtotal;
}
?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name">Tax (5%)</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" name="tax" readonly id="tax" value="<?php if (isset($tax)) {
	echo $tax;
}
?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name">Discount</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" name="discount" id="discount" value="<?php if (isset($discount)) {
	echo $discount;
}
?>">
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
                      <input type="number" min="0" class="form-control" readonly name="total" id="total" value="<?php if (isset($total)) {
	echo $total;
}
?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name">Paid</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" name="paid" id="paid" value="<?php if (isset($paid)) {
	echo $paid;
}
?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name">Due</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                      </div>
                      <input type="number" min="0" class="form-control" readonly name="due" id="due" value="<?php if (isset($due)) {
	echo $due;
}
?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_name" class="d-block">Payment Method</label>
                    <label>
                      <input type="radio" name="rb" class="minimal-red" value="Cash" <?php if ($payment_type == 'Cash') {
	echo 'checked';
}
?>> CASH
                    </label>
                    <label>
                      <input type="radio" name="rb" class="minimal-red" value="Card" <?php if ($payment_type == 'Card') {
	echo 'checked';
}
?>> CARD
                    </label>
                    <label>
                      <input type="radio" name="rb" class="minimal-red" value="Check" <?php if ($payment_type == 'Check') {
	echo 'checked';
}
?>> CHECK
                    </label>
                  </div>
                  <input type="submit" name="btnupdateorder" class="btn btn-info" value="Update Order">
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

<script>
jQuery(document).ready(function($) {


  $('.datepicker').datepicker({
    format: 'mm/dd/yyyy'
  });
  $(document).on('click','#add_btn', function() {
    var html = '';
    html += '<tr>';
    html += '<td><input type="hidden" class="form-control pname" name="product_name[]" readonly/></td>';
    html += '<td><select class="form-control product_id" style="width:250px;" name="product_id[]"> <option value="">Select Option</option> <?php echo fill_products($pdo); ?> </select></td>';
    html += '<td><input type="text" class="form-control stock" name="stock[]" readonly/></td>';
    html += '<td><input type="text" class="form-control price" name="price[]" readonly/></td>';
    html += '<td><input type="number" min="1" id="qty" onclick="calculateQtyPrice($(this))" class="form-control qty" name="qty[]"/></td>';
    html += '<td><input type="text" class="form-control total" name="total[]" readonly/></td>';
    html += '<td><center> <button type="button" name="remove" id="btn_remove" class="btn btn-danger btn-sm btn_remove"><i class="fas fa-times"></i></button></center></td>';
    html += '</tr>';
    $('#produt_table').append(html);

        //initialze select 2 plugin
    $('.product_id').select2();

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

function calculateTotal(disc = 0, paid = 0) {
  var subtotal = 0;
  var tax = 0;
  var discount = disc;
  var net_total = 0;
  var paid_amt = paid;
  var due = 0;

  $('.total').each(function() {
    subtotal = subtotal + ($(this).val() * 1);
  });
  tax = 0;
  net_total = tax + subtotal;
  net_total = net_total - discount;
  due = net_total - paid_amt;

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
  var discount = $('#discount').val();
  calculateTotal(discount, paid);
});
    //initialze select 2 plugin
    $('.product_id').select2();

    $(document).on('change',".product_id", function(e) {
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

//lecture no 12 sales section

</script>

<?php
include_once 'includes/footer.php';
ob_end_flush();
?>