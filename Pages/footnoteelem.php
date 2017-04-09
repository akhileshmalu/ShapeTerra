<?php

/*
 * This Page controls Data Element Add Screen.
 */

require_once("../Resources/Includes/Initialize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
$connection = $initalize->connection;

$notBackToDashboard = true;
$error = array();
$errorflag =0;
$BackTofootnoteHome = true;
$elemid = $_GET['elem_id'];
$elemstatus = $_GET['status'];

$ayset = array();
$aystring = null;
$ouset = array();
$oustring = null;

$bptopic =array();
$bptopicstring = null;


/*
 * Local & Session variable Initialization
 */

$ouid = $_SESSION['login_ouid'];
$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];

/*
 * SQL Existing Data Element Value
 */
$sqldataelem = "SELECT * FROM Footnotes WHERE ID_FOOTNOTE = :elemid; ";
$resultdataelem = $connection->prepare($sqldataelem);
$resultdataelem->bindParam(":elemid", $elemid, PDO::PARAM_INT);
$resultdataelem->execute();

$rowsdataelem = $resultdataelem->fetch(4);



/*
 * Blueprint TopicAreas Value
 */
try {
    $sqltopicareas = "SELECT * FROM TopicAreas where TOPIC_FOR_FOOTNOTES = 'Y';";
    $resulttopicareas = $connection->prepare($sqltopicareas);
    $resulttopicareas->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}

/*
 * Academic Years Value
 */
try {
    $sqlay = "SELECT * FROM AcademicYears ORDER BY ACAD_YEAR_DESC DESC ;";
    $resultay = $connection->prepare($sqlay);
    $resultay->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}

/*
 * Impacted Units Value
 */
try {
    $sqlou = "Select * from Hierarchy where OU_DATE_END IS NULL and OU_TYPE='Academic Unit';";
    $resultou = $connection->prepare($sqlou);
    $resultou->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}


if(isset($_POST['save'])) {

    $footnotetopic = $_POST['footnotetitle'];

    $ayset = $_POST['ay'];
    foreach ($ayset as $ay) {
        $aystring .= $ay.',';
    }

    $bptopic = $_POST['bptopic'];
    foreach ($bptopic as $item){
        $bptopicstring .=$item.',';
    }

    $ouset = $_POST['ou'];
    foreach ($ouset as $item){
        $oustring .=$item.',';
    }

    $narrative = $initalize->mynl2br($_POST['narrativevalue']);

    try {

        $sqladdfoot = "INSERT INTO Footnotes (FOOTNOTE_ACAD_YEAR, FOOTNOTE_APPLIC_UNITS, FOOTNOTE_TOPIC, FOOTNOTE_DESC, FOOTNOTE_NARRATIVE,  MOD_BY, MOD_TIMESTAMP)
    VALUES (:aystring,:oustring,:bptopicstring,:footnotetopic,:narrative,:author,:timeStampmod);";

        $sqladdfootresult = $connection->prepare($sqladdfoot);
        $sqladdfootresult->bindParam(":aystring", $aystring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":oustring", $oustring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":bptopicstring", $bptopicstring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":footnotetopic", $footnotetopic, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":narrative", $narrative, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":author", $author, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":timeStampmod", $time, PDO::PARAM_STR);


        if ($sqladdfootresult->execute()) {
            $error[0] = "Your Footnote has been submitted for review.This will be accepted post approval.";
        } else {
            $error[0] = "Footnote could not be submitted.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }

}

if(isset($_POST['directsave'])) {

    $footnotetopic = $_POST['footnotetitle'];

    $ayset = $_POST['ay'];
    foreach ($ayset as $ay) {
        $aystring .= $ay.',';
    }

    $bptopic = $_POST['bptopic'];
    foreach ($bptopic as $item){
        $bptopicstring .=$item.',';
    }

    $ouset = $_POST['ou'];
    foreach ($ouset as $item){
        $oustring .=$item.',';
    }

    $narrative = $initalize->mynl2br($_POST['narrativevalue']);

    try {

        $sqladdfoot = "INSERT INTO Footnotes (FOOTNOTE_ACAD_YEAR, FOOTNOTE_APPLIC_UNITS, FOOTNOTE_TOPIC, FOOTNOTE_DESC, FOOTNOTE_NARRATIVE,FOOTNOTE_STATUS , MOD_BY, MOD_TIMESTAMP)
        VALUES (:aystring,:oustring,:bptopicstring,:footnotetopic,:narrative,'Approved',:author,:timeStampmod);";

        $sqladdfootresult = $connection->prepare($sqladdfoot);
        $sqladdfootresult->bindParam(":aystring", $aystring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":oustring", $oustring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":bptopicstring", $bptopicstring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":footnotetopic", $footnotetopic, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":narrative", $narrative, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":author", $author, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":timeStampmod", $time, PDO::PARAM_STR);

        if ($sqladdfootresult->execute()) {
            $error[0] = "Your Footnote has been added in Footnotes.";
        } else {
            $error[0] = "Footnote could not be submitted.";
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }




}

if(isset($_POST['update'])) {

    $footnotetopic = $_POST['footnotetitle'];

    $ayset = $_POST['ay'];
    foreach ($ayset as $ay) {
        $aystring .= $ay.',';
    }

    $bptopic = $_POST['bptopic'];
    foreach ($bptopic as $item){
        $bptopicstring .=$item.',';
    }

    $ouset = $_POST['ou'];
    foreach ($ouset as $item){
        $oustring .=$item.',';
    }

    $narrative = $initalize->mynl2br($_POST['narrativevalue']);

    try {

        $sqladdfoot = "UPDATE Footnotes SET FOOTNOTE_ACAD_YEAR = :aystring, FOOTNOTE_APPLIC_UNITS = :oustring, FOOTNOTE_TOPIC = :bptopicstring,
        FOOTNOTE_DESC = :footnotetopic, FOOTNOTE_NARRATIVE = :narrative, FOOTNOTE_STATUS = 'Approved', MOD_BY = :author, MOD_TIMESTAMP = :timeStampmod where ID_FOOTNOTE = :elemid;";

        $sqladdfootresult = $connection->prepare($sqladdfoot);
        $sqladdfootresult->bindParam(":aystring", $aystring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":oustring", $oustring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":bptopicstring", $bptopicstring, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":footnotetopic", $footnotetopic, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":narrative", $narrative, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":author", $author, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":timeStampmod", $time, PDO::PARAM_STR);
        $sqladdfootresult->bindParam(":elemid", $elemid, PDO::PARAM_STR);

        if($sqladdfootresult->execute()) {
            $error[0] = "Footnote has been updated.";
        } else {
            $error[0] = "Footnote could not be submitted.";
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }

}


if (isset($_POST['approve'])) {
    try {
        $sqladdfoot = "update Footnotes SET FOOTNOTE_STATUS = 'Approved' where ID_FOOTNOTE = :elemid;";

        $sqladdfootresult = $connection->prepare($sqladdfoot);
        $sqladdfootresult->bindParam(":elemid", $elemid, PDO::PARAM_INT);


        if($sqladdfootresult->execute()) {
            $error[0] = "Footnote has been approved.";
        } else {
            $error[0] = "Footnote could not be approved.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }
}

if(isset($_POST['discard'])) {
    try {
        $sqladdfoot = "update Footnotes SET FOOTNOTE_STATUS = 'Archived' where ID_FOOTNOTE = :elemid;";

        $sqladdfootresult = $connection->prepare($sqladdfoot);
        $sqladdfootresult->bindParam(":elemid", $elemid, PDO::PARAM_INT);

        if($sqladdfootresult->execute()) {
            $error[0] = "This Footnote has been Archived & excluded from Footnotes.";
        } else {
            $error[0] = "This Footnote could not be Archived.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>

<div class="overlay hidden"></div>
<?php if ( isset($_POST['save']) or isset($_POST['directsave']) or isset($_POST['discard']) or isset($_POST['approve']) or isset($_POST['update'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "footnotehome.php";?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Footnote Management</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Add FootNote</h1>
        <div id="" style="margin-top: 30px;">

            <form action="" method="POST">

                <label for="footname" style="font-size: 18px;">FootNote Title <span style="color: red"><sup>*</sup></span></label>
                <div id="footname" class="form-group">
                    <p>
                        <small><em>Recommend 10-15 words for the Footnote, must be unique</em>
                        </small>
                    </p>
                    <input id="ftitle" type="text" name="footnotetitle" <?php if($elemid == 0){ echo "onblur = 'check_availability_ftitle()' "; } ?> class="form-control"
                           style="width: 60%;"  maxlength="255" value="<?php echo $rowsdataelem['FOOTNOTE_DESC']; ?>">
                    <!--                        To display error if name is not unique-->
                    <p id="ftitle_status" ></p>
                </div>



                <label for="ay" style="font-size: 18px;">Academic Year(s) <span
                        style="color: red"><sup>*</sup></span></label>
                <div id="ay" class="form-group">
                    <p>
                        <small><em>Indicate which years were impacted. Between which change occured.Select one or more.</em></small>
                    </p>
                    <div class="col-xs-12">
                        <?php while ($rowsay = $resultay->fetch(4)) {
                            echo "<div class='col-lg-5'><div class='checkbox'><label><input type='checkbox' name='ay[]' value='". $rowsay['ACAD_YEAR_DESC']."'";
                            $topicitem = explode(',',$rowsdataelem['FOOTNOTE_ACAD_YEAR']);
                            foreach ($topicitem as $top) {
                                if ($top == $rowsay['ACAD_YEAR_DESC']) {
                                    echo " checked";
                                }
                            }
                            echo " >" . $rowsay['ACAD_YEAR_DESC'] . "</label></div></div>";
                        }
                        ?>
                    </div>
                </div>

                <label for="bptopic" style="font-size: 18px;">Blueprint Topic(s) <span
                        style="color: red"><sup>*</sup></span></label>
                <div id="bptopic" class="form-group">
                    <p>
                        <small><em>Indicate which topic(s) were impacted. One or more is required.Select all that apply.</em></small>
                    </p>
                    <div class="col-xs-12">
                        <?php while ($rowstopicareas = $resulttopicareas->fetch(4)) {
                            echo "<div class='col-lg-5'><div class='checkbox'><label><input type='checkbox' name='bptopic[]' value='". $rowstopicareas['ID_TOPIC']."'";
                            $topicitem = explode(',',$rowsdataelem['FOOTNOTE_TOPIC']);
                            foreach ($topicitem as $top) {
                                if ($top == $rowstopicareas['ID_TOPIC']) {
                                    echo " checked";
                                }
                            }
                            echo ">" . $rowstopicareas['TOPIC_BRIEF_DESC'] . "</label></div></div>";
                        }
                        ?>
                    </div>
                </div>

                <label for="ou" style="font-size: 18px;">Impacted Unit(s) <span
                        style="color: red"><sup>*</sup></span></label>
                <div id="ou" class="form-group">
                    <p>
                        <small><em>Indicate which topic(s) were impacted. One or more is required.Select all that apply.</em></small>
                    </p>
                    <div class="col-xs-12">
                        <?php while ($rowsou = $resultou->fetch(4)) {
                            echo "<div class='col-lg-5'><div class='checkbox'><label><input type='checkbox' name='ou[]' value='". $rowsou['ID_HIERARCHY']."'";
                            $ouitem = explode(',',$rowsdataelem['FOOTNOTE_APPLIC_UNITS']);
                            foreach ($ouitem as $ou) {
                                if ($ou == $rowsou['ID_HIERARCHY']) {
                                    echo " checked";
                                }
                            }
                            echo ">" . $rowsou['OU_NAME'] . "</label></div></div>";
                        }
                        ?>
                    </div>
                </div>

                <div id="narrative" class="form-group">
                    <label style="font-size: 18px;">Detail/Narrative </label>
                    <p>
                        <small><em>Provide concise description which will be the footnote content included in reports.Recommend 50-75 words.</em></small>
                    </p>
                    <textarea rows="4" name="narrativevalue" cols="25" wrap="hard"
                              class="form-control"><?php echo $rowsdataelem['FOOTNOTE_NARRATIVE']; ?></textarea>
                </div>

                <!--  Provost Approve / Direct Save Element Def Button Control-->
                <?php if ($_SESSION['login_right'] == 7) {
                    if ($elemid == 0) { ?>

                        <button name="directsave" type="submit"
                                class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right"> Save Footnote
                        </button>
                        <button id="cancel" type="button"
                                class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel
                        </button>

                    <?php }
                    if ($elemstatus == 'Proposed') { ?>

                        <button name="approve" type="submit"
                                class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right"> Approve Footnote
                        </button>
                        <button  type="submit" name="discard" onclick="return confirm('Are you certain you want to delete the Footnote?');"
                                 class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left"> Discard Footnote
                        </button>

                    <?php } elseif($elemstatus == 'Approved') { ?>
                        <button name="update" type="submit"
                                class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right"> Update Footnote
                        </button>
                        <button  type="submit" name="discard" onclick="return confirm('Are you certain you want to delete the Footnote?');"
                                 class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left"> Discard Footnote
                        </button>
                    <?php }
                } else{
                    if ($elemid == 0) { ?>
                        <button name="save" type="submit" class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right">
                            Propose Footnote
                        </button>
                        <button id="cancel" type="button"
                                class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel
                        </button>

                    <?php } } ?>


            </form>
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
    });

    //    function confirmaction() {
    //
    //    }

</script>
<script src="../Resources/Library/js/uniqueness.js"></script>
<script src="../Resources/Library/js/cancelbox.js"></script>
<script src="../Resources/Library/js/tabChange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>