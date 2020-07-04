<?php
session_start();
include_once 'includes/db.php';
include_once 'includes/header.php';

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
}
if (isset($_GET['edit_id'])) {
  $edit_id = $_GET['edit_id'];
}

if (isset($_POST['update_product'])) {
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
               if(empty($image)) {
              $img_query = $pdo->prepare("SELECT * FROM `products` WHERE `pid` = $edit_id");
               $img_query->execute();
               $row=$img_query->fetch(PDO::FETCH_ASSOC);
               $new_file = $row['pimage'];
            }
            

            $update = $pdo->prepare("UPDATE `products` SET `p_name`=:p_name, `p_category`=:p_category, `purchase_price`=:purchase_price, `sale_price`=:sale_price, `pstock`=:pstock, `pdescription`=:pdescription, `pimage`=:pimage WHERE `pid` = $edit_id");

            $update->bindParam(':p_name', $product_name);
            $update->bindParam(':p_category', $product_category);
            $update->bindParam(':purchase_price', $purchase_price);
            $update->bindParam(':sale_price', $sale_price);
            $update->bindParam(':pstock', $stock);
            $update->bindParam(':pdescription', $description);

            $update->bindParam(':pimage', $new_file);

                $run = $update->execute();
          
            if ($image_extension == 'jpg' || $image_extension == 'jpeg' || $image_extension == 'png' || $image_extension == 'gif') {

                if (move_uploaded_file($tmp_name, $store)) {
                    echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Good job!",
  "Product Updated Successfully",
  "success"
)
    });
    </script>';

                } else {
                    echo '<script>
    jQuery(function validation(){
Swal.fire(
  "Warning",
  "Product Not Updated",
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
          <h1 class="m-0 text-dark">Edit Product</h1>
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
              <h3 class="card-title">Edit Product</h3>
            </div>
        <?php
  if (isset($_GET['edit_id'])) {
      $edit_id = $_GET['edit_id'];
        $stmt = $pdo->prepare("SELECT * FROM `products` WHERE `pid` = $edit_id");
        $stmt->execute();
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $pid = $row['pid'];
        $p_name = $row['p_name'];
        $p_category = $row['p_category'];
        $purchase_price = $row['purchase_price'];
        $sale_price = $row['sale_price'];
        $pstock = $row['pstock'];
        $pdescription = $row['pdescription'];
        $pimage = $row['pimage'];
}

        ?>
            <form action="" method="POST" enctype="multipart/form-data">
                  <a href="product_list.php" class="btn btn-info mt-3 ml-3">Back to Product List</a>
              <div class="card-body row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" class="form-control" id="product_name" placeholder="Enter Name" name="product_name" required value="<?php if(isset($p_name)) echo $p_name; ?>">
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
                      <option value="<?php echo $category; ?>" <?php if($p_category == $category) echo 'selected'; ?>><?php echo $category; ?></option>
                    <?php }?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="purchase_price">Purchase Price</label>
                    <input type="number" min="1" step="1" class="form-control" id="purchase_price" placeholder="Enter Price" name="purchase_price" required value="<?php if(isset($purchase_price)) echo $purchase_price; ?>">
                  </div>
                  <div class="form-group">
                    <label for="sale_price">Sale Price</label>
                    <input type="number" min="1" step="1" class="form-control" id="sale_price" placeholder="Enter Price" name="sale_price" required value="<?php if(isset($sale_price)) echo $sale_price; ?>">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-info" name="update_product" value="Update Product">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" min="1" step="1" class="form-control" id="stock" placeholder="Enter stock" name="stock" required value="<?php if(isset($pstock)) echo $pstock; ?>">
                  </div>
                  <div class="form-group">
                    <label for="description">Description</label>
                   <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Enter Description"><?php if(isset($pdescription)) echo $pdescription; ?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="image">Upload image</label>
                   <input type="file" name="myfile" id="image" class="form-control" onchange="readURL(this);">
                   <img id="blah" src="<?php if(isset($pimage)) echo "uploads/".$pimage; ?>" alt="your image" style="max-width: 100px;max-height: 100px;margin-top:10px;" />
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
<script>
       function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(50)
                        .height(50);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
<!-- /.content-wrapper -->
<?php
include_once 'includes/footer.php';

?>
