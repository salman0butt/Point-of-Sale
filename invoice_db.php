<?php 

require('fpdf/fpdf.php');
include_once 'includes/db.php';

$id=$_GET['id'];
$select=$pdo->prepare("select * from `invoice` where invoice_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);


$pdf = new FPDF('p','mm','A4');


//adding new page
$pdf->AddPage();

//set font to arial, bold, 16pt
$pdf->SetFont('Arial','B',22);

$pdf->Cell(190,10,'MURTAZA MOBILE',1,1,'C');

$pdf->Ln(10); // line break
//Cell(width , height , text , border , end line , [align] )
$pdf->SetFont('Arial','B',16);
$pdf->Cell(80,10,'CYBARG INC',0,0,'');


$pdf->SetFont('Arial','B',13);
$pdf->Cell(112,10,'INVOICE',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(80,5,'Address : Service Mor Rehman Saheed Road, Gujrat',0,0,'');


$pdf->SetFont('Arial','',10);
$pdf->Cell(112,5,'Invoice : '.$row->invoice_id,0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(80,5,'Phone Number: 347-4567-2314',0,0,'');


$pdf->SetFont('Arial','',10);
$pdf->Cell(112,5,'Date :'.$row->order_date,0,1,'C');


$pdf->SetFont('Arial','',8);
$pdf->Cell(80,5,'E-mail Address : Shamraizbutt@gmail.com',0,1,'');
// $pdf->Cell(80,5,'Website : www.cybarg.com',0,1,'');

//Line(x1,y1,x2,y2);
// $pdf->Line(5,45,205,45);
// $pdf->Line(5,46,205,46);

$pdf->Ln(10); // line break


$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,10,'Bill To :',0,0,'');


$pdf->SetFont('Courier','B',14);
$pdf->Cell(50,10,$row->customer_name,0,1,'');


$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,10,'Care Of(Grunter) :',0,0,'');


$pdf->SetFont('Courier','B',14);
$pdf->Cell(40,10,$row->grunter,0,1,'R');


$pdf->Ln(5); // line break



$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(208,208,208);
$pdf->Cell(100,8,'PRODUCT',1,0,'C',true);   //190
$pdf->Cell(20,8,'QTY',1,0,'C',true);
$pdf->Cell(30,8,'PRICE',1,0,'C',true);
$pdf->Cell(40,8,'TOTAL',1,1,'C',true);



$select=$pdo->prepare("select * from `invoice_details` where invoice_id=$id");
$select->execute();

while($item=$select->fetch(PDO::FETCH_OBJ)){
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,$item->product_name.' ('.$item->product_imei.')',1,0,'L');   
$pdf->Cell(20,8,$item->qty,1,0,'C');
$pdf->Cell(30,8,$item->price,1,0,'C');
$pdf->Cell(40,8,$item->price * $item->qty,1,1,'C');

}


$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,'',0,0,'L');   //190
$pdf->Cell(20,8,'',0,0,'C');
$pdf->Cell(30,8,'SubTotal',1,0,'C',true);
$pdf->Cell(40,8,$row->subtotal,1,1,'C');


// $pdf->SetFont('Arial','B',12);
// $pdf->Cell(100,8,'',0,0,'L');   //190
// $pdf->Cell(20,8,'',0,0,'C');
// $pdf->Cell(30,8,'Tax',1,0,'C',true);
// $pdf->Cell(40,8,$row->tax,1,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,'',0,0,'L');   //190
$pdf->Cell(20,8,'',0,0,'C');
$pdf->Cell(30,8,'Discount',1,0,'C',true);
$pdf->Cell(40,8,$row->discount,1,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,'',0,0,'L');   //190
$pdf->Cell(20,8,'',0,0,'C');
$pdf->Cell(30,8,'First Payment',1,0,'C',true);
$pdf->Cell(40,8,$row->paid,1,1,'C');
$invoice_id = $id;
 $all_paid = 0;
$previous_payment_check=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id");
 $previous_payment_check->bindParam(':invoice_id', $invoice_id);
$previous_payment_check->execute();


if($previous_payment_check->fetchColumn()){
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,8,'Installments',1,0,'L',true);   //190
$pdf->Cell(60,8,'Date',1,0,'C',true);
$pdf->Cell(30,8,'Price',1,0,'C',true);
$pdf->Cell(40,8,'Due',1,1,'C',true);   


$i=1;

$previous_payment=$pdo->prepare("SELECT * FROM `payments` WHERE `invoice_id`=:invoice_id");
 $previous_payment->bindParam(':invoice_id', $invoice_id);
$previous_payment->execute();
while($row_data=$previous_payment->fetch(PDO::FETCH_OBJ)  ){

 $all_paid = $all_paid + $row_data->payment;  
$pdf->SetFont('Arial','B',12);
// $pdf->SetFillColor(255,255,0);
$pdf->Cell(60,8,$i,1,0,'L');   //190
$pdf->Cell(60,8,$row_data->date,1,0,'C');
$pdf->Cell(30,8,$row_data->payment,1,0,'C',true);
$pdf->Cell(40,8,$row_data->due,1,1,'C');   
$i++;
}
}
  $all_paid = $all_paid+$row->paid;
  $all_due = $row->total-$all_paid;
// $pdf->Ln(1);


$pdf->SetFont('Arial','B',14);
$pdf->Cell(100,8,'',0,0,'L');   //190
$pdf->Cell(20,8,'',0,0,'C');
$pdf->Cell(30,8,'GrandTotal',1,0,'C',true);
$pdf->Cell(40,8,''.$row->total,1,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,'',0,0,'L');   //190
$pdf->Cell(20,8,'',0,0,'C');
$pdf->Cell(30,8,'Paid',1,0,'C',true);
$pdf->Cell(40,8,$all_paid,1,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,'',0,0,'L');   //190
$pdf->Cell(20,8,'',0,0,'C');
$pdf->Cell(30,8,'Due',1,0,'C',true);
$pdf->Cell(40,8,$all_due,1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100,8,'',0,0,'L');   //190
$pdf->Cell(20,8,'',0,0,'C');
$pdf->Cell(30,8,'Payment Type',1,0,'C',true);
$pdf->Cell(40,8,$row->payment_type,1,1,'C');


$pdf->Cell(50,10,'',0,1,'');


$pdf->SetFont('Arial','B',10);
$pdf->Cell(32,10,'Important Notice :',0,0,'',true);


$pdf->SetFont('Arial','',8);
$pdf->Cell(148,10,'No item will be replaced or refunded if you dont have the invoice with you. You can refund within 2 days of purchase.',0,0,'');


//output the results
$pdf->output();

 ?>