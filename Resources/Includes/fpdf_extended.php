<?php

  require "../Library/FPDF/fpdf.php";

  class PDF_MC_Table extends FPDF{

    var $widths;
    var $aligns;

    function SetWidths($w){
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a){
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Row($data){
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++){
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h){
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt){
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
      return $nl;
    }
  }

  error_reporting(1);
  @ini_set('display_errors', 1);

  session_start();
  $ouabbrev= $_SESSION['login_ouabbrev'];
  $selectedYear = $_SESSION["bpayname"];
  $ouid = $_SESSION['login_ouid'];

  if ($ouid == 4) {

    $ouAbbrev = $_SESSION['bpouabbrev'];

  }else{

    $ouAbbrev = $_SESSION['login_ouabbrev'];

  }

  try{

    $connection = new PDO(sprintf('mysql:host=%s;dbname=%s', "localhost", "TESTDB"), "root", "");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  }catch(PDOException $e){

    return $e->getMessage();
    exit();

  }

  //init
  $pdf = new PDF_MC_Table();
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
  $pdf->Ln(50);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',36);
  $pdf->Cell(80);
  $pdf->Cell(20,10,$ouAbbrev,0,0,'C');

  //year
  $pdf->Ln();
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',24);
  $pdf->Cell(80);
  $pdf->Cell(20,10,$selectedYear,0,0,'C');

  //image
  $pdf->Ln(50);
  $pdf->Image('fpdfimages/logo.png',60,250,100);


  //page 2
  $pdf->AddPage();

  //header
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',18);
  $pdf->Cell(10,0,"Blueprint for Academic Excellence");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);

  //sub header
  $pdf->Ln(10);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',12);
  $pdf->Cell(10,0,$ouAbbrev);
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Hossein Haj-Hariri, Dean");

  //table of contents
  $pdf->Ln(10);
  $pdf->SetFont('Arial','BU',12);
  $pdf->Cell(10,0,"Table of Contents");
  $pdf->SetFont('Arial','',12);
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Blueprint for Academic Excellence................................................................................................2");
  $pdf->Ln(5);
  $pdf->Cell(15,0,"Mission...........................................................................................................................................3");
  $pdf->Ln(5);
  $pdf->Cell(15,0,"Vision.............................................................................................................................................3");
  $pdf->Ln(5);
  $pdf->Cell(15,0,"Values............................................................................................................................................3");
  $pdf->Ln(5);
  $pdf->Cell(15,0,"Goals............................................................................................................................................3");

  //page 3
  $pdf->AddPage();

  //header
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',18);
  $pdf->Cell(10,0,"Blueprint for Academic Excellence");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);

  //Mission
  $pdf->setTextColor(0,0,0);
  $pdf->SetDrawColor(0, 0, 255);
  $pdf->SetFont('Arial','B',12);
  $pdf->Ln(15);
  $pdf->Line(195, 20, 11, 20);
  $pdf->Cell(10,0,"Mission");
  $pdf->Line(195, 30, 11, 30);
  $pdf->Ln(7);

  $getMissionStatement = $connection->prepare("SELECT * FROM `BP_MissionVisionValues` WHERE OU_ABBREV = ? AND UNIT_MVV_AY = ?");
  $getMissionStatement->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getMissionStatement->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getMissionStatement->execute();
  $data = $getMissionStatement->fetch();

  $pdf->SetFont('Arial','',11);
  $pdf->Write(5,$data["MISSION_STATEMENT"]);

  //Vision
  $pdf->Ln(10);
  $pdf->setTextColor(0,0,0);
  $pdf->SetDrawColor(0, 0, 255);
  $pdf->SetFont('Arial','B',12);
  $pdf->Ln(15);
  $pdf->Line(195, 62, 11, 62);
  $pdf->Cell(10,0,"Vision");
  $pdf->Line(195, 72, 11, 72);
  $pdf->Ln(7);
  $pdf->SetFont('Arial','',11);
  $pdf->Write(5,$data["VISION_STATEMENT"]);

  //Values
  $pdf->Ln(10);
  $pdf->setTextColor(0,0,0);
  $pdf->SetDrawColor(0, 0, 255);
  $pdf->SetFont('Arial','B',12);
  $pdf->Ln(15);
  $pdf->Line(195, 99, 11, 99);
  $pdf->Cell(10,0,"Values");
  $pdf->Line(195, 109, 11, 109);
  $pdf->Ln(7);
  $pdf->SetFont('Arial','',11);
  $pdf->Write(5,$data["VALUES_STATEMENT"]);

  //Goals
  $pdf->Ln(5);
  $pdf->setTextColor(0,0,0);
  $pdf->SetDrawColor(0, 0, 255);
  $pdf->SetFont('Arial','B',12);
  $pdf->Ln(15);
  $pdf->Line(195, 131, 11, 131);
  $pdf->Cell(10,0,"Goals");
  $pdf->Line(195, 141, 11, 141);
  $pdf->Ln(7);

  //goal sub section
  $getGoals = $connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? ORDER BY ID_SORT ASC");
  $getGoals->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getGoals->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getGoals->execute();

  $pdf->SetWidths(array(50,140,100,60));

  while ($data = $getGoals->fetch()){

    $pdf->Ln(10);
    $pdf->setTextColor(115,0,10);
    $pdf->SetFont('Arial','',11);
    $pdf->Write(5,"Goal $counter - ".$data["UNIT_GOAL_TITLE"]);
    $pdf->Ln(5);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->setTextColor(0,0,0);

    $pdf->Row(array("Linkage to University Goal",getUnivLinkedGoal($data["LINK_UNIV_GOAL"])));
    $pdf->Row(array("Goal",getUnivLinkedGoal($data["GOAL_STATEMENT"])));
    $pdf->Row(array("Alignment with Mission, Vision, and Values",getUnivLinkedGoal($data["GOAL_ALIGNMENT"])));

  }

  //writing pdf
  $pdf->Output("report_final.pdf","I");

  function getUnivLinkedGoal($goal){

    return "test";

  }

?>
