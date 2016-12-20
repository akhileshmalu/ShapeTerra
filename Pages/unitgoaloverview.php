<?php


/*
 * This Page controls Faculty Awards Screen.
 */

/*
 * Session & Error control Initialization.
 */
session_start();
$error = array();
$errorflag =0;

/*
 * Connection to DataBase.
 */
require_once ("../Resources/Includes/connect.php");

/*
 * Local & Session variable Initialization
 */

$contentlink_id = $_GET['linkid'];
$bpayname =$_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];

/*
 * faculty Award Grid ; conditional for provost & other users
 */
if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS,LastModified from broadcast where BROADCAST_AY='$bpayname';";
} else{
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS_OTHERS,LastModified from broadcast where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);



/*
 * Add UNIT GOAL Modal
 */

if(isset($_POST['goal_submit'])) {
    $contentlink_id = $_GET['linkid'];
    $goaltitle = $_POST['goaltitle'];

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }
    $goalstatement = mynl2br($_POST['goalstatement']);
    $goalalignment = mynl2br($_POST['goalalignment']);


    $sqlcreatebp .= "INSERT INTO BP_UnitGoals( OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) VALUES ('$ouabbrev','$author','$time','$bpayname','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";

    $sqlcreatebp .= "Update  BpContents set CONTENT_STATUS = 'In progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if($mysqli->multi_query($sqlcreatebp)) {

        $error[0] = "Unit goals added Succesfully.";

    } else {
        $error[0] = "Unit goals could not be added.";
    }


}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlcreatebp .= "Update  BpContents set CONTENT_STATUS = 'Pending approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlcreatebp)) {

        $error[0] = "Unit goals submitted Successfully";

    } else {
        $error[0] = "Unit goals Could not be submitted. Please Retry.";
    }


}



require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<!--<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>-->
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>


<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title"><?php echo $rowbroad[0]; ?></h1>
        <p class="status"><span>Org Unit Name:</span> <?php echo $_SESSION['login_ouname']; ?></p>
        <p class="status"><span>Status:</span> <?php echo $rowbroad[1]; ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Goals Overview & Management</h1>
        <p>Below is a summary of your Unit Goals.</p>
        <div id="" style="margin-top: 10px;">
            <table class="grid" action="taskboard/unitgoalajax.php" title="Unit Goals">
                <tr>
                    <th col="UNIT_GOAL_TITLE" width="300" type="text">Goal Title</th>
                    <th col="MOD_TIMESTAMP" width="200" type="text">Last Edited On</th>
                    <th col="AUTHOR" width="200" type="text">Last Modified By</th>
                    <!--                                        <th col="" type="text">Actions</th>-->
                </tr>
            </table>
        </div>
        <div id="addnew" class="">
            <button id="add-mission" type="button" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-left"
                    onclick ="$('#approve').removeAttr('disabled');"
                    data-toggle="modal"
                    data-target="#addawardModal"><span class="icon">&#xe035;</span> Add New Goal
            </button>
        <form action="<?php echo "unitgoaloverview.php?linkid=".$contentlink_id ?>" method="POST" class="ajaxform">
            <input type="submit" id="approve" name="approve" value="Submit For Approval" class="btn-primary pull-right" >
        </form>
        </div>

    </div>
</div>


<!--Modal for Addition of New Awards-->

<div class="modal fade" id="addawardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Unit Goal</h4>
            </div>

            <div class="modal-body">
                <form method="POST" action="<?php echo "unitgoaloverview.php?linkid=".$contentlink_id ?>">
                    <label for="title"><strong><h4 style="color:blue; ">College/School Goals</h4></strong></label>
                    <div class="form-group" id="">
                        <p>Instruction: enter a goal for the academic Year for your college/School.Each Goal may be
                            linked to one or more University Goals as presented in the University's strategoc plan.The
                            number of goals is not fixed, although 5 +/- one may be a reasonable quantity. <br/><span
                                style="color: red">*</span> indicates required.</p>
                    </div>

                    <strong><h4 style="color:green; ">Add a Goal</h4></strong>
                    <div class="form-group">
                        <label for="goaltitle">Goal Title <span
                                style="color: red"><sup>*</sup></span></label>
                        <input type="text" class="form-control" name="goaltitle" id="goaltitle" required>
                    </div>
                    <div class="form-group">
                        <label for="goallink">Linked to University Goals (select all that apply)</label>

                        <?php
                        $sqlug = "SELECT * FROM UniversityGoals;";
                        $resultug = $mysqli->query($sqlug);
                        while ($rowsug = $resultug->fetch_assoc()): { ?>
                            <div class="checkbox" id="goallink">
                                <label><input type="checkbox" name="goallink[]"
                                              class="checkBoxClass"
                                              value="<?php echo $rowsug['ID_UNIV_GOAL']; ?>"><?php echo $rowsug['GOAL_TITLE']; ?>
                                </label>

                            </div>
                        <?php } endwhile; ?>

                    </div>
                    <div class="form-group">
                        <label for="goalstatement">College/School Goal Statement<span
                                style="color: red"><sup>*</sup></span></label>

                        <textarea rows="5" class="form-control" name="goalstatement" id="goalstatement"
                                  required></textarea>
                    </div>
                    <div class="form-group">

                        <label for="goalalignment">Describe how this Goal Align with your Mission, Vision & Values<span
                                style="color: red"><sup>*</sup></span></label>
                        <textarea rows="5" class="form-control" name="goalalignment" id="goalalignment"
                                  required></textarea>
                    </div>
                    <input type="submit" id="unitgoalbtn" name="goal_submit" value="Save"
                           class="btn-primary btn-sm pull-right">

                </form>
                <div class="modal-footer">
                </div>
            </div>
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
<script src="../Resources/Library/js/tabchange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
