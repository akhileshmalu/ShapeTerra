<?php

  require "../Library/FPDF/fpdf.php";

  session_start();
  if(!$_SESSION['isLogged']) {
      header("location:login.php");
      die();
  }

  require_once ("connect.php");

  $selectedYear = $_SESSION["bpayname"];
  if ($ouid == 4) {

    $ouAbbrev = $_SESSION['bpouabbrev'];

  }else{

    $ouAbbrev = $_SESSION['login_ouabbrev'];

  }

  //init
  $pdf = new FPDF();
  $pdf->AddPage();

  //header
  $pdf->setTextColor(115,0,10);
  $pdf->SetFont('Arial','B',46);
  $pdf->Cell(80);
  $pdf->Cell(20,10,"Outcomes Report",0,0,'C');

  //date
  $pdf->Ln(50);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',24);
  date_default_timezone_set('US/Eastern');
  $currentDate = date("m-d-Y");
  $pdf->Cell(80);
  $pdf->Cell(20,10,$currentDate,0,0,'C');

  //ou_name
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',36);
  $pdf->Cell(80);
  $pdf->Cell(20,10,$ouAbbrev,0,0,'C');

  //year
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',24);
  $pdf->Cell(80);
  $pdf->Cell(20,10,$selectedYear,0,0,'C');

  //footer
  $pdf->Ln(50);
  $pdf->setTextColor(115,0,10);
  $pdf->SetFont('Arial','B',36);
  $pdf->Cell(80);
  $pdf->Cell(20,10,"Blueprint for \nAcademic Excellence",0,0,'C');

  //writing pdf
  $pdf->Output("report_final.pdf","I");

?>
