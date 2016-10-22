<?php
<<<<<<< Updated upstream
=======
session_start();



>>>>>>> Stashed changes
//Include Header
require_once("../Resources/Includes/header.php");
?>

<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<<<<<<< Updated upstream
<!-- Main Content goes here -->

<div class="container">
    <div class="row">
        <div class='col-sm-6'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" />
=======


<div class="col-lg-offset-3 col-lg-3 col-md-6 col-xs-9" id="ContentRight">
    <form action ="" method="POST">
        <div class='col-lg-12'>
            <div class="form-group">
                <div class='input-group date' id='datepicker'>
                    <input type='text' name="startdate" class="form-control" id="AYstart" />
>>>>>>> Stashed changes
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
<<<<<<< Updated upstream
                $('#datetimepicker1').datetimepicker();
            });
        </script>
    </div>
</div>

=======
                $('#datepicker').datetimepicker();
            });
        </script>
        <input type="submit" name="submit" value="submit" class="btn-primary">
    </form>
</div>




>>>>>>> Stashed changes
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
