<?php


/*
 * This Page controls Data Element Add Screen.
 */

/*
 * Session & Error control Initialization.
 */
session_start();

$error = array();
$errorflag =0;
$notBackToDashboard = true;
$elemid = $_GET['elem_id'];
$elemstatus = $_GET['status'];
$BackToDataDictHome = true;
$bptopic =array();

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

/*
 * SQL Existing Data Element Value
 */
$sqldataelem = "SELECT * FROM DataDictionary WHERE ID_DATA_ELEMENT='$elemid'; ";
$resultdataelem = $mysqli -> query($sqldataelem);
$rowsdataelem = $resultdataelem -> fetch_assoc();

if(isset($_POST['save'])) {

    $funcname = $_POST['functionalname'];
    $techname = $_POST['technicalname'];
    $dataclass = $_POST['dataclass'];
    $basicmean = nl2br($_POST['basicmean']);
    $timebasis = $_POST['timebasis'];
    $bptopic = $_POST['bptopic'];
    $usage = nl2br($_POST['usage']);
    $datasource = nl2br($_POST['datasource']);
    $resparty = $_POST['resparty'];
    $contact = $_POST['contactperson'];
    $datatype = $_POST['datatype'];
    $datatrans = nl2br($_POST['datatrans']);
    $valuemand = nl2br($_POST['valuemand']);
    $permitvalue = nl2br($_POST['permitvalue']);
    $constraint = nl2br($_POST['constraint']);
    $notes = nl2br($_POST['notes']);
    $defauthor = $_POST['defauthor'];

    $sqladdelem = "INSERT INTO DataDictionary (DATA_ELMNT_FUNC_NAME, DATA_ELEMENT_TECH_NAME, BASIC_MEANING, TIME_BASIS_OUTCOME, 
INTERP_USAGE, DATA_CLASSIFICATION, DATA_SOURCE, DATA_TYPE, DATA_TRANSFORM, BP_TOPIC, RESPONSIBLE_PARTY, CONTACT_PERSON, 
VALUES_MANDATORY, VALUES_PERMITTED, VALUES_CONSTRAINTS, NOTES_DATA_ELEMENT, AUTHOR, MOD_BY, MOD_TIMESTAMP) VALUES ('$funcname','$techname',
'$basicmean','$timebasis','$usage','$dataclass','$datasource','$datatype','$datatrans','$bptopic','$resparty','$contact',
'$valuemand','$permitvalue','$constraint','$notes','$defauthor','$author','$time');";

    if($mysqli->query($sqladdelem)) {
        $error[0] = "Your Data Element has been submitted for review.This will be accepted in data dictionary post approval.";
    } else {
        $error[0] = "Data Element could not be submitted.";
    }


}

if(isset($_POST['directsave'])) {

    $funcname = $_POST['functionalname'];
    $techname = $_POST['technicalname'];
    $dataclass = $_POST['dataclass'];
    $basicmean = nl2br($_POST['basicmean']);
    $timebasis = $_POST['timebasis'];
    $bptopic = $_POST['bptopic'];
    $usage = nl2br($_POST['usage']);
    $datasource = nl2br($_POST['datasource']);
    $resparty = $_POST['resparty'];
    $contact = $_POST['contactperson'];
    $datatype = $_POST['datatype'];
    $datatrans = nl2br($_POST['datatrans']);
    $valuemand = nl2br($_POST['valuemand']);
    $permitvalue = nl2br($_POST['permitvalue']);
    $constraint = nl2br($_POST['constraint']);
    $notes = nl2br($_POST['notes']);
    $defauthor = $_POST['defauthor'];

    $sqladdelem = "INSERT INTO DataDictionary (DATA_ELMNT_FUNC_NAME, DATA_ELEMENT_TECH_NAME,STATUS, BASIC_MEANING, TIME_BASIS_OUTCOME, 
INTERP_USAGE, DATA_CLASSIFICATION, DATA_SOURCE, DATA_TYPE, DATA_TRANSFORM, BP_TOPIC, RESPONSIBLE_PARTY, CONTACT_PERSON, 
VALUES_MANDATORY, VALUES_PERMITTED, VALUES_CONSTRAINTS, NOTES_DATA_ELEMENT, AUTHOR, MOD_BY, MOD_TIMESTAMP) VALUES ('$funcname','$techname','Approved',
'$basicmean','$timebasis','$usage','$dataclass','$datasource','$datatype','$datatrans','$bptopic','$resparty','$contact',
'$valuemand','$permitvalue','$constraint','$notes','$defauthor','$author','$time');";

    if($mysqli->query($sqladdelem)) {
        $error[0] = "Your Data Element has been added in Data Dictionary.";
    } else {
        $error[0] = "Data Element could not be added.";
    }


}


if(isset($_POST['approve'])) {


    $sqladdelem = "update DataDictionary SET STATUS = 'Approved' where ID_DATA_ELEMENT = '$elemid';";

    if($mysqli->query($sqladdelem)) {
        $error[0] = "Data Element has been approved & included in Data Dictionary.";
    } else {
        $error[0] = "Data Element could not be approved.";
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
<?php if ( isset($_POST['save']) or isset($_POST['directsave']) or isset($_POST['discard']) or isset($_POST['approve'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "datadicthome.php";?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Add Data Element</h1>
    </div>

    <div id="list" class="col-lg-2 col-md-4 col-xs-4">
        <ul class="tabs-nav">
            <li class="tab1 active">1. Identification & Meaning</li>
            <li class="tab2 disabled">2. Source & Values</li>
            <li class="tab3 disabled">3. Change Log</li>
        </ul>
    </div>

    <div id="form" class="col-lg-10 col-md-8 col-xs-8">

        <form action="" method="POST">
            <div class="form-group tab1 active" id="actionlist">
                <h1>Identification & Meaning</h1>
                <div class="col-lg-8 col-sm-10 col-xs-12">

                    <label for="funcname">Functional Name <span style="color: red"><sup>*</sup></span></label>
                    <div id="funcname" class="form-group">
                        <p>
                            <small><em>Assigned, unique name for the data element, as most users should call it</em>
                            </small>
                        </p>
                        <input id="fname" type="text" name="functionalname" maxlength="255" class="form-control"
                               value="<?php echo $rowsdataelem['DATA_ELMNT_FUNC_NAME']; ?>">
                    </div>

                    <label for="techname">Technical Name <span style="color: red"><sup>*</sup></span></label>
                    <div id="techname" class="form-group">
                        <p>
                            <small><em>The technical name for the data element, as established in the database, tables,
                                    or system</em></small>
                        </p>
                        <input type="text" name="technicalname" maxlength="255" class="form-control"
                               value="<?php echo $rowsdataelem['DATA_ELEMENT_TECH_NAME']; ?>">
                    </div>

                    <label for="dataclass">Data Classification <span style="color: red"><sup>*</sup></span></label>
                    <div id="dataclass" class="form-group">
                        <p>
                            <small><em>The formal Classification of the Data Element based on sensitivity of the
                                    intended
                                    or actual contents; this setting is established by the System Administrator and it
                                    is
                                    the obligation of End Users to ensure that the content they submit is not more
                                    sensitive
                                    than permitted by the assigned Classification.</em></small>
                        </p>
                        <select type="text" name="dataclass" class="form-control">
                            <option value="1">Option1</option>
                        </select>
                    </div>

                    <label for="basicmean">Basic Meaning <span style="color: red"><sup>*</sup></span></label>
                    <div id="basicmean" class="form-group">
                        <p>
                            <small><em>The basic definition or meaning of the data element; recommend no more than 2-3
                                    sentences</em></small>
                        </p>
                        <textarea rows="4" name="basicmean" cols="25" wrap="hard"
                                  class="form-control"><?php echo $rowsdataelem['BASIC_MEANING']; ?></textarea>
                    </div>

                    <label for="timebasis">Time Basis for Outcome <span style="color: red"><sup>*</sup></span></label>
                    <div id="timebasis" class="form-group">
                        <p>
                            <small><em>Indicate the time basis for which outcomes will be composed and reported in each
                                    Blueprint.</em></small>
                        </p>
                        <div class="radio">
                            <label><input type="radio" name="timebasis">Option 1</label>
                        </div>
                    </div>

                    <label for="bptopic">Blueprint Topic(s) <span
                            style="color: red"><sup>*</sup></span></label>
                    <div id="bptopic" class="form-group">
                        <p>
                            <small><em>Topic the data element pertains to</em></small>
                        </p>
                        <div class="checkbox">
                            <label><input type="checkbox" name="bptopic[]">Option 1</label>
                        </div>
                    </div>


                    <label for="usage">Interpretation & Usage <span style="color: red"><sup>*</sup></span></label>
                    <div id="usage" class="form-group">
                        <p>
                            <small><em>In as much detail as necessary, describe the parameters under which this data
                                    element can.
                                    may, or should be used, and how its meaning should be interpreted. Do not list
                                    permitted values here;
                                    use the field designated for that purpose (below).</em></small>
                        </p>
                        <textarea rows="4" name="usage" cols="25" wrap="hard"
                                  class="form-control"><?php echo $rowsdataelem['INTERP_USAGE']; ?></textarea>
                    </div>

                    <button id="next-tab" type="button"
                            class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right changeTab1"> Save & Continue
                    </button>
                    <button id="cancel" type="button"
                            class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel & Discard
                    </button>
                </div>
            </div>

            <div class="form-group hidden tab2" id="actionlist">
                <h1>Source & Values</h1>

                <div class="col-lg-8 col-sm-10 col-xs-12">

                    <div id="datasource" class="form-group">
                        <label>Data Source <span
                                style="color: red"><sup>*</sup></span></label>

                        <p>
                            <small><em>Describe the authoritative source of the information in as much detail as
                                    possible (providing office, person/job title, information system, table, data
                                    element).</em></small>
                        </p>
                        <textarea rows="4" name="datasource" cols="25" wrap="hard"
                                  class="form-control"><?php echo $rowsdataelem['DATA_SOURCE']; ?></textarea>
                    </div>

                    <div id="resparty" class="form-group">
                        <label>Responsible Party <span
                                style="color: red"><sup>*</sup></span></label>
                        <p>
                            <small><em>Name of the office or person responsible for producing and/or providing this data
                                    element.</em></small>
                        </p>
                        <select type="text" name="resparty" class="form-control">
                            <option value="1">Option1</option>
                        </select>
                    </div>

                    <div id="contact" class="form-group">
                        <label>Contact Person </label>
                        <p>
                            <small><em>Last Name, First Name of person to contact with questions related to this data
                                    element, plus email address and area code+phone number.</em></small>
                        </p>
                        <input type="text" name="contactperson" maxlength="255" style="height: 60px;"
                               class="form-control" value="<?php echo $rowsdataelem['CONTACT_PERSON']; ?>">
                    </div>

                    <div id="datatype" class="form-group">
                        <label>Data Type <span
                                style="color: red"><sup>*</sup></span></label>
                        <p>
                            <small><em>Describes the type of data for values stored in the data element; this
                                    determination is made by the System Administator and it is the obligation of End
                                    Users to ensure that the content they submit complies with the parameters.</em>
                            </small>
                        </p>
                        <select type="text" name="datatype" class="form-control">
                            <option value="1">Option1</option>
                        </select>
                    </div>

                    <div id="datatrans" class="form-group">
                        <label>Data Transformation </label>
                        <p>
                            <small><em>Describe any transformations, groupings, logic, or mathematical procedures used
                                    in producing the values for this data element</em></small>
                        </p>
                        <textarea rows="4" name="datatrans" cols="25" wrap="hard"
                                  class="form-control"><?php echo $rowsdataelem['DATA_TRANSFORM']; ?></textarea>
                    </div>

                    <div id="valuemand" class="form-group">
                        <label>Values Mandatory <span
                                style="color: red"><sup>*</sup></span></label>
                        <p>
                            <small><em>Describes whether a value must be provided for the data element.</em></small>
                        </p>
                        <select type="text" name="valuemand" class="form-control">
                            <option value="1">Option1</option>
                        </select>
                    </div>

                    <div id="permitvalue" class="form-group">
                        <label>Permitted Values </label>
                        <p>
                            <small><em>Describes the values that are permitted in the data element, for example a
                                    numeric range or number of decimals, or set of choices. If a pick-list item, please
                                    provide all available choices.</em></small>
                        </p>
                        <textarea rows="4" name="permitvalue" cols="25" wrap="hard"
                                  class="form-control"><?php echo $rowsdataelem['VALUES_PERMITTED']; ?></textarea>
                    </div>

                    <div id="constraint" class="form-group">
                        <label>Constraints on Values </label>
                        <p>
                            <small><em>Any constraints on permitted values for this data element, including limitations
                                    or requirements for types of data such as: field length, unit of measure (days vs.
                                    years), permitted languages, or specified sequence for keying in characters such as
                                    MM-DD-YYYY, etc.
                                    If data element is governed by a pick-list, enter those values in 'Permitted Values,
                                    not here.</em></small>
                        </p>
                        <textarea rows="4" name="constraint" cols="25" wrap="hard"
                                  class="form-control"><?php echo $rowsdataelem['VALUES_CONSTRAINTS']; ?></textarea>
                    </div>

                    <div id="notes" class="form-group">
                        <label>Notes / Misc </label>
                        <p>
                            <small><em>Any miscellaneous notes that do not fit elsewhere.</em></small>
                        </p>
                        <textarea rows="4" name="notes" cols="25" wrap="hard"
                                  class="form-control"><?php echo $rowsdataelem['NOTES_DATA_ELEMENT']; ?></textarea>
                    </div>

                    <button id="next-tab" type="button"
                            class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right changeTab2"> Save & Continue
                    </button>
                    <button id="cancel" type="button"
                            class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel & Discard
                    </button>
                </div>
            </div>

            <div class="form-group hidden tab3" id="actionlist">
                <h1>Change Log</h1>

                <div class="col-lg-8 col-sm-10 col-xs-12">

                    <div id="author" class="form-group">
                        <label>Definition Author </label>
                        <p>
                            <small><em>Name of the individual who defined this data element initially.</em></small>
                        </p>
                        <input type="text" name="defauthor" maxlength="255" class="form-control"
                               value="<?php echo $rowsdataelem['AUTHOR']; ?>" required>
                    </div>


                    <!--  Provost Approve / Direct Save Element Def Button Control-->
                    <?php if ($_SESSION['login_right'] == 7) {
                        if ($elemid == 0) { ?>

                            <button name="directsave" type="submit"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right"> Save Element Def
                            </button>
                            <button id="cancel" type="button"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel & Discard
                            </button>

                        <?php }
                        if ($elemstatus == 'Proposed') { ?>

                            <button name="approve" type="submit"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right"> Approve Element Def
                            </button>
                            <button id="cancel" type="submit" name="discard"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left"> Discard Def
                            </button>

                        <?php }
                    } else {
                        if ($elemid == 0) { ?>
                            <button name="save" type="submit" class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right">
                                Propose Element Def
                            </button>
                            <button id="cancel" type="button"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel & Discard
                            </button>

                        <?php }
                    } ?>


                </div>


            </div>
    </div>
    </form>
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


</script>
<script src="../Resources/Library/js/cancelbox.js"></script>
<script src="../Resources/Library/js/tabChange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>

