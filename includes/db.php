<?php

try {
	$pdo = new PDO('mysql:host=localhost;dbname=pos_db', 'root', '');
	
	if ($pdo) {
		//echo 'Connection Succesfully Created';
	}

} catch (PDOException $e) {
	echo $e->getmessage();
}

?>