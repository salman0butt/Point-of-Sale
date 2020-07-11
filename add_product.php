<?php
session_start();
include_once 'includes/db.php';
include_once 'includes/header.php';

if (isset($_POST['add_product'])) {

	$product_imeis = explode(',', $_POST['product_imei']);

	$product_name = $_POST['product_name'];
	$product_category = $_POST['product_category'];
	$purchase_price = $_POST['purchase_price'];
	$sale_price = $_POST['sale_price'];
	$stock = $_POST['stock'];
	$description = $_POST['description'];
	$image = $_FILES['myfile']['name'];
	$tmp_name = $_FILES['myfile']['tmp_name'];
	$image_size = $_FILES['myfile']['size'];

	$image_extension = explode('.', $image);
	$image_extension = strtolower(end($image_extension));

	$new_file = uniqid() . '.' . $image_extension;
	if (empty($image)) {
		$new_file = 'no-image.png';
	}

	$store = "uploads/" . $new_file;

	if (isset($_POST['product_name'])) {
		$pro_name_check = $pdo->prepare("SELECT * FROM `products` WHERE `pname` = '$product_name'");
		$pro_name_check->execute();
		if ($pro_name_check->rowCount() > 0) {
			echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Product Already exists!",
  "warning"
)
    });
    </script>';
		} else {

			try {
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->beginTransaction();

				$insert = $pdo->prepare("INSERT INTO `products`(`p_name`, `p_category`, `purchase_price`, `sale_price`, `pstock`, `pdescription`, `pimage`) VALUES(:p_name,:p_category,:purchase_price,:sale_price,:pstock,:pdescription,:pimage)");
				$insert->bindParam(':p_name', $product_name);
				$insert->bindParam(':p_category', $product_category);
				$insert->bindParam(':purchase_price', $purchase_price);
				$insert->bindParam(':sale_price', $sale_price);
				$insert->bindParam(':pstock', $stock);
				$insert->bindParam(':pdescription', $description);
				$insert->bindParam(':pimage', $new_file);

				$run = $insert->execute();
        $prod_id = $pdo->lastInsertId();
				$pdo->commit();
			} catch (Exception $e) {
				$pdo->rollback();
			}
			// $insert->debugDumpParams();
		
			// print_r($prod_id);

			foreach ($product_imeis as $product_imei) {
        if(empty($product_imei)){
          continue;
        }
				try {
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$pdo->beginTransaction();

					$insert_imei = $pdo->prepare("INSERT INTO `product_imei`(`product_id`, `imei`)  VALUES(:product_id,:product_imei)");
					$insert_imei->bindParam(':product_id', $prod_id);
					$insert_imei->bindParam(':product_imei', $product_imei);
					$insert_imei->execute();
					$pdo->commit();
				} catch (Exception $e) {
					$pdo->rollback();
				}
				// $insert_imei->debugDumpParams();
			}
			echo '<script>
          jQuery(function validation(){
      Swal.fire(
        "Good job!",
        "Product Saved Successfully",
        "success"
      )
          });
          </script>';

			if ($image_extension == 'jpg' || $image_extension == 'jpeg' || $image_extension == 'png' || $image_extension == 'gif') {

				if (move_uploaded_file($tmp_name, $store)) {

				} else {
					echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Product Not Saved",
  "warning"
)
    });
    </script>';
				}
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
          <h1 class="m-0 text-dark">Add Products</h1>
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
        <div class="col-lg-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Product</h3>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                  <a href="product_list.php" class="btn btn-info mt-3 ml-3">Back to Product List</a>
              <div class="card-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" class="form-control" id="product_name" placeholder="Enter Name" name="product_name" required>
                  </div>
                  <div class="form-group">
                    <label for="product_category">Product Category</label>
                    <select name="product_category" id="product_category" class="form-control">
                      <option value="" disabled selected>Choose Category</option>
                      <?php
$select = $pdo->prepare("SELECT * FROM `category` ORDER BY `cat_id` DESC");
$select->execute();
while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
	$category = $row['name'];
	?>
                      <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                    <?php }?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="purchase_price">Purchase Price</label>
                    <input type="number" value="0" min="1" step="1" class="form-control" id="purchase_price" placeholder="Enter Price" name="purchase_price" required>
                  </div>
                  <div class="form-group">
                    <label for="sale_price">Sale Price</label>
                    <input type="number" value="0" min="1" step="1" class="form-control" id="sale_price" placeholder="Enter Price" name="sale_price" required>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-info" name="add_product" value="Add Product">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" value="0" min="1" step="1" class="form-control" id="stock" placeholder="Enter stock" name="stock" required>
                  </div>
                  <div class="form-group">
                    <label for="description">Description</label>
                   <textarea name="description" id="description" cols="30" rows="2" class="form-control" placeholder="Enter Description"></textarea>
                  </div>
                   <div class="form-group">
                    <label for="product_imei">Poduct IMEI</label>
                   <textarea name="product_imei" id="product_imei" cols="30" rows="2" class="form-control" placeholder="Enter IMEI"></textarea>
                  </div>

                  <div class="form-group">
                    <label for="image">Upload image</label>
                   <input type="file" name="myfile" id="image" class="form-control">
                  </div>
                </div>
              </div>
            </form>
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
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php
include_once 'includes/footer.php';

?>
