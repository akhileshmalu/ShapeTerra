<?php


/*
 * This Page controls Faculty Awards Screen.
 */

/*
 * Session & Error control Initialization.
 */
session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}
$message = array();
$errorflag =0;
$notBackToDashboard = true;

/*
 * Connection to DataBase.
 */
require_once ("../Resources/Includes/connect.php");

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
</style>





<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Data Dictionary Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Hello <?php echo $_SESSION['login_fname']; ?>! </h1>
        <p class="status"><span>Org Unit Name: </span> <?php echo $_SESSION['login_ouname']; ?></p>
        <p class="status"><span>User role: </span> <?php echo $rowsmenu['USER_RIGHT']; ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
<!--        <div id="addnew" class="">-->
            <a id="add-datadict" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-right"
               href="dataelement.php?id=0&status=new"><span class="icon">&#xe035;</span> Add Element Definition
            </a>
<!--        </div>-->
        <h1 class="box-title">Data Dictionary</h1>
        <p>This dictionary is the authoritative guide to data contained in the Academic Blueprint System.
            You may find a specific data element using the Search box, or sort/filter using the columns. Click item name for detailed information..</p>
        <div id="taskboard" style="margin-top: 10px;" >
            <table class="datadict" action="taskboard/datadictajax.php" title="Data Dictionary">
                <tr>
                    <th col="DATA_ELMNT_FUNC_NAME" width="380" href="<?php echo "../Pages/dataelement.php?elem_id={{columns.ID_DATA_ELEMENT}}&status={{columns.STATUS}}";?>"  type="text">Data Element</th>
                    <!--                        Provost add Element Def Button Control-->
<!--                    --><?php //if ($_SESSION['login_right'] == 7): ?>
                        <th col="STATUS" width="100" type="text">Status</th>
<!--                    --><?php //endif; ?>
                    <th col="RESPONSIBLE_PARTY" width="205" type="text">Responsible Party</th>
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
