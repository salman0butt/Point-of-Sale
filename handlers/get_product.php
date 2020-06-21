<?php

include_once '../includes/db.php';

$id = $_GET['id'];

$select = $pdo->prepare("SELECT * FROM `products` WHERE `pid` = :product_id");
$select->bindParam(":product_id", $id);
$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC);

$response = $row;

header('Content_Type: application/json');

echo json_encode($response);


?>