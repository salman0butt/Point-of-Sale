<?php

include_once '../includes/db.php';

$id = $_GET['id'];

$select = $pdo->prepare("SELECT * FROM `invoice` WHERE `customer_id` = :customer_id");
$select->bindParam(":customer_id", $id);
$select->execute();

$response  =array();
while($row = $select->fetch(PDO::FETCH_ASSOC)){
	$response[] = $row;
}


header('Content_Type: application/json');

echo json_encode($response);


?>