<?php

session_start();
$error = array();
$errorflag =0;

require_once ("../Resources/Includes/connect.php");



$csv = array();
$tablefileds = array();
$tablevalue = array();
$sqlupload =null;
$notBackToDashboard =true;

/*
 * Available Initiated Blueprints Academic Years Names.
 */
$sqlbroad = "select DISTINCT(BROADCAST_AY) from broadcast ; ";
$resultbroad = $mysqli->query($sqlbroad);

$sqluploadtables = "show tables where Tables_in_testdb LIKE 'IR%'; ";
$resultuploadtables = $mysqli->query($sqluploadtables);


if(isset($_POST['upload'])) {

    $ayname = $_POST['ay'];
    $tablename = $_POST['table'];

// check there are no errors
    if ($_FILES['csv']['error'] == 0) {

        $name = $_FILES['csv']['name'];
        $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
        $type = $_FILES['csv']['type'];
        $tmpName = $_FILES['csv']['tmp_name'];

        // check the file is a csv
        if ($ext === 'csv') {

            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
                // necessary if a large csv file
                set_time_limit(0);

                $row = 0;

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {


                    // number of fields in the csv
//                $col_count = count($data);

                    // get the values from the csv
                    for ($i = 1; $i <= count($data); $i++) {

                        $colindex = 'col' . $i;
                        $csv[$row][$colindex] = $data[$i - 1];
                        if ($i != count($data)) {
                            if ($row == 0) {
                                $tablefileds[$i] = $csv[$row][$colindex] . ',';
                            } else {
                                $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'" . ',';
                            }
                        } else {
                            if ($row == 0) {
                                $tablefileds[$i] = $csv[$row][$colindex];
                            } else {
                                $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'";
                            }
                        }


                    }

                    // inc the row
                    $row++;
                }


                for ($j = 1; $j < $row; $j++) {
                    $sqlupload .= "INSERT INTO $tablename ( ";
                    foreach ($tablefileds as $fields) {
                        $sqlupload .= $fields;
                    }
                    $sqlupload .= " ) Values (";
                    foreach ($tablevalue[$j] as $fieldvalue) {
                        $sqlupload .= $fieldvalue;
                    }
                    $sqlupload .= ");" . "<br>";
                }




//                echo $sqlupload;


//                if ($mysqli->multi_query($sqlupload)) {
//                    $error[0] = "Data Uploaded Successfully.";
//                } else {
//                    $error[0] = "Error in Data. Upload Failed.";
//                }

                fclose($handle);
            }
        } else{
            $error[0] = "Please select only csv format File";
        }
    } else {
        $error[0] = "Error in Uploading File. ";
    }

}




require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>
<div class="overlay hidden"></div>
<?php if (isset($_POST['submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="account.php" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>


<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title" class="">Service Unit Upload</h1>
    </div>

    <!-- Possible Greeting box -->
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Hello <?php echo $rowsac['FNAME']; ?>! </h1>
        <p class="status"><span>Org Unit Name: </span> <?php echo $_SESSION['login_ouname']; ?></p>
        <p class="status"><span>User role: </span> <?php echo $rowsmenu['USER_RIGHT']; ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
    <form action="uploadOIRAAdata.php" method="post" class="" enctype="multipart/form-data">
        <div id="" style="margin-top: 10px;">
        <div class="form-group">

                <label for ="AYgoal" >Select Academic Year</label>
                <select  name="ay" class="form-control" id="AYgoal" required>
                    <option value=""></option>
                    <?php while($rowbroad = $resultbroad->fetch_array(MYSQLI_NUM)) { ?>
                        <option value="<?php echo $rowbroad[0]; ?>"> <?php echo $rowbroad[0]; ?> </option>
                    <?php } ?>
                </select>
        </div>
            <div class="form-group">

                <label for ="AYgoal" >Select Data Table</label>
                <select  name="table" class="form-control" id="AYgoal" required>
                    <option value=""></option>
                    <?php while($rowuploadtables = $resultuploadtables->fetch_array(MYSQLI_NUM)) { ?>
                        <option value="<?php echo $rowuploadtables[0]; ?>"> <?php echo $rowuploadtables[0]; ?> </option>
                    <?php } ?>
                </select>
            </div>

        <div id="suppfacinfo" class="form-group">
            <label for="supinfo"><small><em>Please Attach CSV file of Above Academic Year.</em></small>
            </label>
            <input id="supinfo" type="file" name="csv" onchange="selectorfile(this)" class="form-control" required>
        </div>

            <?php if (isset($_POST['upload'])) { ?>
                <div id="display">
                    <p><b>Display Data - Table Name: </b></p>

                    <?php  echo $tablename."<div><table border='1' cellpadding='3'><tbody><tr>";
                    foreach ($csv[0] as $fields) {
                        echo "<th>".$fields."</th>";
                    }
                    echo "</tr><tr>";
                    for($j =1;$j < $row; $j++) {
                        foreach ($csv[$j] as $fieldvalue) {
                            echo "<td>".$fieldvalue."</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>"; ?>

                    <p>Please Select Save to Confirm Uploading If Below Data is Correct.</p>
                </div>

            <?php } ?>

            <input type="submit" name="upload" id="upload" class="btn-primary pull-right" onclick="$('#save').removeClass('hidden');$('#upload').addClass('hidden');" value="Upload">

            <input type="submit" name="save" id="save" class="btn-primary pull-right hidden" value="Save">

        </div>
    </form>
    </div>

</div>

<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>


<script type="text/javascript">

    function selectorfile(selected) {
        var b = $(selected).val().substr(12);
        alert(b + " is selected.");
    }

</script>

