<?php

/*
 * This Page controls Intiation of Academic BluePrint module.
 */

require_once("../Resources/Includes/Initialize.php");
$initialize = new Initialize();
$initialize->checkSessionStatus();
$connection = $initialize->connection;

$time = date('Y-m-d H:i:s');
$message = array();
$errorflag = 0;

$sqlbroad = "";
$ou = array();
$globalAdminRoles = null;
$broad_id = 0;
$author = $_SESSION['login_userid'];
$first = TRUE;

/*
 * Query to show Non terminated Organization Unit as on date.
 */
$sqlou = "SELECT * FROM Hierarchy WHERE OU_ABBREV != 'UNAFFIL' AND OU_DATE_END IS NULL AND OU_TYPE ='Academic Unit' 
ORDER BY OU_NAME ASC;";
$resultou = $connection->prepare($sqlou);
$resultou->execute();


/*
 * Query to show Academic years for Initiating Blue Print.
 */

$sqlay = "SELECT * FROM AcademicYears ORDER BY ID_ACAD_YEAR ASC;";
$resultay = $connection->prepare($sqlay);
$resultay->execute();

// To provide Global access to Sys Admin of Provost Unit <Inter Unit Role Change>
$globalAdminRoles = $_SESSION['login_outype'] == 'Administration' ? "'Provost'" :
    "'Provost','System Administrator','System Developer','Team Lead'";

$sqlrole = "SELECT * FROM UserRoles WHERE USER_ROLE NOT IN ($globalAdminRoles);";
$resultrole = $connection->prepare($sqlrole);
$resultrole->execute();

if (isset($_POST['addUser'])) {

    $username = $initialize->test_input($_POST['username']);
    $role = $_POST['role'];
    $ouid = $_SESSION['login_outype'] == 'Administration' ? $_POST['ouname'] : $_SESSION['login_ouid'];

    try {
        $sql = "INSERT INTO PermittedUsers (NETWORK_USERNAME, USER_OU_MEMBERSHIP, SYS_USER_ROLE, USER_STATUS,
 FNAME, LNAME, VIP_ID) VALUES (:username,:ouid, :role, '1','Test1','Test2','111')";
        $result = $initialize->connection->prepare($sql);
        $result->bindParam(':username', $username, 2);
        $result->bindParam(':ouid', $ouid, 2);
        $result->bindParam(':role', $role, 2);
        if ($result->execute()) {
            $message[0] = "User: $username Added successfully.";
        } else {
            $message[0] = "User: $username could not be Added.";
        }

    } catch (PDOException $e) {
        $e->getMessage();
    }
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<script
    src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"
    integrity="sha256-oTyWrNiP6Qftu4vs2g0RPCKr3g1a6QTlITNgoebxRc4="
    crossorigin="anonymous"></script>
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css"/>
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>

<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div class="overlay hidden"></div>
<?php if (isset($_POST['addUser'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo $_SERVER['PHP_SELF']; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Team Lead Dashboard</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <form action="" method="POST" class="col-xs-12">
            <h2>1. Enter Network Username</h2>
            <div id="userbox">
                <div  class="input-group">
                    <input type="text" name="username" class="form-control" placeholder="Network Username / VIP ID">
                    <span class="input-group-btn"><button class="btn" type="button">Search</button></span>
                </div>
            </div>
            <h2>2. Select User Roles </h2>
            <div>
                <select name="role" class="form-control" id="roles"
                        style="padding: 0px !important; background-color: #fff !important;">
                    <option value=""></option>
                    <?php while ($rowsrole = $resultrole->fetch(4)): { ?>
                        <option value="<?php echo $rowsrole[0]; ?>"><?php echo $rowsrole[1]; ?></option>
                    <?php } endwhile; ?>
                </select>
            </div>
            <!--            <br/>-->
            <?php if ($_SESSION['login_outype'] == 'Administration'): ?>
                <h2>3. Select Organization Unit</h2>
                <div>
                    <select name="ouname" class="form-control">
                        <option value="0" selected>--Select A Unit--</option>
                        <?php while ($rowsou = $resultou->fetch(4)) { ?>
                            <option value="<?php echo $rowsou[0]; ?>"><?php echo $rowsou[1]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php endif; ?>
            <div>
                <input type="submit" name="addUser" value="Add User" class="btn-primary pull-right">
            </div>
            <div id="taskboard" class="col-xl-12" style="margin-top: 5px;">
                <h3 style="padding: 5px;">PERMITTED USERS</h3>
                <div id="jsGridUsersTable"></div>

                <script>
                    var status;

                    var ouid = $('#ouid').text().substr(11);

                    $.extend({
                        getUrlVars: function () {
                            var vars = [], hash;
                            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                            for (var i = 0; i < hashes.length; i++) {
                                hash = hashes[i].split('=');
                                vars.push(hash[0]);
                                vars[hash[0]] = hash[1];
                            }
                            return vars;
                        },
                        getUrlVar: function (name) {
                            return $.getUrlVars()[name];
                        }
                    });
                    $.post("../Resources/Includes/Data.php?functionNum=7&ouid=" + ouid, function (data) {
                        if(data==""){

                        }
                        data = $.parseJSON(data);
                        //console.log(data);
                        $("#jsGridUsersTable").jsGrid({
                            width: "100%",
                            height: "400px",
                            editing: false,
                            sorting: true,
                            paging: true,
                            data: data,
                            rowClass: function (item, itemIndex) {
                                return "client-" + itemIndex;
                            },
                            controller: {
                                loadData: function () {
                                    return db.clients.slice(0, 15);
                                }
                            },
                            rowClick: function (args) {
                                window.location = "../Pages/modifyUser.php?id_user=" + args.item.ID_STATUS;
                            },

                            fields: [
                                {name: "USERFULLNAME", title: "Name", type: "text", width: "auto"},
                                {
                                    name: "NETWORK_USERNAME", title: "Network Username",
//                                    itemTemplate: function (value, item) {
//                                        return $("<a>").attr("href", "../Pages/modifyUser.php?id_user=" +
//                                            item.ID_STATUS).text(value);
//                                    },
                                    type: "text", width: "auto"
                                },
                                {name: "USER_ROLE", title: "User Role", type: "text", width: "auto"}
                            ],
//
                        });

                        var showDetailsDialog = function (item) {
                            return $("<a>").attr("href", "../Pages/modifyUser.php?id_user=" + item.ID_STATUS);
                        }
                    });

                </script>
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

        var tableBody = $('<div> <table id="mytb" class="table"> <thead>' +
            ' <tr> <th style="width: 25%;">User Full Name</th> <th style="width: 40%">User College</th> <th ' +
            'style="width: ' +
            '35%">Select</th> </tr> ' + '</thead>' +
            ' <tbody> <tr> ' +
            '<td>Blah</td> <td>Blah More</td>' +
            ' <td class="vcenter"><input type="checkbox" id="blahA" value="1"/></td> </tr> </tbody> </table> </div>');
        $('#userbox').append(tableBody);

        $('#selectChk').on('change', function() {
            if ($('#selectChk').is(':checked')) {
                //$(this).prop('checked',false);
                $('#userCredential').attr("readonly","readonly");
            } else {
                //$(this).prop('checked',true);
                $('#userCredential').removeAttr("readonly");
            }
        });


</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
