<?php 
include_once 'includes/db.php';

if ($_SESSION['username'] == '' AND empty($_SESSION['username'])) {
  header('Location: index.php');
}
$id = $_POST['idd'];
$sql = "DELETE FROM `products` WHERE `pid`=$id";
$delete = $pdo->prepare($sql);

if ($delete->execute()) {
	return true;
	
}else {
	echo 'Error in Deleting';
}

 ?>