<?php
  session_start();
  require_once ("../Resources/Includes/connect.php");
  $ouabbrev= $_SESSION['login_ouabbrev'];
  $sqluser = "select NETWORK_USERNAME,OU_NAME,SYS_USER_ROLE,FNAME,LNAME from PermittedUsers Inner Join Hierarchy ON PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.OU_ABBREV where OU_ABBREV = '$ouabbrev'";
  $resultuser = $mysqli->query($sqluser);
  $rowsuser = $resultuser->fetch_assoc();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once("../Resources/Includes/header.php"); ?>
    <title>Test Grid</title>
    <script
			  src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"
			  integrity="sha256-oTyWrNiP6Qftu4vs2g0RPCKr3g1a6QTlITNgoebxRc4="
			  crossorigin="anonymous"></script>
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
  </head>
  <body>
    <div id="jsGrid"></div>
    <script>
      var tableData;
      /*var clients = [
          { "Name": "Otto Clay", "Age": 25, "Country": 1, "Address": "Ap #897-1459 Quam Avenue", "Married": false },
          { "Name": "Connor Johnston", "Age": 45, "Country": 2, "Address": "Ap #370-4647 Dis Av.", "Married": true },
          { "Name": "Lacey Hess", "Age": 29, "Country": 3, "Address": "Ap #365-8835 Integer St.", "Married": false },
          { "Name": "Timothy Henson", "Age": 56, "Country": 1, "Address": "911-5143 Luctus Ave", "Married": true },
          { "Name": "Ramona Benton", "Age": 32, "Country": 3, "Address": "Ap #614-689 Vehicula Street", "Married": false }
      ];*/

      tableData = [
        {"ID_CONTENT":"1","0":"1","Linked_BP_ID":"4","1":"4","CONTENT_BRIEF_DESC":"Create BluePrint","2":"Create BluePrint","CONTENT_LINK":"mvv.php","3":"mvv.php","CONTENT_STATUS":"In progress","4":"In progress","BP_AUTHOR":"3","5":"3","MOD_TIMESTAMP":"2016-12-21 12:10:03","6":"2016-12-21 12:10:03","Sr_No":"1","7":"1","BP_OU_TYPE":"Academic Unit","8":"Academic Unit"},
        {"ID_CONTENT":"2","0":"2","Linked_BP_ID":"4","1":"4","CONTENT_BRIEF_DESC":"Goal Overview & Management","2":"Goal Overview & Management","CONTENT_LINK":"unitgoaloverview.php","3":"unitgoaloverview.php","CONTENT_STATUS":"Pending approval","4":"Pending approval","BP_AUTHOR":"3","5":"3","MOD_TIMESTAMP":"2016-12-21 11:58:42","6":"2016-12-21 11:58:42","Sr_No":"2","7":"2","BP_OU_TYPE":"Academic Unit","8":"Academic Unit"},
        {"ID_CONTENT":"3","0":"3","Linked_BP_ID":"4","1":"4","CONTENT_BRIEF_DESC":"Goal Outcomes Summary","2":"Goal Outcomes Summary","CONTENT_LINK":"goaloutcomeshome.php","3":"goaloutcomeshome.php","CONTENT_STATUS":"Pending approval","4":"Pending approval","BP_AUTHOR":"3","5":"3","MOD_TIMESTAMP":"2016-12-15 00:58:47","6":"2016-12-15 00:58:47","Sr_No":"3","7":"3","BP_OU_TYPE":"Academic Unit","8":"Academic Unit"},
        {"ID_CONTENT":"4","0":"4","Linked_BP_ID":"4","1":"4","CONTENT_BRIEF_DESC":"Faculty Awards","2":"Faculty Awards","CONTENT_LINK":"facultyawards.php","3":"facultyawards.php","CONTENT_STATUS":"In progress","4":"In progress","BP_AUTHOR":"6","5":"6","MOD_TIMESTAMP":"2016-12-21 10:08:00","6":"2016-12-21 10:08:00","Sr_No":"4","7":"4","BP_OU_TYPE":"Academic Unit","8":"Academic Unit"},
        {"ID_CONTENT":"5","0":"5","Linked_BP_ID":"4","1":"4","CONTENT_BRIEF_DESC":"Faculty Info","2":"Faculty Info","CONTENT_LINK":"facultyInfo.php","3":"facultyInfo.php","CONTENT_STATUS":"In progress","4":"In progress","BP_AUTHOR":"3","5":"3","MOD_TIMESTAMP":"2016-12-19 16:33:22","6":"2016-12-19 16:33:22","Sr_No":"5","7":"5","BP_OU_TYPE":"Academic Unit","8":"Academic Unit"},
        {"ID_CONTENT":"6","0":"6","Linked_BP_ID":"4","1":"4","CONTENT_BRIEF_DESC":"Initiatives & Observations","2":"Initiatives & Observations","CONTENT_LINK":"initiatives.php","3":"initiatives.php","CONTENT_STATUS":"Pending approval","4":"Pending approval","BP_AUTHOR":"3","5":"3","MOD_TIMESTAMP":"2016-12-21 11:57:44","6":"2016-12-21 11:57:44","Sr_No":"6","7":"6","BP_OU_TYPE":"Academic Unit","8":"Academic Unit"},
        {"ID_CONTENT":"7","0":"7","Linked_BP_ID":"4","1":"4","CONTENT_BRIEF_DESC":"IR_AC_DiversityPersonnel","2":"IR_AC_DiversityPersonnel","CONTENT_LINK":"uploadOIRAAdata.php","3":"uploadOIRAAdata.php","CONTENT_STATUS":"Not Started","4":"Not Started","BP_AUTHOR":"3","5":"3","MOD_TIMESTAMP":"2016-12-21 11:57:44","6":"2016-12-21 11:57:44","Sr_No":"7","7":"7","BP_OU_TYPE":"Service Unit","8":"Service Unit"}
      ];
      $.post( "../Resources/Includes/data.php?functionNum=1", function(data) {
        console.log(data);
        this.tableData = [data];
        //console.log(this.tableData);
      });
      $("#jsGrid").jsGrid({
          width: "100%",
          height: "400px",
          inserting: true,
          editing: true,
          sorting: true,
          paging: true,
          data: this.tableData,
          fields: [
              { name: "ID_CONTENT", type: "text", width: 150, validate: "required" },
              { name: "Linked_BP_ID", type: "text", width: 50 },
              { name: "CONTENT_BRIEF_DESC", type: "text", width: 200 },
              { name: "CONTENT_LINK", type: "text"},
              { name: "MOD_TIMESTAMP", type: "text", sorting: false },
              { name: "BP_AUTHOR", type: "text", sorting: false }
          ]
      });
      $("tbody").sortable({
          items: "tr",
          appendTo: "parent",
          helper: "clone"
      }).disableSelection();
    </script>
  </body>
</html>
