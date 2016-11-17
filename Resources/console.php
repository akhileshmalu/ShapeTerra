<?php
$error = "";
$i=0;

require_once("../Resources/Includes/connect.php");
$error = "";

$dynamictable = "<table border='1' cellpadding='10' class='table'><tr>";

if (isset($_POST['submit'])) {

    $sql = $_POST['query'];
    IF ($result = $mysqli->query($sql)) {

        /*
         * Header of SQL file
         */
        $fieldcnt = $result ->field_count;
        while($i<$fieldcnt)
        {
            $meta = $result ->fetch_field();
            $dynamictable .="<th>".$meta->name."</th>";
            $i++;
        }

        $dynamictable.= '</tr>';

        /* fetch associative array */
        while ($row = $result->fetch_assoc()) {

            $dynamictable.='<tr>';
            foreach ($row as $value) {

                $dynamictable .= '<td>'. $value . '</td>';
            }
            $dynamictable .= '</tr>';
        }

        $dynamictable .= '</table>';
        $error = "Query Executed";

        $result->free();
        $mysqli->close();
    } else {
        $eror = "Query Failed";
    }
}
if (isset($_POST['multi'])) {
    $sql = $_POST['query'];
    if ($mysqli->multi_query($sql)) {
        echo "Record Created";
    } else {
        echo "Query Failed";
    }
}

?>

<?php
//Include Header
require_once("../Resources/Includes/header.php");
?>
</head>
<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>


<h1>Console for Shapeterra DB <a href="../Pages/login.php" class="btn-primary">Login</a></h1>


<form id="consoleform" method="POST">
    <div class="col-lg-3">
        <label for="querytag">Enter your Query</label>
        <div class="form-group">
            <input type="text" id="querytag" name="query" class="form-control" placeholder="Enter your query">
        </div>
        <div class="form-group">
            <input type=submit name="submit" class="btn-sm " value="Single Query">
            <input type=submit name="multi" class="btn-sm" value="Multi Query">
        </div>
        <label id="error"><?php echo $error ?> </label>
        <hr>
        Display Result <br>
        <?php echo $dynamictable; ?>
    </div>
</form>

<!--</div>-->

<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
