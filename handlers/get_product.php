<?php

include_once '../includes/db.php';

$id = $_GET['id'];

$select = $pdo->prepare("SELECT * FROM `products` WHERE `pid` = :product_id");
$select->bindParam(":product_id", $id);
$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC);

$response = $row;
$data = '';
$imei_number = array();
$order_imei = $pdo->prepare("SELECT * FROM `invoice_details` WHERE `product_id` = :id");
$order_imei->bindParam(":id", $id);
$order_imei->execute();
$get_imei = $order_imei->fetchAll(PDO::FETCH_ASSOC);
// $order_imei->dugDumpParebams();

if (count($get_imei) > 0) {

	for ($i = 0; $i < count($get_imei); $i++) {
		$check_imei = $pdo->prepare("SELECT A.imei FROM product_imei AS A LEFT OUTER JOIN invoice_details AS B ON A.imei = B.product_imei WHERE B.ID IS NULL");
		// $check_imei->bindParam(":product_id", $id);
		// $check_imei->bindParam(":product_imei", $get_imei[$i]['product_imei']);
		$check_imei->execute();
		$tests = $check_imei->fetchAll(PDO::FETCH_ASSOC);
	

		if ($tests) {
			foreach ($tests as $test) {
				$imei_number[] = $test['imei'];
			}
			break;
		}
	}
}else {
		$check_imei = $pdo->prepare("SELECT * FROM `product_imei` WHERE `product_id` = :product_id");
		$check_imei->bindParam(":product_id", $id);
		$check_imei->execute();
		$tests = $check_imei->fetch(PDO::FETCH_ASSOC);

		if ($tests) {
			foreach ($tests as $test) {
				$imei_number[] = $test['imei'];
			}
		}
}
header('Content_Type: application/json');
$data = ["response" => $response, "imei" => $imei_number];
echo json_encode($data);

?>