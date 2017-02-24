<?php

  session_start();
//  if(!$_SESSION['isLogged']) {
//      header("location:login.php");
//      die();
//  }

require_once("../Resources/Includes/Initialize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
$connection = $initalize->connection;

  require_once("../Resources/Includes/header.php");
  require_once("../Resources/Includes/menu.php");
  require("../Resources/Includes/data.php");

  $Data = new Data($connection);
  $Data->saveExecutiveSummaryDet();
  $executiveSummaryData = $Data->getExecutiveSummary();

?>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
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
        <form action="executiveSummaryDetails.php" method="POST" enctype="multipart/form-data">
            <h3>College/School Name</h3>
            <div id="college-school" class="form-group form-indent">
                <p class="status">
                    <small>Provide the formal name of the College/School exactly as you want it to appear.</small>
                </p>
                <input id="college-school-input" name="college-school-input" type="text" class="form-control" value="<?php echo $executiveSummaryData["UNIT_NAME"]; ?>">
            </div>
            <h3>Dean's Name</h3>
            <div id="deans-name" class="form-group form-indent">
                <p class="status">
                    <small>Provide the formal name of the Dean exactly as you want it to appear.</small>
                </p>
                <input id="deans-name-input" name="deans-name-input" type="text" class="form-control" value="<?php echo $executiveSummaryData["DEAN_NAME_PRINT"]; ?>">
            </div>
            <h3>Deans Title</h3>
            <div id="deans-title" class="form-group form-indent">
                <p class="status">
                    <small>Provide the full, formal title of the Dean exactly as you would like it to appear.</small>
                </p>
                <input id="deans-title-input" name="deans-title-input" type="text" class="form-control" value="<?php echo $executiveSummaryData["DEAN_TITLE"]; ?>">
            </div>
            <h3>Deans Portrait</h3>
            <div id="deans-portrait" class="form-group form-indent">
                <p class="status">
                    <small>Optional. Upload a high resolution portrait photo of the Dean, exactly as you want it to
                        appear. Image should be sized to 250 x 250 pixels in JPG, GIF, or PNG format. Note: if you do
                        not include a portrait, the official Univeristy Seal will be printed in its place.
                        <small>
                </p>
                <input id="deans-portrait-logo" name="deans-portrait-logo" type="file" class="form-control">
            </div>
            <h3>Deans Dean's Signature</h3>
            <div id="deans-signature" class="form-group form-indent">
                <p class="status">
                    <small>Optional. Upload a high resolution image of the Dean's signature, exactly as you want it to
                        appear. Image should be sized to 250 x 75 pixels in JPG, GIF, or PNG format.
                    </small>
                </p>
                <input id="deans-signature-logo" name="deans-signature-logo" type="file" class="form-control">
            </div>
            <h3>Deans College/School Companion Logo</h3>
            <div id="deans-college-school" class="form-group form-indent">
                <p class="status">
                    <small>Upload the official Columbia Campus Colleges and Schools Companion Logo, congruent with
                        instructions provided at http://sc.edu/toolbox/companion_Logos.php.
                    </small>
                </p>
                <input id="deans-college-school-logo" name="deans-college-school-logo" type="file" class="form-control">
            </div>
            <input id="save" type="submit" name="savedraft" value="Save" class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
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
    }
</script>
