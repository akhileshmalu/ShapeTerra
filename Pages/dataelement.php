<?php


/*
 * This Page controls Data Element Add Screen.
 */

require_once ("../Resources/Includes/DataDictionary.php");
$DataDictionary = new DATADICTIONARY();
$DataDictionary->checkSessionStatus();

$message = array();
$errorflag = 0;
$notBackToDashboard = true;
$elemid = $_GET['elem_id'];
$elemstatus = $_GET['status'];
$BackToDataDictHome = true;
$bptopic =array();
$bptopicstring = null;

// Set of valid Values

$timebasisoutcome = array (
    'Academic Years only - final: Aug 16- Aug 15',
    'Academic Years -final + Recent Fall -preliminary',
    'Fall Terms only -final',
    'Fiscal Year : July 1-June 30',
    'Calendar Year',
    'Not applicable',
    'Other - explain in Interpretation & Usage Notes'
);

$datatypeset = array(
    'Text - simple (alpha, or alphanumeric)',
    'Text - paragraph',
    'Numeric',
    'Date or Date-Time',
    'Currency',
    'Unknown',
);

$respartyset = array(
    'Dean',
    'Contributor (each College/School)',
    'Human Resources',
    'OIRAA',
    'Provost Office',
    'Reviewer',
    'Team Lead',
    'University Registrar',
    'System Administrator',
    'System Developer',
    'System-generated Value'
);

$valuemandset = array(
    'Required',
    'Conditional',
    'Optional',
    'Unknown'
);



// Local & Session variable Initialization
$ouid = $_SESSION['login_ouid'];
$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];





// SQL Existing Data Element Value
$resultdataelem = $DataDictionary->ElementExValue();
$rowsdataelem = $resultdataelem -> fetch(2);


// Data Classification Value
$resultdataclass = $DataDictionary->GetDataClassification();


// Blueprint TopicAreas Value
$resulttopicareas = $DataDictionary->GetTopicAreas();


if(isset($_POST['save'])) {
    $message[0] = $DataDictionary->SaveWithReview();
}

if(isset($_POST['directsave'])) {
    $message[0] = $DataDictionary->SaveWithReview('Approved');
}

if(isset($_POST['update'])) {

    $message[0] = $DataDictionary->Update();
}


if(isset($_POST['approve'])) {
    $message[0] = $DataDictionary->Approve();
}

if(isset($_POST['discard'])) {

    $message[0] = $DataDictionary->Reject();
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
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
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
            <!--            <li class="tab3 disabled">3. Change Log</li>-->
        </ul>
    </div>

    <div id="main-box" class="col-lg-8 col-xs-offset-1 col-md-8 col-xs-8">
        <form action="<?php echo $_SERVER['PHP_SELF'].'?elem_id='.$elemid.'&status='.$elemstatus;?>" method="POST">
            <div class="form-group tab1 active" id="actionlist">
                <h1>Identification & Meaning</h1>
                <div class="col-xs-12">

                    <h3>Functional Name <span style="color: red"><sup>*</sup></span></h3>
                    <div id="funcname" class="form-group form-indent">
                        <p class="status">
                            <small>Assigned, unique name for the data element, as most users should call it
                            </small>
                        </p>
                        <input id="fname" type="text" name="functionalname" <?php if ($elemid == 0) {
                            echo "onblur = 'check_availability_func()' ";
                        } ?> maxlength="255" class="form-control"
                               value="<?php echo $rowsdataelem['DATA_ELMNT_FUNC_NAME']; ?>">
                        <!--                        To display error if name is not unique-->
                        <p id="funcname_status"></p>
                    </div>

                    <h3>Technical Name <span style="color: red"><sup>*</sup></span></h3>
                    <div id="techname" class="form-group form-indent">
                        <p class="status">
                            <small>The technical name for the data element, as established in the database, tables,
                                or system
                            </small>
                        </p>
                        <input id="tecname" type="text" <?php if ($elemid == 0) {
                            echo "onblur = 'check_availability_tech()' ";
                        } ?> name="technicalname" maxlength="255" class="form-control"
                               value="<?php echo $rowsdataelem['DATA_ELEMENT_TECH_NAME']; ?>">
                        <!--                        To display error if name is not unique-->
                        <p id="tecname_status"></p>
                    </div>

                    <h3>Label in System</h3>
                    <div id="syslabel" class="form-group form-indent">
                        <p class="status">
                            <small>The label that should appear alongside the data element in the system (on screen).
                            </small>
                        </p>
                        <input type="text" name="syslabel" maxlength="125" class="form-control"
                               value="<?php echo $rowsdataelem['LABEL_SYSTEM']; ?>">
                    </div>

                    <h3>Label in Print</h3>
                    <div id="printlabel" class="form-group form-indent">
                        <p class="status">
                            <small>The label that should appear alongside the data element in printed reports and other
                                outputs.
                            </small>
                        </p>
                        <input type="text" name="printlabel" maxlength="125" class="form-control"
                               value="<?php echo $rowsdataelem['LABEL_PRINT']; ?>">
                    </div>

                    <h3>Data Classification <span style="color: red"><sup>*</sup></span></h3>
                    <div id="dataclass" class="form-group form-indent">
                        <p class="status">
                            <small>The formal Classification of the Data Element based on sensitivity of the
                                intended
                                or actual contents; this setting is established by the System Administrator and it
                                is
                                the obligation of End Users to ensure that the content they submit is not more
                                sensitive
                                than permitted by the assigned Classification.
                            </small>
                        </p>
                        <select  name="dataclass" class="form-control">
                            <option value=""></option>
                            <?php while ($rowsdataclass = $resultdataclass->fetch(2)) {
                                echo "<option value=" . $rowsdataclass['ID_DATA_CLASS'];
                                if ($rowsdataelem['DATA_CLASSIFICATION'] == $rowsdataclass['ID_DATA_CLASS']) {
                                    echo " selected = selected";
                                }
                                echo ">" . $rowsdataclass['CLASSIFICATION'] . "</option>";
                            } ?>
                        </select>
                    </div>

                    <h3>Basic Meaning <span style="color: red"><sup>*</sup></span></h3>
                    <div id="basicmean" class="form-group form-indent">
                        <p class="status">
                            <small>The basic definition or meaning of the data element; recommend no more than 2-3
                                sentences
                            </small>
                        </p>
                        <textarea rows="4" name="basicmean" cols="25" wrap="hard"
                                  class="form-control"><?php echo $DataDictionary->mybr2nl($rowsdataelem['BASIC_MEANING']); ?></textarea>
                    </div>

                    <h3>User Instructions</h3>
                    <div id="userinstr" class="form-group form-indent">
                        <p class="status">
                            <small>The instructions users should follow when responding to or completing the data
                                element or item.
                            </small>
                        </p>
                        <textarea rows="3" name="userinstr" cols="25" wrap="hard"
                                  class="form-control"><?php echo $DataDictionary->mybr2nl($rowsdataelem['USER_INSTRCTN']); ?></textarea>
                    </div>

                    <h3>Time Basis for Outcome <span style="color: red"><sup>*</sup></span></h3>
                    <div id="timebasis" class="form-group form-indent">
                        <p class="status">
                            <small>Indicate the time basis for which outcomes will be composed and reported in each
                                Blueprint.
                            </small>
                        </p>
                        <?php foreach ($timebasisoutcome as $key) {
                            echo "<div class='radio'><label><input type='radio' name='timebasis' value='" . $key . "'";
                            if ($rowsdataelem['TIME_BASIS_OUTCOME'] == $key) {
                                echo " checked";
                            }
                            echo ">" . $key . "</label></div>";
                        }
                        ?>

                    </div>

                    <h3>Blueprint Topic(s) <span
                            style="color: red"><sup>*</sup></span></h3>
                    <div id="bptopic" class="form-group form-indent">
                        <p class="status">
                            <small>Topic the data element pertains to</small>
                        </p>

                        <?php

                        $i = 0;
                        $columns = 3;
                        while ($rowstopicareas = $resulttopicareas->fetch(2)) {
                            if ($i % $columns == 0) {
                                echo "<div class='col-xs-6'><label>";
                            }
                            echo "<div><input type='checkbox' name='bptopic[]' value='" . $rowstopicareas['ID_TOPIC'] .
                                "'";
                            $topicitem = explode(',', $rowsdataelem['BP_TOPIC']);
                            foreach ($topicitem as $top) {
                                if ($top == $rowstopicareas['ID_TOPIC']) {
                                    echo " checked";
                                }
                            }
                            echo ">" . $rowstopicareas['TOPIC_BRIEF_DESC']."</div>";
                            $i++;
                            if ($i % $columns == 0) {
                                echo "</label></div>";
                            }
                        }
                        ?>

                    </div>


                    <h3>Interpretation & Usage</h3>
                    <div id="usage" class="form-group form-indent">
                        <p class="status">
                            <small>In as much detail as necessary, describe the parameters under which this data
                                element can.
                                may, or should be used, and how its meaning should be interpreted. Do not list
                                permitted values here;
                                use the field designated for that purpose (below).
                            </small>
                        </p>
                        <textarea rows="4" name="usage" cols="25" wrap="hard"
                                  class="form-control"><?php echo $DataDictionary->mybr2nl($rowsdataelem['INTERP_USAGE']); ?></textarea>
                    </div>

                    <button id="next-tab" type="button"
                            class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right changeTab1">Continue
                    </button>
                    <button id="cancel" type="button"
                            class="btn-secondary col-lg-3 col-md-5 col-sm-6 pull-left canceldatadictbox">Cancel
                    </button>
                    <button type="submit" name="discard"
                            onclick="return confirm('Are you certain you want to delete the definition?');"
                            class="btn-primary col-lg-4 col-md-4 col-sm-4 pull-left"> Discard Def
                    </button>
                </div>
            </div>

            <div class="form-group hidden tab2" id="actionlist">
                <h1>Source & Values</h1>

                <div class="col-lg-8 col-sm-10 col-xs-12">
                    <h3>Data Source <span style="color: red"><sup>*</sup></span></h3>
                    <div id="datasource" class="form-group form-indent">
                        <p class="status">
                            <small>Describe the authoritative source of the information in as much detail as
                                possible (providing office, person/job title, information system, table, data
                                element).
                            </small>
                        </p>
                        <textarea rows="4" name="datasource" cols="25" wrap="hard"
                                  class="form-control"
                                  required><?php echo $DataDictionary->mybr2nl($rowsdataelem['DATA_SOURCE']); ?></textarea>
                    </div>

                    <h3>Responsible Party <span
                            style="color: red"><sup>*</sup></span></h3>

                    <div id="resparty" class="form-group form-indent">
                        <p class="status">
                            <small>Name of the office or person responsible for producing and/or providing this data
                                element.
                            </small>
                        </p>
                        <select type="text" name="resparty" class="form-control" required>
                            <option value=""></option>
                            <?php foreach ($respartyset as $party) { ?>
                                <option
                                    value="<?php echo $party ?>" <?php if ($rowsdataelem['RESPONSIBLE_PARTY'] == $party) {
                                    echo " selected = selected";
                                } ?>><?php echo $party ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <h3>Contact Person </h3>

                    <div id="contact" class="form-group form-indent">

                        <p class="status">
                            <small>Last Name, First Name of person to contact with questions related to this data
                                element, plus email address and area code+phone number.
                            </small>
                        </p>
                        <input type="text" name="contactperson" maxlength="255" style="height: 60px;"
                               class="form-control" value="<?php echo $rowsdataelem['CONTACT_PERSON']; ?>">
                    </div>

                    <h3>Data Type <span
                            style="color: red"><sup>*</sup></span></h3>

                    <div id="datatype" class="form-group form-indent">

                        <p class="status">
                            <small>Describes the type of data for values stored in the data element; this
                                determination is made by the System Administator and it is the obligation of End
                                Users to ensure that the content they submit complies with the parameters.
                            </small>
                        </p>
                        <select type="text" name="datatype" class="form-control" required>
                            <option value=""></option>
                            <?php foreach ($datatypeset as $data) { ?>
                                <option value="<?php echo $data ?>" <?php if ($rowsdataelem['DATA_TYPE'] == $data) {
                                    echo " selected = selected";
                                } ?>><?php echo $data ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <h3>Data Transformation </h3>

                    <div id="datatrans" class="form-group form-indent">
                        <p class="status">
                            <small>Describe any transformations, groupings, logic, or mathematical procedures used
                                in producing the values for this data element
                            </small>
                        </p>
                        <textarea rows="4" name="datatrans" cols="25" wrap="hard"
                                  class="form-control"><?php echo $DataDictionary->mybr2nl($rowsdataelem['DATA_TRANSFORM']); ?></textarea>
                    </div>

                    <h3>Values Mandatory <span
                            style="color: red"><sup>*</sup></span></h3>

                    <div id="valuemand" class="form-group form-indent">

                        <p class="status">
                            <small>Describes whether a value must be provided for the data element.</small>
                        </p>
                        <select type="text" name="valuemand" class="form-control" required>
                            <option value=""></option>
                            <?php foreach ($valuemandset as $valmand) { ?>
                                <option
                                    value="<?php echo $valmand ?>" <?php if ($rowsdataelem['VALUES_MANDATORY'] == $valmand) {
                                    echo " selected = selected";
                                } ?>><?php echo $valmand ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <h3>Permitted Values </h3>

                    <div id="permitvalue" class="form-group form-indent">

                        <p class="status">
                            <small>Describes the values that are permitted in the data element, for example a
                                numeric range or number of decimals, or set of choices. If a pick-list item, please
                                provide all available choices.
                            </small>
                        </p>
                        <textarea rows="4" name="permitvalue" cols="25" wrap="hard"
                                  class="form-control"><?php echo $DataDictionary->mybr2nl($rowsdataelem['VALUES_PERMITTED']); ?></textarea>
                    </div>

                    <h3>Constraints on Values </h3>

                    <div id="constraint" class="form-group form-indent">

                        <p class="status">
                            <small>Any constraints on permitted values for this data element, including limitations
                                or requirements for types of data such as: field length, unit of measure (days vs.
                                years), permitted languages, or specified sequence for keying in characters such as
                                MM-DD-YYYY, etc.
                                If data element is governed by a pick-list, enter those values in 'Permitted Values,
                                not here.
                            </small>
                        </p>
                        <textarea rows="4" name="constraint" cols="25" wrap="hard"
                                  class="form-control"><?php echo $DataDictionary->mybr2nl($rowsdataelem['VALUES_CONSTRAINTS']); ?></textarea>
                    </div>

                    <h3>Notes / Misc </h3>

                    <div id="notes" class="form-group form-indent">

                        <p class="status">
                            <small>Any miscellaneous notes that do not fit elsewhere.</small>
                        </p>
                        <textarea rows="4" name="notes" cols="25" wrap="hard"
                                  class="form-control"><?php echo $DataDictionary->mybr2nl($rowsdataelem['NOTES_DATA_ELEMENT']); ?></textarea>
                    </div>

                    <!--                    <button id="next-tab" type="button"-->
                    <!--                            class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right changeTab2"> Save & Continue-->
                    <!--                    </button>-->
                    <!--                    <button id="cancel" type="button"-->
                    <!--                            class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel & Discard-->
                    <!--                    </button>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!---->
                    <!--            <div class="form-group form-indent hidden tab3" id="actionlist">-->
                    <!--                <h1>Change Log</h1>-->
                    <!---->
                    <!--                <div class="col-lg-8 col-sm-10 col-xs-12">-->

                    <h3>Definition Author Name </h3>

                    <div id="author" class="form-group form-indent">

                        <p class="status">
                            <small>Name of the individual who defined this data element initially.</small>
                        </p>
                        <div class="col-lg-6">
                            <label for="lname">Last Name<span style="color: red"><sup>*</sup></span></label>
                            <input id="lname" type="text" name="defauthorlname" maxlength="25" class="form-control"
                                   value="<?php echo $rowsdataelem['AUTHOR_LNAME']; ?>" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="fname">First Name<span style="color: red"><sup>*</sup></span></label>
                            <input id="fname" type="text" name="defauthorfname" maxlength="25" class="form-control"
                                   value="<?php echo $rowsdataelem['AUTHOR_FNAME']; ?>" required>
                        </div>
                    </div>


                    <!--  Provost Approve / Direct Save Element Def Button Control-->
                    <?php if ($_SESSION['login_right'] == 7) {
                        if ($elemid == 0) { ?>

                            <button name="directsave" type="submit"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right"> Save Element Def
                            </button>
                            <button id="cancel" type="button"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel
                            </button>

                        <?php }
                        if ($elemstatus == 'Proposed') { ?>

                            <button name="approve" type="submit"
                                    class="btn-primary col-lg-4 col-md-4 col-sm-4 pull-right"> Approve Def
                            </button>
                            <button name="update" type="submit"
                                    class="btn-primary col-lg-4 col-md-4 col-sm-4 pull-right"> Save Changes
                            </button>
                            <button type="submit" name="discard"
                                    onclick="return confirm('Are you certain you want to delete the definition?');"
                                    class="btn-primary col-lg-4 col-md-4 col-sm-4 pull-left"> Discard Def
                            </button>

                        <?php } elseif ($elemstatus == 'Approved') { ?>
                            <button name="update" type="submit"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right"> Update Element Def
                            </button>
                            <button type="submit" name="discard"
                                    onclick="return confirm('Are you certain you want to delete the definition?');"
                                    class="btn-primary col-lg-4 col-md-4 col-sm-4 pull-left"> Discard Def
                            </button>
                        <?php }
                    } else {
                        if ($elemid == 0) { ?>
                            <button name="save" type="submit" class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right">
                                Propose Element Def
                            </button>
                            <button id="cancel" type="button"
                                    class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-left canceldatadictbox"> Cancel
                            </button>

                        <?php }
                    } ?>
                </div>
            </div>
        </form>
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


</script>
<script src="../Resources/Library/js/uniqueness.js"></script>
<script src="../Resources/Library/js/tabChange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
