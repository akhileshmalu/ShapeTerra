<?php
session_start();
require_once ("../Resources/Includes/connect.php");

$sql1 = "select * from broadcast;";
$result1=$mysqli1->query($sql1);
$rows1 = $result1->fetch_assoc();




//Include Header
require_once("../Resources/Includes/header.php");
?>

<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css" />

<div class="hr"></div>
<div id="main-content" class="col-xs-10">
    <h1 id="title">BluePrint Completion</h1>

    <ul id="tabs" class="nav nav-pills" id="menu-secondary" role="tablist">
        <li class="active"><a href="#input1">Confirm Goals</a></li>
        <li><a href="#input2">Input Section 1</a></li>
    </ul>
</div>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active fade in" id="input1">
        <div class="form-group col-xs-4" id="actionlist">
            <label for="goaltitle">Please confirm Previous year Goals :</label><br>
            <select multiple="multiple" name="goaltitles[]" id= "goaltitle">
                <?php
                $ay = $rows1['BROADCAST_AY'];
                $ay -= 101;
                $ouabbrev = $_SESSION['login_ouabbrev'];
                $aydesc = idtostring($ay);
                $sql = "select * from BP_UnitGoals where find_in_set ('$aydesc',UNIT_GOAL_AY)>0 and OU_ABBREV ='$ouabbrev';";
                $result = $mysqli->query($sql);
                while($rows = $result ->fetch_assoc()){ ?>
                    <option value="<?php echo $rows['ID_UNIT_GOAL']; ?>"><?php echo $rows['UNIT_GOAL_TITLE']."\n"; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade " id="input2">
        Tab 2
        <div>

        </div>
    </div>
</div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
