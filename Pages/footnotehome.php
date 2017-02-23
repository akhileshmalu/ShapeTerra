<?php


/*
 * This Page controls Faculty Awards Screen.
 */

/*
 * Session & Error control Initialization.
 */

require_once ("../Resources/Includes/initalize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();

$message = array();
$errorflag =0;
$notBackToDashboard = true;

/*
 * Local & Session variable Initialization
 */

$ouid = $_SESSION['login_ouid'];
$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];

if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>

<style>
    div.gridWrapper .columns .cell.headerCell {
        background: -webkit-linear-gradient(top, #fff 0%, #e4e4e4 100%);
    }
    /*div.gridWrapper .columns .col.dynamic {*/
        /*width: 4% !important;*/
    /*}*/
    /*div.gridWrapper .columns .col {*/
        /*width: 40% !important;*/
    /*}*/

</style>





<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Footnotes Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Hello <?php echo $_SESSION['login_fname']; ?>! </h1>
        <p class="status"><span>Org Unit Name: </span> <?php echo $_SESSION['login_ouname']; ?></p>
        <p class="status"><span>User role: </span> <?php echo $rowsmenu['USER_RIGHT']; ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <!--        <div id="addnew" class="">-->
        <a id="add-datadict" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-right"
           href="footnoteelem.php?elem_id=0&status=new"><span class="icon">&#xe035;</span> Add Footnote
        </a>
        <!--        </div>-->
        <h1 class="box-title">Footnotes</h1>
        <p>Footnotes document substantial changes and issues that occur within or between one or more Academic Years and which impact one or more Colleges/Schools reporting to the Provost at USC Columbia.
            They may be used to memorialize and provide substantive information that may affect the mission, goals, organization, or outcomes for an academic unit.
            Footnotes will be automatically merged and incorporated into reports generated by this system, and are invoked by the Academic Year and Organizational Units impacted.</p>
            <p>You may request a Footnote to be added by clicking Propose Footnote.</p>
        <div id="taskboard" style="margin-top: 10px;" >
            <table class="datadict" action="taskboard/footnoteajax.php" title="Data Dictionary">
                <tr>
                    <th col="FOOTNOTE_DESC" width="400" href="<?php echo "../Pages/footnoteelem.php?elem_id={{columns.ID_FOOTNOTE}}&status={{columns.STATUS}}";?>"  type="text">Title</th>
                    <!--                        Provost add Element Def Button Control-->
                    <?php if ($_SESSION['login_right'] == 7): ?>
                        <th col="FOOTNOTE_STATUS" width="80" type="text">Status</th>
                    <?php endif; ?>
                    <th col="FOOTNOTE_ACAD_YEAR" width="250" type="text">Academic Year(s)</th>
<!--                    <th col="TIMESTAMP" width="125" type="text">Last Edited On</th>-->
<!--                    <th col="LAST_MOD_BY" width="150" type="text">Last Modified By</th>-->
                </tr>
            </table>
        </div>



    </div>
</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
