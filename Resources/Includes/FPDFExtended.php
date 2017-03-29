<?php

  error_reporting(1);
  @ini_set('display_errors', 1);
  require "../Library/FPDF/fpdf.php";

  class Table extends FPDF
  {

    var $widths;
    var $aligns;

    public function SetWidths($w)
    {

        //Set the array of column widths
        $this->widths=$w;

    }

    public function SetAligns($a)
    {

        //Set the array of column alignments
        $this->aligns=$a;

    }

    public function Row($data)
    {

        //Calculate the height of the row
        $nb=0;

        for($i=0;$i<count($data);$i++){

          if ($i == 0)
            $this->SetFont('Arial','B',11);
          else
            $this->SetFont('Arial','',11);

          $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

        }

        $h=7*$nb;

        //Issue a page break first if needed
        $this->CheckPageBreak($h);

        //Draw the cells of the row
        for($i=0;$i<count($data);$i++){

          if ($i == 0)
            $this->SetFont('Arial','B',11);
          else
            $this->SetFont('Arial','',11);

          $w=$this->widths[$i];
          $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';

          //Save the current position
          $x=$this->GetX();
          $y=$this->GetY();

          //Draw the border
          $this->Rect($x,$y,$w,$h);

          //Print the text
          $this->MultiCell($w,7,$data[$i],0,$a);

          //Put the position to the right of the cell
          $this->SetXY($x+$w,$y);

        }

        //Go to the next line
        $this->Ln($h);

    }

    public function CheckPageBreak($h){

      //If the height h would cause an overflow, add a new page immediately
      if($this->GetY()+$h>$this->PageBreakTrigger)
          $this->AddPage($this->CurOrientation);

    }

    public function NbLines($w,$txt){

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

        if($c == ' ')

          $sep=$i;
          $l+=$cw[$c];

          if($l > $wmax)
          {

            if($sep == -1)
            {

                if($i == $j)
                  $i++;

            }else
              $i=$sep+1;

            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;

          }else

            $i++;

          }

        return $nl;

      }

  }

  class TOC extends Table
  {

    protected $_toc = array();
    protected $_numbering = false;
    protected $_numberingFooter = false;
    protected $_numPageNum = 1;

    /*function AddPage($orientation='', $format='', $rotation=0)
    {

      $this->AddPage($orientation,$format,$rotation);

      if($this->_numbering)
        $this->_numPageNum++;

    }*/

    function startPageNums()
    {

      $this->_numbering = true;
      $this->_numberingFooter = true;

    }

    function stopPageNums()
    {

      $this->_numbering=false;

    }

    function numPageNo()
    {

      return $this->_numPageNum;

    }

    function TOC_Entry($txt, $level = 0)
    {

      $this->_toc[] = array('t'=>$txt,'l'=>$level,'p'=>$this->numPageNo());

    }

    function insertTOC( $location = 1, $labelSize = 20, $entrySize=10, $tocfont='Arial')
    {


      $this->stopPageNums();
      $this->AddPage();
      $tocstart=$this->page;

      $this->setTextColor(33,87,138);
      $this->setFont('Arial','B',16);
      $this->Write(5,"Blueprint for Academic Excellence");
      $this->Ln(7);
      $this->Write(5,"Outcomes Report - ".$this->selectedYear);
      $this->Ln(15);

      //table of contents
      $this->setTextColor(140,38,51);
      $this->SetFont('Arial','BU',11);
      $this->Cell(10,0,"Table of Contents");
      $this->SetFont('Arial','',11);
      $this->Ln(5);
      $this->setTextColor(0,0,0);

      foreach($this->_toc as $t) {

        //Offset
        $level=$t['l'];

        if($level > 0)
          $this->Cell($level*8);

        $weight='';

        if($level == 0)
            $weight='B';

        $str = $t['t'];
        $this->SetFont($tocfont,$weight,$entrySize);
        $strsize = $this->GetStringWidth($str);
        $this->Cell($strsize+2,$this->FontSize+2,$str);

        //Filling dots
        $this->SetFont($tocfont,'',$entrySize);
        $PageCellSize = $this->GetStringWidth($t['p'])+2;
        $w = $this->w-$this->lMargin-$this->rMargin-$PageCellSize-($level*8)-($strsize+2);
        $nb = $w/$this->GetStringWidth('.');
        $dots = str_repeat('.',$nb);
        $this->Cell($w,$this->FontSize+2,$dots,0,0,'R');

        //Page number
        $this->Cell($PageCellSize,$this->FontSize+2,$t['p'],0,1,'R');

      }

      //Grab it and move to selected location
      $n=$this->page;
      $n_toc = $n - $tocstart + 1;
      $last = array();

      //store toc pages
      for($i = $tocstart;$i <= $n;$i++)
          $last[] = $this->pages[$i];

      //move pages
      for($i=$tocstart-1;$i>=$location-1;$i--)
          $this->pages[$i+$n_toc] = $this->pages[$i];

      //Put toc pages at insert point
      for($i = 0;$i < $n_toc;$i++)
        $this->pages[$location + $i] = $last[$i];

    }

    function Footer()
    {

      if(!$this->_numberingFooter)
        return;

      $this->SetY(-15);
      $this->SetFont('Arial','I',8);
      $this->Cell(0,7,$this->numPageNo(),0,0,'C');

      if(!$this->_numbering)
        $this->_numberingFooter=false;

    }

  }

  class FPDFExtended extends TOC
  {

    private $cconnection, $pdf, $ouAbbrev, $selectedYear, $ouId, $output, $savingDirectory, $initalize;

    function __construct()
    {

      require_once("Initialize.php");
      $this->initalize = new Initialize();
      $this->initalize->checkSessionStatus();

      $this->connection = $this->initalize->connection;
      $this->pdf = new TOC();

      $this->selectedYear = $_SESSION["bpayname"];
      $this->ouId = $_SESSION["login_ouid"];

      if ($this->ouid == 4) {

        $this->ouAbbrev = $_SESSION['bpouabbrev'];

      }else{

        $this->ouAbbrev = $_SESSION['login_ouabbrev'];

      }

      $savingDirectory = "../../User/PDF/".$this->ouAbbrev."/".$this->selectedYear."/";

    }

    public function savePDF($fileName,$output)
    {

      mkdir($this->savingDirectory);

      if (is_dir($this->savingDirectory)){

        $this->pdf->Output($this->savingDirectory.$fileName);

      }

    }

    public function generatePDFLive()
    {

      $this->introPage();
      $this->executiveSummaryPage();
      $this->foundationPage();
      $this->goalsLookingBackPage();
      $this->goalsRealTimePage();
      $this->goalsLookingAheadPage();
      $this->academicPrograms();
      $this->facultyPopulation();
      $this->facultyInformation();
      $this->teaching();
      $this->studentRecruitingRetention();
      $this->studentEnrollmentPage();
      $this->facultyAwardsPage();
      $this->serviceAwardsPage();
      $this->teachingAwardsPage();
      $this->collaborationsPage();
      $this->getUnivLinkedGoal();
      $this->getGoalOutcomes();
      $this->pdf->insertTOC(3);
      $this->pdf->Output("report_final.pdf","I");

    }

    public function introPage()
    {

        $this->pdf->AddPage();

        //header
        $this->pdf->setTextColor(140,38,51);
        $this->pdf->SetFont('Arial','B',48);
        $this->pdf->Cell(80);
        $this->pdf->Cell(25,10,"Outcomes",0,0,'C');
        $this->pdf->Ln(20);
        $this->pdf->Cell(80);
        $this->pdf->Cell(25,10,"Report",0,0,'C');

        //current date
        $this->pdf->Ln(20);
        $this->pdf->setTextColor(33,87,138);
        $this->pdf->SetFont('Arial','',26);
        date_default_timezone_set('US/Eastern');
        $currentDate = date("m-d-Y");
        $this->pdf->Cell(80);
        $this->pdf->Cell(25,10,$currentDate,0,0,'C');

        //getting full unit name
        $getOUName = $this->connection->prepare("SELECT * FROM `Hierarchy` WHERE OU_ABBREV = ?");
        $getOUName->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
        $getOUName->execute();
        $data = $getOUName->fetch();
        $ouName = $data["OU_NAME"];

        //displaying full unit name
        $this->pdf->Ln(40);
        $this->pdf->setTextColor(0,0,0);
        $this->pdf->SetFont('Arial','B',36);
        $this->pdf->SetWidths(array(250));
        $this->pdf->SetDrawColor(255,255,255);
        $stringArray = explode(" ",$ouName);

        for ($i = 0; count($stringArray) > $i; $i++){

          $stringLength += $this->pdf->GetStringWidth($stringArray[$i]);

          if ($stringLength < 150)
            $stringFinal = $stringFinal." ".$stringArray[$i];
          else
            $stringFinal2 = $stringFinal2." ".$stringArray[$i];

        }

        $this->pdf->Cell(80);
        $this->pdf->Cell(25,10,$stringFinal,0,0,'C');
        $this->pdf->Ln(20);
        $this->pdf->Cell(80);
        $this->pdf->Cell(25,10,$stringFinal2,0,0,'C');

        //displaying year that has been selected
        $this->pdf->Ln(20);
        $this->pdf->setTextColor(0,0,0);
        $this->pdf->SetFont('Arial','B',28);
        $this->pdf->Cell(80);
        $this->pdf->Cell(25,10,$this->selectedYear,0,0,'C');

        $this->pdf->Ln(40);
        $this->pdf->SetFont('Arial','B',48);
        $this->pdf->setTextColor(140,38,51);
        $this->pdf->SetDrawColor(255,255,255);
        $this->pdf->Cell(80);
        $this->pdf->Cell(30,10,"Blueprint for",0,0,'C');
        $this->pdf->Ln(25);
        $this->pdf->Cell(80);
        $this->pdf->Cell(30,10,"Academic",0,0,'C');
        $this->pdf->Ln(25);
        $this->pdf->Cell(80);
        $this->pdf->Cell(30,10,"Excellence",0,0,'C');
        $this->pdf->Ln(10);
        $this->pdf->Image('fpdfimages/logo.png',55,250,100);

    }

    public function executiveSummaryPage()
    {

      //page 2
      $this->pdf->AddPage();
      $this->pdf->TOC_Entry("Executive Summary", 0);
      $this->pdf->TOC_Entry("Introduction", 1);
      $this->pdf->TOC_Entry("Highlights", 1);

      //header
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Executive Summary");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(140,38,51);
      $this->pdf->Line(195, 15, 11, 15);

      $getOUName = $this->connection->prepare("SELECT * FROM `Hierarchy` WHERE OU_ABBREV = ?");
      $getOUName->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getOUName->execute();
      $data = $getOUName->fetch();
      $ouName = $data["OU_NAME"];

      //sub header
      $this->pdf->Ln(10);
      $this->pdf->setTextColor(33,87,138);
      $this->pdf->setFont('Arial','B',16);
      $this->pdf->Write(5,"Blueprint for Academic Excellence");
      $this->pdf->Ln(7);
      $this->pdf->Write(5,$ouName);
      $this->pdf->Ln(7);
      $this->pdf->Write(5,$this->selectedYear);

      //introduction
      $getExecutiveInformation = $this->connection->prepare("SELECT * FROM `AC_ExecSum` where OU_ABBREV = ? AND ID_EXECUTIVE_SUMMARY in (select max(ID_EXECUTIVE_SUMMARY) from AC_ExecSum where OUTCOMES_AY = ? group by OU_ABBREV); ");
      $getExecutiveInformation->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getExecutiveInformation->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getExecutiveInformation->execute();
      $data = $getExecutiveInformation->fetch();

      $this->pdf->Ln(10);
      $this->pdf->setFont("Arial","B",16);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->Write(5,"Introduction");
      $this->pdf->Ln(7);
      $this->pdf->setFont("Arial","",12);
      $this->pdf->Write(5,$this->initalize->mybr2nl($data["INTRODUCTION"]));

      $this->pdf->Ln(15);
      $this->pdf->setFont("Arial","B",16);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->Write(5,"Highlights");
      $this->pdf->Ln(7);
      $this->pdf->setFont("Arial","",12);

      $highlightsArray = explode("<br />",$data["HIGHLIGHTS_NARRATIVE"]);

      for ($i = 0; count($highlightsArray) > $i; $i++){

        $this->pdf->Ln(5);
        $this->pdf->Write(8,chr(127)." ".$this->initalize->mybr2nl($highlightsArray[$i]));

      }


      $deanImage = "fpdfimages/".$this->ouAbbrev."_Dean.jpg";
      $unitImage = "fpdfimages/".$this->ouAbbrev."_Logo.jpg";
      $signatureImage = "fpdfimages/".$this->ouAbbrev."_signature.jpg";
      $deanName = "";
      $deanTitle = "";

      $this->pdf->setFont("Arial","",11);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->Ln(105);
      $this->pdf->Write(35,$data["DEAN_NAME_PRINT"].", ".$data["DEAN_TITLE"]);
      $this->pdf->Image($deanImage,130,210,50);
      $this->pdf->Image($unitImage,15,250,85);
      $this->pdf->Image($signatureImage,15,220,65);

    }

    public function foundationPage()
    {

      //page 4
      $this->pdf->AddPage();
      $this->pdf->TOC_Entry("Foundation for Academic Excellence", 0);

      //header
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Foundation for Academic Excellence");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);

      //body
      //
      $getMissionStatement = $this->connection->prepare("SELECT * FROM BP_MissionVisionValues where OU_ABBREV = ? AND ID_UNIT_MVV in (select max(ID_UNIT_MVV) from BP_MissionVisionValues where UNIT_MVV_AY = ? group by OU_ABBREV)");
      $getMissionStatement->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getMissionStatement->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getMissionStatement->execute();
      $data = $getMissionStatement->fetch();

      //mission
      $this->pdf->Ln(10);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Write(5,"Mission Statement");
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Ln(5);
      $this->pdf->Write(5,str_replace("<br />", "",$data["MISSION_STATEMENT"]));
      $this->pdf->Ln(10);
      $this->pdf->setX(130);
      $this->pdf->Write(5,"Updated: " .$data["MISSION_UPDATE_DATE"]);
      $this->pdf->setX(5);

      //Vision
      $this->pdf->Ln(10);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Write(5,"Vision Statement");
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Ln(5);
      $this->pdf->Write(5,str_replace("<br />", "",$data["VISION_STATEMENT"]));
      $this->pdf->Ln(10);
      $this->pdf->setX(130);
      $this->pdf->Write(5,"Updated: ".$data["VISION_UPDATE_DATE"]);
      $this->pdf->setX(5);

      //Values
      $this->pdf->Ln(10);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Write(5,"Values");
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Ln(5);
      $this->pdf->Write(5,str_replace("<br />", "",$data["VALUES_STATEMENT"]));
      $this->pdf->Ln(10);
      $this->pdf->setX(130);
      $this->pdf->Write(5,"Updated: ".$data["VALUES_UPDATE_DATE"]);
      $this->pdf->setX(5);


    }

    public function goalsLookingBackPage()
    {

      $this->pdf->AddPage();
      $this->pdf->TOC_Entry("Goals Looking Back", 0);

      $getOUName = $this->connection->prepare("SELECT * FROM `Hierarchy` WHERE OU_ABBREV = ?");
      $getOUName->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getOUName->execute();
      $data = $getOUName->fetch();
      $ouName = $data["OU_NAME"];

      //Goals
      //header
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Goals - Looking Back");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->setFont('Arial','',11);
      $this->pdf->Ln(10);
      $this->pdf->Cell(80);
      $this->pdf->Cell(20,10,"Goals for the $ouName for the previous Academic Year.",0,0,'C');
      $this->pdf->Ln(5);

      //goal sub section
      $goalType = "Looking Back";
      $getGoals = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
      $getGoals->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getGoals->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getGoals->bindParam(3,$goalType,PDO::PARAM_STR);
      $getGoals->execute();

      $this->pdf->SetWidths(array(50,140,100,60));

      while ($data = $getGoals->fetch()){

        $counter++;

        $universityGoals = $this->getUnivLinkedGoal($data["LINK_UNIV_GOAL"]);
        $goalOutcomes = $this->getGoalOutcomes($data["ID_UNIT_GOAL"]);
        $unitGoalTitle = $data["UNIT_GOAL_TITLE"];

        $this->pdf->Ln(10);
        $this->pdf->setTextColor(115,0,10);
        $this->pdf->SetFont('Arial','B',12);
        $this->pdf->Write(5,"Goal $counter - ".$unitGoalTitle);
        $this->pdf->Ln(5);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->setTextColor(0,0,0);
        $this->pdf->SetFont('Arial','',12);
        $this->pdf->SetMargins(10,10,10);

        $this->pdf->Row(array("Goal Statement",$this->initalize->mybr2nl($data["GOAL_STATEMENT"])));
        $this->pdf->Row(array("Linkage to University Goal",$universityGoals));
        $this->pdf->Row(array("Alignment with Mission, Vision, and Values",$data["GOAL_ALIGNMENT"]));
        $this->pdf->Row(array("Status",$this->initalize->mybr2nl($goalOutcomes["GOAL_REPORT_STATUS"])));
        $this->pdf->Row(array("Achievements",$this->initalize->mybr2nl($goalOutcomes["GOAL_ACHIEVEMENTS"])));
        $this->pdf->Row(array("Resources Utilized",$this->initalize->mybr2nl($goalOutcomes["GOAL_RSRCS_UTLZD"])));
        $this->pdf->Row(array("Continuation",$this->initalize->mybr2nl($goalOutcomes["GOAL_CONTINUATION"])));
        $this->pdf->Row(array("Resources Needed",$this->initalize->mybr2nl($goalOutcomes["GOAL_RSRCS_NEEDED"])));
        $this->pdf->Row(array("Plans for upcoming year (if not completed)",$this->initalize->mybr2nl($goalOutcomes["GOAL_PLAN_INCOMPLT"])));

      }

    }

    public function goalsRealTimePage()
    {

      $counter = 0;
      $this->pdf->AddPage();
      //$this->pdf->TOC_Entry("Goals Real Time", 0);

      $getOUName = $this->connection->prepare("SELECT * FROM `Hierarchy` WHERE OU_ABBREV = ?");
      $getOUName->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getOUName->execute();
      $data = $getOUName->fetch();
      $ouName = $data["OU_NAME"];

      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Goals - Real Time");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->setFont('Arial','',11);
      $this->pdf->Ln(10);
      $this->pdf->Cell(80);
      $this->pdf->Cell(20,10,"Goals for the $ouName that are in progress for $this->selectedYear.",0,0,'C');
      $this->pdf->Ln(5);

      //goal sub section
      $goalType = "Real Time";
      $getGoals = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
      $getGoals->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getGoals->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getGoals->bindParam(3,$goalType,PDO::PARAM_STR);
      $getGoals->execute();

      $this->pdf->SetWidths(array(50,140,100,60));

      while ($data = $getGoals->fetch()){

        $universityGoals = $this->getUnivLinkedGoal($data["LINK_UNIV_GOAL"]);
        $goalOutcomes = $this->getGoalOutcomes($data["ID_UNIT_GOAL"]);
        $unitGoalTitle = $data["UNIT_GOAL_TITLE"];

        $counter++;
        $this->pdf->Ln(10);
        $this->pdf->setTextColor(115,0,10);
        $this->pdf->SetFont('Arial','B',12);
        $this->pdf->Write(5,"Goal $counter - ".$unitGoalTitle);
        $this->pdf->SetFont('Arial','',11);
        $this->pdf->Ln(5);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->setTextColor(0,0,0);

        $this->pdf->Row(array("Goal Statement",$this->initalize->mybr2nl($data["GOAL_STATEMENT"])));
        $this->pdf->Row(array("Linkage to University Goal",$universityGoals));
        $this->pdf->Row(array("Alignment with Mission, Vision, and Values",$this->initalize->mybr2nl($data["GOAL_ALIGNMENT"])));
        $this->pdf->Row(array("Status",$this->initalize->mybr2nl($goalOutcomes["GOAL_REPORT_STATUS"])));
        $this->pdf->Row(array("Achievements",$this->initalize->mybr2nl($goalOutcomes["GOAL_ACHIEVEMENTS"])));
        $this->pdf->Row(array("Resources Utilized",$this->initalize->mybr2nl($goalOutcomes["GOAL_RSRCS_UTLZD"])));
        $this->pdf->Row(array("Continuation",$this->initalize->mybr2nl($goalOutcomes["GOAL_CONTINUATION"])));
        $this->pdf->Row(array("Resources Needed",$this->initalize->mybr2nl($goalOutcomes["GOAL_RSRCS_NEEDED"])));
        $this->pdf->Row(array("Plans for upcoming year (if not completed)",$this->initalize->mybr2nl($goalOutcomes["GOAL_PLAN_INCOMPLT"])));

      }

    }

    public function goalsLookingAheadPage()
    {

      $counter = 0;
      $this->pdf->AddPage();
      //$this->pdf->TOC_Entry("Goals Looking Ahead", 0);

      $getOUName = $this->connection->prepare("SELECT * FROM `Hierarchy` WHERE OU_ABBREV = ?");
      $getOUName->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getOUName->execute();
      $data = $getOUName->fetch();
      $ouName = $data["OU_NAME"];

      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Goals - Looking Ahead");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->setFont('Arial','',11);
      $this->pdf->Ln(10);
      $this->pdf->Cell(80);
      $this->pdf->Cell(20,10,"Goals for the $ouName that are slated for the upcoming year.",0,0,'C');
      $this->pdf->Ln(5);

      //goal sub section
      $goalType = "Looking Ahead";
      $getGoals = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
      $getGoals->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getGoals->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getGoals->bindParam(3,$goalType,PDO::PARAM_STR);
      $getGoals->execute();

      $this->pdf->SetWidths(array(50,140,100,60));

      while ($data = $getGoals->fetch()){

        $universityGoals = $this->getUnivLinkedGoal($data["LINK_UNIV_GOAL"]);
        $goalOutcomes = $this->getGoalOutcomes($data["ID_UNIT_GOAL"]);
        $unitGoalTitle = $data["UNIT_GOAL_TITLE"];

        $counter++;
        $this->pdf->Ln(10);
        $this->pdf->setTextColor(115,0,10);
        $this->pdf->SetFont('Arial','B',12);
        $this->pdf->Write(5,"Goal $counter - ".$unitGoalTitle);
        $this->pdf->SetFont('Arial','',11);
        $this->pdf->Ln(5);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->setTextColor(0,0,0);

        $this->pdf->Row(array("Goal Statement",$this->initalize->mybr2nl($data["GOAL_STATEMENT"])));
        $this->pdf->Row(array("Linkage to University Goal",$universityGoals));
        $this->pdf->Row(array("Alignment with Mission, Vision, and Values",$this->initalize->mybr2nl($data["GOAL_ALIGNMENT"])));
        $this->pdf->Row(array("Status",$this->initalize->mybr2nl($goalOutcomes["GOAL_REPORT_STATUS"])));
        $this->pdf->Row(array("Achievements",$this->initalize->mybr2nl($goalOutcomes["GOAL_ACHIEVEMENTS"])));
        $this->pdf->Row(array("Resources Utilized",$this->initalize->mybr2nl($goalOutcomes["GOAL_RSRCS_UTLZD"])));
        $this->pdf->Row(array("Continuation",$this->initalize->mybr2nl($goalOutcomes["GOAL_CONTINUATION"])));
        $this->pdf->Row(array("Resources Needed",$this->initalize->mybr2nl($goalOutcomes["GOAL_RSRCS_NEEDED"])));
        $this->pdf->Row(array("Plans for upcoming year (if not completed)",$this->initalize->mybr2nl($goalOutcomes["GOAL_PLAN_INCOMPLT"])));

      }

    }

    public function academicPrograms()
    {

      $this->pdf->AddPage();

      $getAcademicPrograms = $this->connection->prepare("SELECT * FROM `AC_Programs` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
      $getAcademicPrograms->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getAcademicPrograms->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getAcademicPrograms->execute();

      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Academic Programs");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->Ln(10);

      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Write(5,"Program Rankings");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Instructional Modalities");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Program Launches");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Program Terminations");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Supplemental Info - Academic Programs");
      $this->pdf->Ln(10);

    }

    public function academicInitiatives()
    {

      $this->pdf->AddPage();

      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Academic Initiatives");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->Ln(10);

      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Write(5,"Experiential Learning For Undergraduates");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Experiential Learning For Graduate & Professional Students");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Affordability");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Reputation Enhancement");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Challenges");
      $this->pdf->Ln(10);

      $this->pdf->Write(5,"Supplemental Info - Initiatives And Observations");
      $this->pdf->Ln(10);

    }

    public function facultyPopulation()
    {

      $this->pdf->AddPage();

      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Faculty Population");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->Ln(10);

    }

    public function facultyInformation()
    {

      $this->pdf->AddPage();

      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Faculty Information");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->Ln(10);

    }

    public function teaching()
    {

      $this->pdf->AddPage();

      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Teaching");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->Ln(10);

    }

    public function studentRecruitingRetention()
    {

      $this->pdf->AddPage();

      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Student Recruiting and Retention");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->Ln(10);

    }

    public function facultyAwardsPage()
    {

      $this->pdf->AddPage();
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Faculty Awards Received");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->Ln(7);
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Write(5,"During $this->selectedYear faculty of $this->ouAbbrev were recognized for their professional accomplishments in the categories of Research, Service, and Teaching.");
      $this->pdf->Ln(10);
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Ln(5);
      $this->pdf->Write(5,"Research Awards");
      $this->pdf->setTextColor(115,0,10);
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Ln(5);
      $this->pdf->SetDrawColor(0, 0, 0);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetWidths(array(50,50,80,60));
      $this->pdf->Row(array("Recipient(s)","Award","Organization"));
      $awardType = "Research";

      $getAwards = $this->connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND AWARD_TYPE = ? ORDER BY ID_SORT ASC");
      $getAwards->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getAwards->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getAwards->bindParam(3,$awardType,PDO::PARAM_STR);
      $getAwards->execute();

      while($data = $getAwards->fetch()){

        $this->pdf->Row(array($data["RECIPIENT_NAME_LAST"].", ".$data["RECIPIENT_NAME_FIRST"],$data["AWARD_TITLE"],$data["AWARDING_ORG"]));

      }

    }

    public function serviceAwardsPage()
    {

      $this->pdf->Ln(5);
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Write(5,"Service Awards");
      $this->pdf->setTextColor(115,0,10);
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Ln(5);
      $this->pdf->SetDrawColor(0, 0, 0);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetWidths(array(50,50,80,60));
      $this->pdf->Row(array("Recipient(s)","Award","Organization"));
      $awardType = "Service";

      $getAwards = $this->connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND AWARD_TYPE = ? ORDER BY ID_SORT ASC");
      $getAwards->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getAwards->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getAwards->bindParam(3,$awardType,PDO::PARAM_STR);
      $getAwards->execute();

      while($data = $getAwards->fetch()){

        $this->pdf->Row(array($data["RECIPIENT_NAME_LAST"].", ".$data["RECIPIENT_NAME_FIRST"],$data["AWARD_TITLE"],$data["AWARDING_ORG"]));

      }

    }

    public function teachingAwardsPage()
    {

      $this->pdf->Ln(5);
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Write(5,"Teaching Awards");
      $this->pdf->setTextColor(115,0,10);
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Ln(5);
      $this->pdf->SetDrawColor(0, 0, 0);
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetWidths(array(50,50,80,60));
      $this->pdf->Row(array("Recipient(s)","Award","Organization"));
      $awardType = "Teaching";

      $getAwards = $this->connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND AWARD_TYPE = ? ORDER BY ID_SORT ASC");
      $getAwards->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getAwards->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getAwards->bindParam(3,$awardType,PDO::PARAM_STR);
      $getAwards->execute();

      while($data = $getAwards->fetch()){

        $this->pdf->Row(array($data["RECIPIENT_NAME_LAST"].", ".$data["RECIPIENT_NAME_FIRST"],$data["AWARD_TITLE"],$data["AWARDING_ORG"]));

      }

    }

    public function collaborationsPage()
    {

      //another one
      $this->pdf->AddPage();

      //header
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Collaborations");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);

      //body
      $getCollaborations = $this->connection->prepare("SELECT * FROM `AC_Collaborations` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
      $getCollaborations->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getCollaborations->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getCollaborations->execute();

      $this->pdf->Ln(10);
      $this->pdf->setTextColor(0,0,0);

      //internal collaborations
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Ln(10);
      $this->pdf->Write(5,"Internal Collaborations");
      $this->pdf->Ln(5);
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Write("Unit’s most significant academic collaborations and multidisciplinary efforts characterized as internal to the University.");

      while($data = $getCollaborations->fetch()){

        $this->pdf->Ln(5);
        $this->pdf->Write(5,chr(127)." ".$this->initalize->mybr2nl($data["COLLAB_INTERNAL"]));

      }

      $getCollaborations = $this->connection->prepare("SELECT * FROM `AC_Collaborations` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
      $getCollaborations->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getCollaborations->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getCollaborations->execute();

      //external collaborations
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Ln(10);
      $this->pdf->Write(5,"External Collaborations");
      $this->pdf->Ln(5);
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Write("Unit's most significant academic collaborations and multidisciplinary efforts characterized as external to the University.");

      while($data = $getCollaborations->fetch()){

        $this->pdf->Ln(5);
        $this->pdf->Write(5,chr(127)." ".$this->initalize->mybr2nl($data["COLLAB_EXTERNAL"]));

      }

      $getCollaborations = $this->connection->prepare("SELECT * FROM `AC_Collaborations` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
      $getCollaborations->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getCollaborations->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getCollaborations->execute();

      //other collaborations
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Ln(10);
      $this->pdf->Write(5,"Other Collaborations");
      $this->pdf->Ln(5);
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Write("Unit's most significant academic collaborations and multidisciplinary efforts that are not otherwise accounted for as Internal or External Collaborations.");

      while($data = $getCollaborations->fetch()){

        $this->pdf->Ln(5);
        $this->pdf->Write(5,chr(127)." ".$this->initalize->mybr2nl($data["COLLAB_OTHER"]));

      }

      //other collaborations
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->Ln(10);
      $this->pdf->Write(5,"Supplemental Info - Collaborations");
      $this->pdf->Ln(5);
      $this->pdf->SetFont('Arial','',11);
      $this->pdf->Write(5,"Additional information provided by the $ouName appears as Appendix ~#");

    }

    public function studentEnrollmentPage()
    {

      //student pop page
      $this->pdf->AddPage();

      //header
      $this->pdf->setTextColor(0,0,0);
      $this->pdf->SetFont('Arial','',22);
      $this->pdf->Cell(10,0,"Student Enrollment & Outcomes");
      $this->pdf->Ln();
      $this->pdf->SetDrawColor(115,0,10);
      $this->pdf->Line(195, 15, 11, 15);
      $this->pdf->Ln(10);
      $this->pdf->setFont('Arial','B',16);
      $this->pdf->Write(5,"Student Enrollments");
      $this->pdf->Ln(7);
      $this->pdf->Write(5,"Student Population by Headcount");
      $this->pdf->Ln(7);
      $this->pdf->setFont('Arial','',11);
      $this->pdf->Write(5, "During $this->selectedYear, $ouName enrolled students as shown below, according to USC’s Office of Institutional Research, Assessment, and Analytic");
      $this->pdf->Ln(5);


      $getEnrollmentData = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` where OU_ABBREV=? AND ID_AC_ENROLLMENTS in (select max(ID_AC_ENROLLMENTS) from `IR_AC_Enrollments` where OUTCOMES_AY = ? group by OU_ABBREV )");
      $getEnrollmentData->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getEnrollmentData->bindParam(2,$this->selectedYear,PDO::PARAM_STR);
      $getEnrollmentData->execute();

      $oldYear = "AY2015-2016";
      $getEnrollmentDataOld1 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OU_ABBREV=? AND ID_AC_ENROLLMENTS IN (SELECT max(ID_AC_ENROLLMENTS) FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? GROUP BY OU_ABBREV )");
      $getEnrollmentDataOld1->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
      $getEnrollmentDataOld1->bindParam(2,$oldYear,PDO::PARAM_STR);
      $getEnrollmentDataOld1->execute();

      $oldYear = "AY2014-2015";
      $getEnrollmentDataOld2 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OU_ABBREV=? AND ID_AC_ENROLLMENTS IN (SELECT max(ID_AC_ENROLLMENTS) FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? GROUP BY OU_ABBREV )");
      $getEnrollmentDataOld2->bindParam(1,$this->ouAbbrev,PDO::PARAM_STR);
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

      //$this->pdf->cMargin = 10;
      $this->pdf->SetWidths(array(40,50,50,50));
      $this->pdf->SetDrawColor(0,0,0);
      $this->pdf->Row(array("","Fall 2016-2017","Fall 2015-2016","Fall 2014-2015"));
      $this->pdf->Row(array("Undergraduate Enrollment","","",""));
      $this->pdf->Row(array("Freshman",$data["ENROLL_HC_FRESH"],$dataOld1["ENROLL_HC_FRESH"],$dataOld2["ENROLL_HC_FRESH"]));
      $this->pdf->Row(array("Sophmore",$data["ENROLL_HC_SOPH"],$dataOld1["ENROLL_HC_SOPH"],$dataOld2["ENROLL_HC_SOPH"]));
      $this->pdf->Row(array("Junior",$data["ENROLL_HC_JUNR"],$dataOld1["ENROLL_HC_JUNR"],$dataOld2["ENROLL_HC_JUNR"]));
      $this->pdf->Row(array("Senior",$data["ENROLL_HC_SENR"],$dataOld1["ENROLL_HC_SENR"],$dataOld2["ENROLL_HC_SENR"]));
      $this->pdf->Row(array("Totals",$total2017Under,$total2016Under,$total2015Under));
      $this->pdf->Row(array("Graduate Enrollment","","",""));
      $this->pdf->Row(array("Masters",$data["ENROLL_HC_MASTERS"],$dataOld1["ENROLL_HC_MASTERS"],$dataOld2["ENROLL_HC_MASTERS"]));
      $this->pdf->Row(array("Doctoral",$data["ENROLL_HC_DOCTORAL"],$dataOld1["ENROLL_HC_DOCTORAL"],$dataOld2["ENROLL_HC_DOCTORAL"]));
      $this->pdf->Row(array("Graduate Certificate",$data["ENROLL_HC_GRAD_CERT"],$dataOld1["ENROLL_HC_GRAD_CERT"],$dataOld2["ENROLL_HC_GRAD_CERT"]));
      $this->pdf->Row(array("Totals",$total2017Grad,$total2016Grad,$total2015Grad));
      $this->pdf->Row(array("Graduate Enrollment","","",""));
      $this->pdf->Row(array("Medicine",$data["ENROLL_HC_MEDICINE"],$dataOld1["ENROLL_HC_MEDICINE"],$dataOld2["ENROLL_HC_MEDICINE"]));
      $this->pdf->Row(array("Law",$data["ENROLL_HC_LAW"],$dataOld1["ENROLL_HC_LAW"],$dataOld2["ENROLL_HC_LAW"]));
      $this->pdf->Row(array("PharmD",$data["ENROLL_HC_PHARMD"],$dataOld1["ENROLL_HC_PHARMD"],$dataOld2["ENROLL_HC_PHARMD"]));
      $this->pdf->Row(array("Totals",$total2017Pro,$total2016Pro,$total2015Pro));
      $this->pdf->Row(array("Total Enrollment (All Levels)",$total2017,$total2016,$total2015));

    }

    public function getUnivLinkedGoal($goals)
    {

      $uniGoalsArray = explode(',',$goals);
      $finalString = "";

      for ($i = 0; count($uniGoalsArray) > $i; $i++){

        $getUniversityGoal = $this->connection->prepare("SELECT * FROM `UniversityGoals` WHERE ID_UNIV_GOAL = ?");
        $getUniversityGoal->bindParam(1,$uniGoalsArray[$i],PDO::PARAM_INT);
        $getUniversityGoal->execute();
        $data = $getUniversityGoal->fetch();

        return $data["GOAL_TITLE"];

      }

    }

    public function getGoalOutcomes($unitID)
    {

      $getGoalOutcomes = $this->connection->prepare("SELECT * FROM `BP_UnitGoalOutcomes` WHERE ID_UNIT_GOAL = ?");
      $getGoalOutcomes->bindParam(1,$unitID,PDO::PARAM_INT);
      $getGoalOutcomes->execute();
      return $getGoalOutcomes->fetch();

    }

    public function getProperDots($title,$number,$indented)
    {

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

  }

  $PDFExtended = new FPDFExtended();
  $PDFExtended->generatePDFLive();


?>
