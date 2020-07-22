<?php 

require('fpdf/fpdf.php');
include_once 'includes/db.php';

$id=$_GET['id'];
$select=$pdo->prepare("select * from `purchase_invoice` where invoice_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$supplier_select=$pdo->prepare("select * from `suppliers` where id=$row->supplier_id");
$supplier_select->execute();
$supplier=$supplier_select->fetch(PDO::FETCH_OBJ);

$pdf = new FPDF('p','mm','A4');


//adding new page
$pdf->AddPage();

//set font to arial, bold, 16pt
$pdf->SetFont('Arial','B',22);

$pdf->Cell(190,10,'MURTAZA MOBILE',1,1,'C');
$pdf->Ln(2);
$pdf->Image('fpdf/logo.png',170,30,25);

$pdf->Ln(10); // line break
//Cell(width , height , text , border , end line , [align] )
$pdf->SetFont('Arial','B',16);
$pdf->Cell(80,10,'CYBARG INC',0,0,'');


// $pdf->Ln(10);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(112,10,'INVOICE',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(80,5,'Address : Service Mor Rehman Saheed Road, Gujrat',0,0,'');


$pdf->SetFont('Arial','',10);
$pdf->Cell(112,5,'Invoice : '.$row->invoice_id,0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(80,5,'Phone Number: 347-4567-2314',0,0,'');


$pdf->SetFont('Arial','',10);
$pdf->Cell(112,5,'Date :'.$row->purchase_date,0,1,'C');


$pdf->SetFont('Arial','',8);
$pdf->Cell(80,5,'E-mail Address : Shamraizbutt@gmail.com',0,1,'');
// $pdf->Cell(80,5,'Website : www.cybarg.com',0,1,'');

//Line(x1,y1,x2,y2);
// $pdf->Line(5,45,205,45);
// $pdf->Line(5,46,205,46);

$pdf->Ln(10); // line break


$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,'Supplier Name :',0,0,'');


$pdf->SetFont('Courier','B',8);
$pdf->Cell(50,5,$row->supplier_name,0,1,'');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,'Supplier Contact no:',0,0,'');
$pdf->SetFont('Courier','B',8);
$pdf->Cell(50,5,$supplier->contact_no,0,1,'');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,5,'Supplier Address :',0,0,'');
$pdf->SetFont('Courier','B',8);
$pdf->Cell(50,5,$supplier->supplier_address,0,1,'');

$pdf->Ln(5); // line break



$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(208,208,208);
$pdf->Cell(100,8,'PRODUCT',1,0,'C',true);   //190
$pdf->Cell(20,8,'QTY',1,0,'C',true);
$pdf->Cell(30,8,'PRICE',1,0,'C',true);
$pdf->Cell(40,8,'TOTAL',1,1,'C',true);



$select=$pdo->prepare("select * from `purchase_invoice_details` where invoice_id=$id");
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

  $all_paid = $row->paid;
  $all_due = $row->total-$all_paid;



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