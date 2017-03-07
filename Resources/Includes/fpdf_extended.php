<?php

  require_once ("../Includes/connect.php");
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
  require_once ("connect.php");

  session_start();
  $ouabbrev= $_SESSION['login_ouabbrev'];
  $selectedYear = $_SESSION["bpayname"];
  $ouid = $_SESSION['login_ouid'];

  if ($ouid == 4) {

    $ouAbbrev = $_SESSION['bpouabbrev'];

  }else{

    $ouAbbrev = $_SESSION['login_ouabbrev'];

  }

  //init
  $pdf = new PDF_MC_Table();
  $pdf->AddPage();

  //header
  $pdf->setTextColor(115,0,10);
  $pdf->SetFont('Arial','B',48);
  $pdf->Cell(80);
  $pdf->Cell(20,10,"Outcomes",0,0,'C');
  $pdf->Ln(20);
  $pdf->Cell(80);
  $pdf->Cell(20,10,"Report",0,0,'C');

  //date
  $pdf->Ln(20);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',26);
  date_default_timezone_set('US/Eastern');
  $currentDate = date("m-d-Y");
  $pdf->Cell(80);
  $pdf->Cell(20,10,$currentDate,0,0,'C');

  //ou_name
  $getOUName = $connection->prepare("SELECT * FROM `Hierarchy` WHERE OU_ABBREV = ?");
  $getOUName->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getOUName->execute();
  $data = $getOUName->fetch();
  $ouName = $data["OU_NAME"];

  $pdf->Ln(50);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',36);
  //$pdf->Cell(80);
  //$pdf->Cell(20,10,$ouName,0,0,'C');

  $pdf->SetWidths(array(250));
  $pdf->SetDrawColor(255,255,255);
  $pdf->setX(15);
  $pdf->Row(array($pdf->Write(15,$ouName)));


  //year
  $pdf->Ln(20);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',28);
  $pdf->Cell(80);
  $pdf->Cell(20,10,$selectedYear,0,0,'C');

  //image
  $pdf->Ln(50);
  $pdf->Image('fpdfimages/logo.png',60,250,100);

  //page 2
  $pdf->AddPage();

  //header
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',22);
  $pdf->Cell(10,0,"Executive Summary");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);

  //sub header
  $pdf->Ln(10);
  $pdf->setTextColor(33,87,138);
  $pdf->setFont('Arial','',16);
  $pdf->Write(5,"Blueprint for Academic Excellence");
  $pdf->Ln(7);
  $pdf->Write(5,$ouName);
  $pdf->Ln(7);
  $pdf->Write(5,$selectedYear);

  //introduction
  $getExecutiveInformation = $connection->prepare("SELECT * FROM `AC_ExecSum` where OU_ABBREV = ? AND ID_EXECUTIVE_SUMMARY in (select max(ID_EXECUTIVE_SUMMARY) from AC_ExecSum where OUTCOMES_AY = ? group by OU_ABBREV); ");
  $getExecutiveInformation->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getExecutiveInformation->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getExecutiveInformation->execute();
  $data = $getExecutiveInformation->fetch();

  $pdf->Ln(10);
  $pdf->setFont("Arial","B",16);
  $pdf->setTextColor(0,0,0);
  $pdf->Write(5,"Introduction");
  $pdf->Ln(7);
  $pdf->setFont("Arial","",12);
  $pdf->Write(5,$initialize->mybr2nl($data["INTRODUCTION"]));

  $pdf->Ln(15);
  $pdf->setFont("Arial","B",16);
  $pdf->setTextColor(0,0,0);
  $pdf->Write(5,"Highlights");
  $pdf->Ln(7);
  $pdf->setFont("Arial","",12);

  $highlightsArray = explode("<br />",$data["HIGHLIGHTS_NARRATIVE"]);

  for ($i = 0; count($highlightsArray) > $i; $i++){

    $pdf->Ln(5);
    $pdf->Write(5,chr(127).$initialize->mybr2nl($highlightsArray[$i]));

  }

  $pdf->Image('fpdfimages/dean.jpg',140,220,50);
  $pdf->Image('fpdfimages/cas.jpg',10,260,85);
  $pdf->Image('fpdfimages/signature.png',10,220,65);

  //page
  $pdf->AddPage();

  //header
  $pdf->Ln(10);
  $pdf->setTextColor(33,87,138);
  $pdf->setFont('Arial','',16);
  $pdf->Write(5,"Blueprint for Academic Excellence");
  $pdf->Ln(7);

$pdf->Write(5,$ouName);
  $pdf->Ln(7);
  $pdf->Write(5,$selectedYear);

  //table of contents
  $pdf->setTextColor(0,0,0);
  $pdf->Ln(10);
  $pdf->SetFont('Arial','BU',12);
  $pdf->Cell(10,0,"Table of Contents");
  $pdf->SetFont('Arial','',12);
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Executive Summary".getProperDots("Executive Summary",2,false)."2");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Introduction".getProperDots("Introduction",2,true)."2");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Highlights".getProperDots("Highlights",3,true)."3");
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Foundation for Academic Excellence".getProperDots("Foundation for academic excellence",2,false)."2");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Mission Statment".getProperDots("mission statement",2,true)."3");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Vision Statement".getProperDots("vission statement",2,true)."3");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Values".getProperDots("Values",2,true)."3");
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Goals - Looking Back".getProperDots("Goals - Looking Back",2,false)."3");
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Goals - Real Time".getProperDots("Goals - Real Time",2,false)."3");
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Goals - Looking Ahead".getProperDots("goals - looking ahead",2,false)."3");
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Faculty Awards Received".getProperDots("Faculty awards received",2,false)."3");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Research Awards".getProperDots("Research awards",2,true)."3");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Service Awards".getProperDots("Service Awards",2,true)."3");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Teaching Awards".getProperDots("Teaching Awards",2,true)."5");
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Collaborations".getProperDots("collaborations",2,false)."9");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Internal Collaborations".getProperDots("internal collaborations",2,true)."9");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"External Collaborations".getProperDots("external collaborations",2,true)."9");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Other Collaborations".getProperDots("other collaborations",2,true)."9");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Supplemental Info - Collaborations".getProperDots("supplemental ifno - collaborations",9,true)."9");
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Student Enrollement & Outcomes".getProperDots("student enrollement & outcomes",10,false)."10");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Student Enrollments".getProperDots("studnet enrollments",10,true)."10");
  $pdf->Ln(5);
  $pdf->setX(15);
  $pdf->Cell(10,0,"Student Population by Headcount".getProperDots("student population by headcount",10,true)."10");
  $pdf->Ln(5);
  $pdf->Cell(10,0,"Appendix <~#> - final_report.pdf");

  //page 4
  $pdf->AddPage();

  //header
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',22);
  $pdf->Cell(10,0,"Foundation for Academic Excellence");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);

  //body
//
  $getMissionStatement = $connection->prepare("SELECT * FROM BP_MissionVisionValues where OU_ABBREV = ? AND ID_UNIT_MVV in (select max(ID_UNIT_MVV) from BP_MissionVisionValues where UNIT_MVV_AY = ? group by OU_ABBREV)");
  $getMissionStatement->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getMissionStatement->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getMissionStatement->execute();
  $data = $getMissionStatement->fetch();

  //mission
  $pdf->Ln(10);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',16);
  $pdf->Write(5,"Mission Statement");
  $pdf->setX(15);
  $pdf->SetFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->Write(5,$initialize->mybr2nl($data["MISSION_STATEMENT"]));
  $pdf->setX(30);
  $pdf->Ln(5);
  $pdf->Write(5,$data["MISSION_DATE_UPDATED"]);

  //Vision
  $pdf->Ln(10);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',16);
  $pdf->Write(5,"Vision Statement");
  $pdf->setX(15);
  $pdf->SetFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->Write(5,$initialize->mybr2nl($data["VISION_STATEMENT"]));
  $pdf->setX(30);
  $pdf->Ln(5);
  $pdf->Write(5,$data["VISION_DATE_UPDATED"]);

  //Values
  $pdf->Ln(10);
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','B',16);
  $pdf->Write(5,"Values");
  $pdf->setX(15);
  $pdf->SetFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->Write(5,$initialize->mybr2nl($data["VALUES_STATEMENT"]));

  $pdf->AddPage();

  //Goals
  //header
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',22);
  $pdf->Cell(10,0,"Goals - Looking Back");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);
  $pdf->setFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->Write("Goals for the $ouName for the previous Academic Year.");

  //goal sub section
  $goalType = "Looking Back";
  $getGoals = $connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
  $getGoals->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getGoals->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getGoals->bindParam(3,$goalType,PDO::PARAM_STR);
  $getGoals->execute();

  $pdf->SetWidths(array(50,140,100,60));

  while ($data = $getGoals->fetch()){

    $counter++;

    $universityGoals = getUnivLinkedGoal($connection,$data["LINK_UNIV_GOAL"]);
    $goalOutcomes = getGoalOutcomes($connection,$data["ID_UNIT_GOAL"]);

    $pdf->Ln(10);
    $pdf->setTextColor(115,0,10);
    $pdf->SetFont('Arial','',12);
    $pdf->Write(5,"Goal $counter");
    $pdf->Ln(5);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->setTextColor(0,0,0);

    $pdf->Row(array("Goal Statement",$initialize->mybr2nl($data["GOAL_STATEMENT"])));
    $pdf->Row(array("Linkage to University Goal",$universityGoals));
    $pdf->Row(array("Alignment with Mission, Vision, and Values",$data["GOAL_ALIGNMENT"]));
    $pdf->Row(array("Status",$initialize->mybr2nl($goalOutcomes["GOAL_REPORT_STATUS"])));
    $pdf->Row(array("Achievements",$initialize->mybr2nl($goalOutcomes["GOAL_ACHIEVEMENTS"])));
    $pdf->Row(array("Resources Utilized",$initialize->mybr2nl($goalOutcomes["GOAL_RSRCS_UTLZD"])));
    $pdf->Row(array("Continuation",$initialize->mybr2nl($goalOutcomes["GOAL_CONTINUATION"])));
    $pdf->Row(array("Resources Needed",$initialize->mybr2nl($goalOutcomes["GOAL_RSRCS_NEEDED"])));
    $pdf->Row(array("Plans for upcoming year (if not completed)",$initialize->mybr2nl($goalOutcomes["GOAL_PLAN_INCOMPLT"])));

  }

  $counter = 0;
  $pdf->AddPage();
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',22);
  $pdf->Cell(10,0,"Goals - Real Time");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);
  $pdf->setFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->Write("Goals for the $ouName that are in progress for $selectedYear.");

  //goal sub section
  $goalType = "Real Time";
  $getGoals = $connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
  $getGoals->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getGoals->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getGoals->bindParam(3,$goalType,PDO::PARAM_STR);
  $getGoals->execute();

  $pdf->SetWidths(array(50,140,100,60));

  while ($data = $getGoals->fetch()){

    $universityGoals = getUnivLinkedGoal($connection,$data["LINK_UNIV_GOAL"]);
    $goalOutcomes = getGoalOutcomes($connection,$data["ID_UNIT_GOAL"]);

    $counter++;
    $pdf->Ln(10);
    $pdf->setTextColor(115,0,10);
    $pdf->SetFont('Arial','',11);
    $pdf->Write(5,"Goal $counter");
    $pdf->Ln(5);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->setTextColor(0,0,0);

    $pdf->Row(array("Goal Statement",$initialize->mybr2nl($data["GOAL_STATEMENT"])));
    $pdf->Row(array("Linkage to University Goal",$universityGoals));
    $pdf->Row(array("Alignment with Mission, Vision, and Values",$initialize->mybr2nl($data["GOAL_ALIGNMENT"])));
    $pdf->Row(array("Status",$initialize->mybr2nl($goalOutcomes["GOAL_REPORT_STATUS"])));
    $pdf->Row(array("Achievements",$initialize->mybr2nl($goalOutcomes["GOAL_ACHIEVEMENTS"])));
    $pdf->Row(array("Resources Utilized",$initialize->mybr2nl($goalOutcomes["GOAL_RSRCS_UTLZD"])));
    $pdf->Row(array("Continuation",$initialize->mybr2nl($goalOutcomes["GOAL_CONTINUATION"])));
    $pdf->Row(array("Resources Needed",$initialize->mybr2nl($goalOutcomes["GOAL_RSRCS_NEEDED"])));
    $pdf->Row(array("Plans for upcoming year (if not completed)",$initialize->mybr2nl($goalOutcomes["GOAL_PLAN_INCOMPLT"])));

  }

  $counter = 0;
  $pdf->AddPage();
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',22);
  $pdf->Cell(10,0,"Goals - Looking Ahead");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);
  $pdf->setFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->Write("Goals for the $ouName that are slated for the upcoming year.");

  //goal sub section
  $goalType = "Looking Ahead";
  $getGoals = $connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
  $getGoals->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getGoals->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getGoals->bindParam(3,$goalType,PDO::PARAM_STR);
  $getGoals->execute();

  $pdf->SetWidths(array(50,140,100,60));

  while ($data = $getGoals->fetch()){

    $universityGoals = getUnivLinkedGoal($connection,$data["LINK_UNIV_GOAL"]);
    $goalOutcomes = getGoalOutcomes($connection,$data["ID_UNIT_GOAL"]);

    $counter++;
    $pdf->Ln(10);
    $pdf->setTextColor(115,0,10);
    $pdf->SetFont('Arial','',11);
    $pdf->Write(5,"Goal $counter");
    $pdf->Ln(5);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->setTextColor(0,0,0);

    $pdf->Row(array("Goal Statement",$initialize->mybr2nl($data["GOAL_STATEMENT"])));
    $pdf->Row(array("Linkage to University Goal",$universityGoals));
    $pdf->Row(array("Alignment with Mission, Vision, and Values",$initialize->mybr2nl($data["GOAL_ALIGNMENT"])));
    $pdf->Row(array("Status",$initialize->mybr2nl($goalOutcomes["GOAL_REPORT_STATUS"])));
    $pdf->Row(array("Achievements",$initialize->mybr2nl($goalOutcomes["GOAL_ACHIEVEMENTS"])));
    $pdf->Row(array("Resources Utilized",$initialize->mybr2nl($goalOutcomes["GOAL_RSRCS_UTLZD"])));
    $pdf->Row(array("Continuation",$initialize->mybr2nl($goalOutcomes["GOAL_CONTINUATION"])));
    $pdf->Row(array("Resources Needed",$initialize->mybr2nl($goalOutcomes["GOAL_RSRCS_NEEDED"])));
    $pdf->Row(array("Plans for upcoming year (if not completed)",$initialize->mybr2nl($goalOutcomes["GOAL_PLAN_INCOMPLT"])));

  }

  $pdf->AddPage();
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',22);
  $pdf->Cell(10,0,"Faculty Awards Received");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);
  $pdf->Ln(7);
  $pdf->SetFont('Arial','',11);
  $pdf->Write(5,"During $selectedYear faculty of $ouAbbrev were recognized for their professional accomplishments in the categories of Research, Service, and Teaching.");
  $pdf->Ln(10);
  $pdf->SetFont('Arial','B',16);
  $pdf->Ln(5);
  $pdf->Write(5,"Research Awards");
  $pdf->setTextColor(115,0,10);
  $pdf->SetFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->SetDrawColor(0, 0, 0);
  $pdf->setTextColor(0,0,0);
  $pdf->SetWidths(array(50,50,80,60));
  $pdf->Row(array("Recipient(s)","Award","Organization"));
  $awardType = "Research";

  $getAwards = $connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND AWARD_TYPE = ? ORDER BY ID_SORT ASC");
  $getAwards->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getAwards->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getAwards->bindParam(3,$awardType,PDO::PARAM_STR);
  $getAwards->execute();

  while($data = $getAwards->fetch()){

    $pdf->Row(array($data["RECIPIENT_NAME_LAST"].", ".$data["RECIPIENT_NAME_FIRST"],$data["AWARD_TITLE"],$data["AWARDING_ORG"]));

  }

  $pdf->Ln(5);
  $pdf->SetFont('Arial','B',16);
  $pdf->Write(5,"Service Awards");
  $pdf->setTextColor(115,0,10);
  $pdf->SetFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->SetDrawColor(0, 0, 0);
  $pdf->setTextColor(0,0,0);
  $pdf->SetWidths(array(50,50,80,60));
  $pdf->Row(array("Recipient(s)","Award","Organization"));
  $awardType = "Service";

  $getAwards = $connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND AWARD_TYPE = ? ORDER BY ID_SORT ASC");
  $getAwards->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getAwards->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getAwards->bindParam(3,$awardType,PDO::PARAM_STR);
  $getAwards->execute();

  while($data = $getAwards->fetch()){

    $pdf->Row(array($data["RECIPIENT_NAME_LAST"].", ".$data["RECIPIENT_NAME_FIRST"],$data["AWARD_TITLE"],$data["AWARDING_ORG"]));

  }

  $pdf->Ln(5);
  $pdf->SetFont('Arial','B',16);
  $pdf->Write(5,"Teaching Awards");
  $pdf->setTextColor(115,0,10);
  $pdf->SetFont('Arial','',11);
  $pdf->Ln(5);
  $pdf->SetDrawColor(0, 0, 0);
  $pdf->setTextColor(0,0,0);
  $pdf->SetWidths(array(50,50,80,60));
  $pdf->Row(array("Recipient(s)","Award","Organization"));
  $awardType = "Teaching";

  $getAwards = $connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND AWARD_TYPE = ? ORDER BY ID_SORT ASC");
  $getAwards->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getAwards->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getAwards->bindParam(3,$awardType,PDO::PARAM_STR);
  $getAwards->execute();

  while($data = $getAwards->fetch()){

    $pdf->Row(array($data["RECIPIENT_NAME_LAST"].", ".$data["RECIPIENT_NAME_FIRST"],$data["AWARD_TITLE"],$data["AWARDING_ORG"]));

  }

  //another one
  $pdf->AddPage();

  //header
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',22);
  $pdf->Cell(10,0,"Collaborations");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);

  //body
  $getCollaborations = $connection->prepare("SELECT * FROM `AC_Collaborations` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
  $getCollaborations->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getCollaborations->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getCollaborations->execute();

  $pdf->Ln(10);
  $pdf->setTextColor(0,0,0);

  //internal collaborations
  $pdf->SetFont('Arial','B',16);
  $pdf->Ln(10);
  $pdf->Write(5,"Internal Collaborations");
  $pdf->Ln(5);
  $pdf->SetFont('Arial','',11);
  $pdf->Write("Unit’s most significant academic collaborations and multidisciplinary efforts characterized as internal to the University.");

  while($data = $getCollaborations->fetch()){

    $pdf->Ln(5);
    $pdf->Write(5,chr(127)." ".$initialize->mybr2nl($data["COLLAB_INTERNAL"]));

  }

  $getCollaborations = $connection->prepare("SELECT * FROM `AC_Collaborations` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
  $getCollaborations->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getCollaborations->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getCollaborations->execute();

  //external collaborations
  $pdf->SetFont('Arial','B',16);
  $pdf->Ln(10);
  $pdf->Write(5,"External Collaborations");
  $pdf->Ln(5);
  $pdf->SetFont('Arial','',11);
  $pdf->Write("Unit's most significant academic collaborations and multidisciplinary efforts characterized as external to the University.");

  while($data = $getCollaborations->fetch()){

    $pdf->Ln(5);
    $pdf->Write(5,chr(127)." ".$initialize->mybr2nl($data["COLLAB_EXTERNAL"]));

  }

  $getCollaborations = $connection->prepare("SELECT * FROM `AC_Collaborations` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
  $getCollaborations->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getCollaborations->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getCollaborations->execute();

  //other collaborations
  $pdf->SetFont('Arial','B',16);
  $pdf->Ln(10);
  $pdf->Write(5,"Other Collaborations");
  $pdf->Ln(5);
  $pdf->SetFont('Arial','',11);
  $pdf->Write("Unit's most significant academic collaborations and multidisciplinary efforts that are not otherwise accounted for as Internal or External Collaborations.");

  while($data = $getCollaborations->fetch()){

    $pdf->Ln(5);
    $pdf->Write(5,chr(127)." ".$initialize->mybr2nl($data["COLLAB_OTHER"]));

  }

  //other collaborations
  $pdf->SetFont('Arial','B',16);
  $pdf->Ln(10);
  $pdf->Write(5,"Supplemental Info - Collaborations");
  $pdf->Ln(5);
  $pdf->SetFont('Arial','',11);
  $pdf->Write(5,"Additional information provided by the $ouName appears as Appendix ~#");

  //student pop page
  $pdf->AddPage();

  //header
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Arial','',22);
  $pdf->Cell(10,0,"Student Enrollment & Outcomes");
  $pdf->Ln();
  $pdf->SetDrawColor(115,0,10);
  $pdf->Line(195, 15, 11, 15);
  $pdf->Ln(10);
  $pdf->setFont('Arial','B',16);
  $pdf->Write(5,"Student Enrollments");
  $pdf->Ln(7);
  $pdf->Write(5,"Student Population by Headcount");
  $pdf->Ln(7);
  $pdf->setFont('Arial','',11);
  $pdf->Write(5, "During $selectedYear, $ouName enrolled students as shown below, according to USC’s Office of Institutional Research, Assessment, and Analytic");
  $pdf->Ln(5);


  $getEnrollmentData = $connection->prepare("SELECT * FROM `IR_AC_Enrollments` where OU_ABBREV=? AND ID_AC_ENROLLMENTS in (select max(ID_AC_ENROLLMENTS) from `IR_AC_Enrollments` where OUTCOMES_AY = ? group by OU_ABBREV )");
  $getEnrollmentData->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getEnrollmentData->bindParam(2,$selectedYear,PDO::PARAM_STR);
  $getEnrollmentData->execute();

  $oldYear = "AY2015-2016";
  $getEnrollmentDataOld1 = $connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OU_ABBREV=? AND ID_AC_ENROLLMENTS IN (SELECT max(ID_AC_ENROLLMENTS) FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? GROUP BY OU_ABBREV )");
  $getEnrollmentDataOld1->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getEnrollmentDataOld1->bindParam(2,$oldYear,PDO::PARAM_STR);
  $getEnrollmentDataOld1->execute();

  $oldYear = "AY2014-2015";
  $getEnrollmentDataOld2 = $connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OU_ABBREV=? AND ID_AC_ENROLLMENTS IN (SELECT max(ID_AC_ENROLLMENTS) FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? GROUP BY OU_ABBREV )");
  $getEnrollmentDataOld2->bindParam(1,$ouAbbrev,PDO::PARAM_STR);
  $getEnrollmentDataOld2->bindParam(2,$oldYear,PDO::PARAM_STR);
  $getEnrollmentDataOld2->execute();

  $data = $getEnrollmentData->fetch();
  $dataOld1 = $getEnrollmentDataOld1->fetch();
  $dataOld2 = $getEnrollmentDataOld2->fetch();

  $total2017Under = $data["ENROLL_HC_FRESH"] + $data["ENROLL_HC_SOPH"] + $data["ENROLL_HC_JUNR"] + $data["ENROLL_HC_SENR"];
  $total2016Under = $dataOld1["ENROLL_HC_FRESH"] + $dataOld1["ENROLL_HC_SOPH"] + $dataOld1["ENROLL_HC_JUNR"] + $dataOld1["ENROLL_HC_SENR"];
  $total2015Under = $dataOld2["ENROLL_HC_FRESH"] + $dataOld2["ENROLL_HC_SOPH"] + $dataOld2["ENROLL_HC_JUNR"] + $dataOld2["ENROLL_HC_SENR"];

  $total2017Grad = $data["ENROLL_HC_MASTERS"] + $data["ENROLL_HC_DOCTORAL"] + $data["ENROLL_HC_GRAD_CERT"];
  $total2016Grad = $dataOld1["ENROLL_HC_MASTERS"] + $dataOld1["ENROLL_HC_DOCTORAL"] + $dataOld1["ENROLL_HC_GRAD_CERT"];
  $total2015Grad = $dataOld2["ENROLL_HC_MASTERS"] + $dataOld2["ENROLL_HC_DOCTORAL"] + $dataOld2["ENROLL_HC_GRAD_CERT"];

  $total2017Pro = $data["ENROLL_HC_MEDICINE"] + $data["ENROLL_HC_LAW"] + $data["ENROLL_HC_PHARMD"];
  $total2016Pro = $dataOld1["ENROLL_HC_MEDICINE"] + $dataOld1["ENROLL_HC_LAW"] + $dataOld1["ENROLL_HC_PHARMD"];
  $total2015Pro = $dataOld2["ENROLL_HC_MEDICINE"] + $dataOld2["ENROLL_HC_LAW"] + $dataOld2["ENROLL_HC_PHARMD"];

  $total2017 = $total2017Under + $total2017Grad + $total2017Pro;
  $total2016 = $total2016Under + $total2016Grad + $total2016Pro;
  $total2015 = $total2015Under + $total2015Grad + $total2015Pro;

  //$pdf->cMargin = 10;
  $pdf->SetWidths(array(40,50,50,50));
  $pdf->SetDrawColor(0,0,0);
  $pdf->Row(array("","Fall 2016-2017","Fall 2015-2016","Fall 2014-2015"));
  $pdf->Row(array("Undergraduate Enrollment","","",""));
  $pdf->Row(array("Freshman",$data["ENROLL_HC_FRESH"],$dataOld1["ENROLL_HC_FRESH"],$dataOld2["ENROLL_HC_FRESH"]));
  $pdf->Row(array("Sophmore",$data["ENROLL_HC_SOPH"],$dataOld1["ENROLL_HC_SOPH"],$dataOld2["ENROLL_HC_SOPH"]));
  $pdf->Row(array("Junior",$data["ENROLL_HC_JUNR"],$dataOld1["ENROLL_HC_JUNR"],$dataOld2["ENROLL_HC_JUNR"]));
  $pdf->Row(array("Senior",$data["ENROLL_HC_SENR"],$dataOld1["ENROLL_HC_SENR"],$dataOld2["ENROLL_HC_SENR"]));
  $pdf->Row(array("Totals",$total2017Under,$total2016Under,$total2015Under));
  $pdf->Row(array("Graduate Enrollment","","",""));
  $pdf->Row(array("Masters",$data["ENROLL_HC_MASTERS"],$dataOld1["ENROLL_HC_MASTERS"],$dataOld2["ENROLL_HC_MASTERS"]));
  $pdf->Row(array("Doctoral",$data["ENROLL_HC_DOCTORAL"],$dataOld1["ENROLL_HC_DOCTORAL"],$dataOld2["ENROLL_HC_DOCTORAL"]));
  $pdf->Row(array("Graduate Certificate",$data["ENROLL_HC_GRAD_CERT"],$dataOld1["ENROLL_HC_GRAD_CERT"],$dataOld2["ENROLL_HC_GRAD_CERT"]));
  $pdf->Row(array("Totals",$total2017Grad,$total2016Grad,$total2015Grad));
  $pdf->Row(array("Graduate Enrollment","","",""));
  $pdf->Row(array("Medicine",$data["ENROLL_HC_MEDICINE"],$dataOld1["ENROLL_HC_MEDICINE"],$dataOld2["ENROLL_HC_MEDICINE"]));
  $pdf->Row(array("Law",$data["ENROLL_HC_LAW"],$dataOld1["ENROLL_HC_LAW"],$dataOld2["ENROLL_HC_LAW"]));
  $pdf->Row(array("PharmD",$data["ENROLL_HC_PHARMD"],$dataOld1["ENROLL_HC_PHARMD"],$dataOld2["ENROLL_HC_PHARMD"]));
  $pdf->Row(array("Totals",$total2017Pro,$total2016Pro,$total2015Pro));
  $pdf->Row(array("Total Enrollment (All Levels)",$total2017,$total2016,$total2015));

  //writing pdf
  $pdf->Output("report_final.pdf","I");

  function getUnivLinkedGoal($connection,$goals){

    $uniGoalsArray = explode(',',$goals);
    $finalString = "";

    for ($i = 0; count($uniGoalsArray) > $i; $i++){

      $getUniversityGoal = $connection->prepare("SELECT * FROM `UniversityGoals` WHERE ID_UNIV_GOAL = ?");
      $getUniversityGoal->bindParam(1,$uniGoalsArray[$i],PDO::PARAM_INT);
      $getUniversityGoal->execute();
      $data = $getUniversityGoal->fetch();

      return $data["GOAL_TITLE"];

    }

  }

  function getGoalOutcomes($connection,$unitID){

    $getGoalOutcomes = $connection->prepare("SELECT * FROM `BP_UnitGoalOutcomes` WHERE ID_UNIT_GOAL = ?");
    $getGoalOutcomes->bindParam(1,$unitID,PDO::PARAM_INT);
    $getGoalOutcomes->execute();
    return $getGoalOutcomes->fetch();

  }

  function getProperDots($title,$number,$indented){

    $dotsPerRow = 125;

    if ($indented)
      $indentedNumber = 15;
    else
      $indentedNumber = 0;

    $total = $dotsPerRow - (strlen($title) + strlen($number)) - $indentedNumber;

    for ($i = 0; $i < $total; $i++){

      $dotString = $dotString.".";

    }

    return $dotString;

  }

?>
