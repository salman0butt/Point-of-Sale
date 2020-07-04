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
try {
		$sql_query = "SELECT product_imei.* FROM `invoice_details` RIGHT OUTER JOIN `product_imei` ON (invoice_details.product_imei = product_imei.imei ) WHERE product_imei.product_id=:product_id AND invoice_details.id is null";
		$check_imei = $pdo->prepare($sql_query);
		$check_imei->bindParam(":product_id", $id);
		$check_imei->execute();
		$tests = $check_imei->fetchAll(PDO::FETCH_ASSOC);
		$imei_number = array_column($tests, 'imei');
		// $check_imei->dugDumpParebams();

	}catch(\Exception $e) {
		echo $e;
	}

header('Content_Type: application/json');
$data = ["response" => $response, "imei" => $imei_number];
echo json_encode($data);

?>