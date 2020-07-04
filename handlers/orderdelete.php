<?php

include_once '../includes/db.php';

$id = $_POST['pidd'];

$payments_details = "DELETE FROM `payments` WHERE invoice_id =$id";
$payment = $pdo->prepare($payments_details);
 $payment->bindParam(':id', $id);
$payment->execute();

// DELETE T1, T2 FROM T1 INNER JOIN T2 ON T1.key = T2.key  WHERE condition T1.key=id;


$sql = "delete `invoice` , `invoice_details` FROM `invoice` INNER JOIN `invoice_details` ON invoice.invoice_id = invoice_details.invoice_id where invoice.invoice_id=:id";

//$sql="delete from tbl_product where pid=$id";

$delete = $pdo->prepare($sql);
 $delete->bindParam(':id', $id);

if (!$delete->execute()) {
$sql_again = "delete FROM `invoice` WHERE invoice_id=:id";

$delete_again = $pdo->prepare($sql_again);
 $delete_again->bindParam(':id', $id);
 $delete_again->execute();
} else {

	echo 'DELETED';
}

?>