<?php

require_once("../Resources/Includes/Initialize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
$connection = $initalize->connection;

  require_once("../Resources/Includes/header.php");
  require_once("../Resources/Includes/menu.php");

  require("../Resources/Includes/Data.php");
  $Data = new Data;
  $Data->saveExecutiveSummaryIntro();
  $executiveSummaryData = $Data->getExecutiveSummary();

?>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<div class="overlay hidden"></div>
<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
  <div id="title-header">
    <h1 id="title">Blueprint Home</h1>
  </div>
  <div id="main-box" class="col-xs-10 col-xs-offset-1">
    <div class="col-xs-8">
      <h1 class="box-title"></h1>
      <p class="status"><span>Org Unit Name:</span></p>
      <p class="status"><span>Status:</span></p>
    </div>
  </div>
  <div id="main-box" class="col-xs-10 col-xs-offset-1">
    <h1 class="box-title">Executive Summary</h1>
    <form action="executiveSummaryIntro.php" method="POST" enctype="multipart/form-data">
      <h3>Introduction</h3>
      <div id="introduction" class="form-group form-indent">
        <p class="status"><small>Provide a brief narrative introduction of no more than 1,000 characters. This text will form the narrative introduction to the annual Outcomes Report and you may choose to follow it with highlights using the feature provider below. In the Introduction, please use only plain text.</small></p>
        <textarea rows="5" cols="25" id="introduction-input" name="introduction-input" class="form-control"><?php echo $executiveSummaryData["INTRODUCTION"]; ?></textarea>
      </div>
      <h3>Highlights</h3>
      <div id="highlights" class="form-group form-indent">
        <p class="status"><small>Provide a narrative that highlights accomplishments, awards, or other outcomes. You should elaborate on these highlights elsewhere in your outcomes reporting. Content is restricted to 2,500 characters (including spaces).</small></p>
        <textarea rows="5" cols="25" id="highlights-input" name="highlights-input" type="textarea" class="form-control"><?php echo $executiveSummaryData["HIGHLIGHTS_NARRATIVE"]; ?></textarea>
      </div>
      <button id="save" type="submit" name="savedraft" class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">Cancel & Discard</button>
      <!--<input type="submit" id="approve" name="approve" value="Submit for Approval" class="btn-primary pull-right">-->
      <!--<input type="submit" id="reject" name="reject" value="Save" class="btn-primary pull-right">-->
    </form>
  </div>
</div>
<?php require_once("../Resources/Includes/footer.php"); //Include Footer ?>
<!--Calender Bootstrap inclusion for date picker INPUT-->
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    function selectorfile(selected) {
        var b = $(selected).val().substr(12);
        alert(b + " is selected.");
    };
    $('.cancelbox').on("click", function () {
        var choice = confirm("Are you sure you want to cancel");
        if (choice == true) {
            var ayname = <?php echo json_encode($bpayname); ?> , ouabbrev = <?php echo json_encode($ouabbrev); ?>;
            $(window).attr('location', 'bphome.php?ayname=' + ayname + '&ou_abbrev=' + ouabbrev)
        }
    });
</script>
