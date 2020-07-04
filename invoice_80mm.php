<?php
require('fpdf/fpdf.php');
include_once 'includes/db.php';


$id=$_GET['id'];
$select=$pdo->prepare("select * from invoice where invoice_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);



//create pdf object
$pdf = new FPDF('P','mm',array(80,200));

//add new page
$pdf->AddPage();

//set font to arial, bold, 16pt
$pdf->SetFont('Arial','B',16);
//Cell(width , height , text , border , end line , [align] )
$pdf->Cell(60,8,'MURTAZA MOBILES',1,1,'C');

$pdf->SetFont('Arial','B',8);

$pdf->Cell(60,5,'Address : Service Mor Rehman Saheed Road, Gujrat',0,1,'C');
$pdf->Cell(60,5,'Phone Number: 347-4567-2314',0,1,'C');
$pdf->Cell(60,5,'E-mail Address : Shamraizbutt@gmail.com',0,1,'C');



//Line(x1,y1,x2,y2);

$pdf->Line(7,38,72,38);


$pdf->Ln(1); // line 


$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,'Bill To :',0,0,'');


$pdf->SetFont('Courier','BI',8);
$pdf->Cell(40,4,$row->customer_name,0,1,'');

$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,'Care of(Grunter) :',0,0,'');


$pdf->SetFont('Courier','BI',8);
$pdf->Cell(45,4,$row->grunter,0,1,'C');


$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,'Invoice no :',0,0,'');


$pdf->SetFont('Courier','BI',8);
$pdf->Cell(40,4,$row->invoice_id,0,1,'');



$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,'Date :',0,0,'');


$pdf->SetFont('Courier','BI',8);
$pdf->Cell(40,4,$row->order_date,0,1,'');



/////////


$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);

$pdf->Cell(34,5,'PRODUCT',1,0,'C');   //70
$pdf->Cell(11,5,'QTY',1,0,'C');
$pdf->Cell(8,5,'PRC',1,0,'C');
$pdf->Cell(12,5,'TOTAL',1,1,'C');


$select=$pdo->prepare("select * from invoice_details where invoice_id=$id");
$select->execute();

while($item=$select->fetch(PDO::FETCH_OBJ)){
  $pdf->SetX(7);
  $pdf->SetFont('Helvetica','B',8);
  $pdf->Cell(34,5,$item->product_name,1,0,'L');   //190 
  $pdf->Cell(11,5,$item->qty,1,0,'C');
  $pdf->Cell(8, 5,$item->price,1,0,'C');
  $pdf->Cell(12,5,$item->price*$item->qty,1,1,'C');  
}




/////////




$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'SUBTOTAL',1,0,'C');
$pdf->Cell(20,5,$row->subtotal,1,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
// $pdf->Cell(25,5,'TAX(5%)',1,0,'C');
// $pdf->Cell(20,5,$row->tax,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'DISCOUNT',1,0,'C');
$pdf->Cell(20,5,$row->discount,1,1,'C');
$pdf->SetX(8);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,8,'',0,0,'L');   //190
// $pdf->Cell(20,8,'',0,0,'C');
$pdf->Cell(25,8,'First Payment',1,0,'C');
$pdf->Cell(20,8,$row->paid,1,1,'C');

$invoice_id = $id;
 $all_paid = 0;
$previous_payment_check=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id");
 $previous_payment_check->bindParam(':invoice_id', $invoice_id);
$previous_payment_check->execute();


if($previous_payment_check->fetchColumn()){
$pdf->SetFont('Arial','B',8);
$pdf->Cell(17,8,'Installments',1,0,'L');   //190
$pdf->Cell(18,8,'Date',1,0,'C');
$pdf->Cell(14,8,'Price',1,0,'C');
$pdf->Cell(14,8,'Due',1,1,'C');   


$i=1;

$previous_payment=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id");
 $previous_payment->bindParam(':invoice_id', $invoice_id);
$previous_payment->execute();
while($row_data=$previous_payment->fetch(PDO::FETCH_OBJ)  ){

 $all_paid = $all_paid + $row_data->payment;  
$pdf->SetFont('Arial','B',8);
// $pdf->SetFillColor(255,255,0);
$pdf->Cell(17,8,$i,1,0,'L');   //190
$pdf->Cell(18,8,$row_data->date,1,0,'C');
$pdf->Cell(14,8,$row_data->payment,1,0,'C');
$pdf->Cell(14,8,$row_data->due,1,1,'C');   
$i++;
}
}
  $all_paid = $all_paid+$row->paid;
  $all_due = $row->total-$all_paid;




$pdf->SetX(7);
$pdf->SetFont('courier','B',10);
$pdf->Cell(20,5,'',0,0,'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'GRAND TOTAL',1,0,'C');
$pdf->Cell(20,5,$row->total,1,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'PAID',1,0,'C');
$pdf->Cell(20,5,$all_paid,1,1,'C');



$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'DUE',1,0,'C');
$pdf->Cell(20,5,$all_due,1,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25,5,'PAYMENT TYPE',1,0,'C');
$pdf->Cell(20,5,$row->payment_type,1,1,'C');

$pdf->Cell(20,5,'',0,1,'');

$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);
$pdf->Cell(25,5,'Important Notice :',0,1,'');

$pdf->SetX(7);
$pdf->SetFont('Arial','',5);
$pdf->Cell(75,5,'No item will be replaced or refunded if you dont have the invoice with you. ',0,2,'');

$pdf->SetX(7);
$pdf->SetFont('Arial','',5);
$pdf->Cell(75,5,'You can refund within 2 days of purchase. ',0,1,'');


$pdf->Output();

?>