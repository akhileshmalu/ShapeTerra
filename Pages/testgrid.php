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
    <div id="table-status"></div>
    <script>

      $.post("../Resources/Includes/data.php?functionNum=1", function(data) {
        data = $.parseJSON(data);
        $("#jsGrid").jsGrid({
          width: "100%",
          height: "400px",
          editing: true,
          sorting: true,
          paging: true,
          data: data,
          rowClass: function(item, itemIndex) {
            return "client-" + itemIndex;
          },
          controller: {
            loadData: function() {
              return db.clients.slice(0, 15);
            }
          },
          fields: [
            { name: "ID_UNIT_GOAL", type: "text", width: 150 },
            { name: "UNIT_GOAL_AY", type: "text", width: 50 },
            { name: "UNIT_GOAL_TITLE", type: "text", width: 200 },
            { name: "GOAL_STATEMENT", type: "text"},
            { name: "GOAL_ALIGNMENT", type: "text", sorting: false }
          ],
          onRefreshed: function() {
            $("#table-status").html("Table is being saved.");
            var $gridData = $("#jsGrid .jsgrid-grid-body tbody");
            $gridData.sortable({
              update: function(e, ui) {
                var clientIndexRegExp = /\s*client-(\d+)\s*/;
                var indexes = $.map($gridData.sortable("toArray", { attribute: "class" }), function(classes) {
                    return clientIndexRegExp.exec(classes)[1];
                });
                var items = $.map($gridData.find("tr"), function(row) {
                    return $(row).data("JSGridItem");
                });
                $.post("../Resources/Includes/data.php?functionNum=2",{'data':items},function(){
                  $("#table-status").html("Table Saved.");
                })
              }
            });
          }
        });
      });

    </script>
  </body>
</html>
