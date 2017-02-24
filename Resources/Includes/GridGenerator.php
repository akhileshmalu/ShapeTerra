<?php

  require "initalize.php";

  Class GridGenerator extends Initalize
  {

    function __construct()
    {

      super();

    }

    public function generateGrid($table,$columns,$columnNames)
    {

      //getting connection var
      $connection = $this->connection;

      switch($table){
        case "AC_FacultyAwards":
          $this->initOrderFacultyAwards();
          $getData = $connection->prepare("SELECT * FROM `$table` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? ORDER BY ID_SORT ASC");
          $getData->bindParam(1,$columns["ouAbbrev"],PDO::PARAM_STR);
          $getData->bindParam(2,$columns["outcomesAY"],PDO::PARAM_STR);
          $getData->execute();
        default:
          break;
      }

      $data = $getData->fetch();

      echo "
        data = $.parseJSON(data);
        $('#jsGrid').jsGrid({
          width: '100%',
          height: '400px',
          sorting: true,
          paging: true,
          data: data,
          rowClass: function(item, itemIndex) {
            return 'client-' + itemIndex;
          },
          controller: {
            loadData: function() {
              return db.clients.slice(0, 15);
            }
          },
      ";

      for ($i = 0; count($columnNames) > $i; $i++){

        if ($columnNames[0]["complex"]){

          

        }else{

        }

      }

    }

    public function initOrderFacultyAwards()
    {

      $selectedYear = $_SESSION["bpayname"];
      if ($ouid == 4) {

        $ouAbbrev = $_SESSION['bpouabbrev'];

      } else {

        $ouAbbrev = $_SESSION['login_ouabbrev'];

      }
      $zeroCheck = 0;
      $null = null;

      $getCurrentOrder = $this->connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND ID_SORT != ? OR ID_SORT != ? ORDER BY ID_SORT ASC");
      $getCurrentOrder->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
      $getCurrentOrder->bindParam(2, $selectedYear, PDO::PARAM_STR);
      $getCurrentOrder->bindParam(3, $zeroCheck, PDO::PARAM_INT);
      $getCurrentOrder->bindParam(4, $null, PDO::PARAM_STR);
      $getCurrentOrder->execute();
      $rowsGetCurrentOrder = $getCurrentOrder->rowCount();

      $getNewOrder = $this->connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND ID_SORT = ? OR ID_SORT = ?");
      $getNewOrder->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
      $getNewOrder->bindParam(2, $selectedYear, PDO::PARAM_STR);
      $getNewOrder->bindParam(3, $zeroCheck, PDO::PARAM_INT);
      $getNewOrder->bindParam(4, $null, PDO::PARAM_STR);
      $getNewOrder->execute();

      while ($data = $getNewOrder->fetch()) {

        if ($data["ID_SORT"] == 0 || $data["ID_SORT"] == NULL) {

          $rowsGetCurrentOrder++;

          $updateItem = $this->connection->prepare("UPDATE `AC_FacultyAwards` SET ID_SORT = ? WHERE ID_FACULTY_AWARDS = ?");
          $updateItem->bindParam(1, $rowsGetCurrentOrder, PDO::PARAM_INT);
          $updateItem->bindParam(2, $data["ID_FACULTY_AWARDS"], PDO::PARAM_INT);
          $updateItem->execute();

        }

      }

    }

  }
