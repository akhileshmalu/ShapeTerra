<?php

if (isset($_POST['functionNum']))
  $function = $_POST['functionNum'];

if (isset($_GET['functionNum']))
  $function = $_GET['functionNum'];

if (!empty($function)) {

  $Data = new Data();

}

switch ($function) {
  case 1:
    $Data->getUnitGoals($_GET["viewpoint"]);
    break;
  case 2:
    $itemData = $_POST["data"];
    $indexes = $_POST["indexes"];
    $Data->saveUnitGoalsOrder($itemData, $indexes);
    break;
  case 3:
    $Data->getUnitGoalsStatus();
    break;
  case 4:
    $itemData = $_POST["data"];
    $indexes = $_POST["indexes"];
    $Data->saveFacultyAwardsOrder($itemData, $indexes);
    break;
  case 5:
    $Data->getGoalAuthors();
    break;
  case 6:
    $Data->getFacultyAwards($_GET["viewpoint"]);
    break;
  default:
    break;
}

Class Data
{

  private $connection;

  function __construct()
  {

    require_once("Initialize.php");
    $initalize = new Initialize();
    $initalize->checkSessionStatus();
    $this->connection = $initalize->connection;

  }

  ////////////////////Unit Goals Grid//////////////////////////

  public function getUnitGoals($viewpoint)
  {

    $this->initOrderGoals();

    $selectedYear = $_SESSION["bpayname"];
    if ($ouid == 4) {
      $ouAbbrev = $_SESSION['bpouabbrev'];
    } else {
      $ouAbbrev = $_SESSION['login_ouabbrev'];
    }
    $counter = 0;

    if ($viewpoint == "back") {

      $viewpoint = "Looking Back";

    } else if ($viewpoint == "real") {

      $viewpoint = "Real Time";

    } else {

      $viewpoint = "Looking Ahead";

    }

    $getUnitGoals = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
    $getUnitGoals->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
    $getUnitGoals->bindParam(2, $selectedYear, PDO::PARAM_STR);
    $getUnitGoals->bindParam(3, $viewpoint, PDO::PARAM_STR);
    $getUnitGoals->execute();

    while ($data = $getUnitGoals->fetchAll()) {

      if ($counter != 0) {

        echo "," . json_encode($data);

      } else {

        echo json_encode($data);

      }

      $counter++;

    }

  }

  public function initOrderGoals()
  {

    $selectedYear = $_SESSION["bpayname"];
    if ($ouid == 4) {

      $ouAbbrev = $_SESSION['bpouabbrev'];

    } else {

      $ouAbbrev = $_SESSION['login_ouabbrev'];

    }

    $viewPoint = array("Looking Back","Real Time","Looking Ahead");
    $zeroCheck = 0;

    for ($i = 0; $i < count($viewPoint); $i++){

      $getCurrentOrder = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND ID_SORT != ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
      $getCurrentOrder->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
      $getCurrentOrder->bindParam(2, $selectedYear, PDO::PARAM_STR);
      $getCurrentOrder->bindParam(3, $zeroCheck, PDO::PARAM_STR);
      $getCurrentOrder->bindParam(4, $viewPoint[$i], PDO::PARAM_STR);
      $getCurrentOrder->execute();
      $rowsGetCurrentOrder = $getCurrentOrder->rowCount();

      $getNewOrder = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND ID_SORT = ? AND GOAL_VIEWPOINT = ?");
      $getNewOrder->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
      $getNewOrder->bindParam(2, $selectedYear, PDO::PARAM_STR);
      $getNewOrder->bindParam(3, $zeroCheck, PDO::PARAM_STR);
      $getNewOrder->bindParam(4, $viewPoint[$i], PDO::PARAM_STR);
      $getNewOrder->execute();

      while ($data = $getNewOrder->fetch()) {

        if ($data["ID_SORT"] == 0 || $data["ID_SORT"] == NULL) {

          $updateItem = $this->connection->prepare("UPDATE `BP_UnitGoals` SET ID_SORT = ? WHERE ID_UNIT_GOAL = ? AND GOAL_VIEWPOINT = ?");
          $updateItem->bindParam(1, $rowsGetCurrentOrder, PDO::PARAM_INT);
          $updateItem->bindParam(2, $data["ID_UNIT_GOAL"], PDO::PARAM_INT);
          $updateItem->bindPAram(3, $viewPoint[$i], PDO::PARAM_STR);
          $updateItem->execute();

        }

      }

    }

  }

  public function getUnitGoalsStatus()
  {

    $counter = 0;

    $getGoalStatus = $this->connection->prepare("SELECT * FROM `BP_UnitGoalOutcomes`");
    $getGoalStatus->execute();

    while ($data = $getGoalStatus->fetchAll()) {

      if ($counter != 0) {

        echo "," . json_encode($data);

      } else {

        echo json_encode($data);

      }

      $counter++;

    }

  }

  public function saveUnitGoalsOrder($itemData, $indexes)
  {

    $selectedYear = $_SESSION["bpayname"];

    if ($ouid == 4) {

      $ouAbbrev = $_SESSION['bpouabbrev'];

    } else {

      $ouAbbrev = $_SESSION['login_ouabbrev'];

    }

    for ($i = 0; count($itemData) > $i; $i++) {

      $counter = $i + 1;

      $updateList = $this->connection->prepare("UPDATE `BP_UnitGoals` SET ID_SORT = ? WHERE ID_UNIT_GOAL = ?");
      $updateList->bindParam(1, $counter, PDO::PARAM_INT);
      $updateList->bindParam(2, $itemData[$i][0], PDO::PARAM_INT);
      $updateList->execute();

    }

  }

  public function getGoalAuthors()
  {

    $counter = 0;

    $getGoalStatus = $this->connection->prepare("SELECT * FROM `PermittedUsers`");
    $getGoalStatus->execute();

    while ($data = $getGoalStatus->fetchAll()) {

      if ($counter != 0) {

        echo "," . json_encode($data);

      } else {

        echo json_encode($data);

      }

      $counter++;

    }

  }

  ////////////////////Faculty Awards Grid///////////////////////////

  public function getFacultyAwards($viewpoint)
  {

    $this->initOrderFacultyAwards();

    $selectedYear = $_SESSION["bpayname"];

    if ($ouid == 4) {

      $ouAbbrev = $_SESSION['bpouabbrev'];

    } else {

      $ouAbbrev = $_SESSION['login_ouabbrev'];

    }

    $counter = 0;

    $getUnitGoals = $this->connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ? AND AWARD_TYPE = ? ORDER BY ID_SORT ASC");
    $getUnitGoals->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
    $getUnitGoals->bindParam(2, $selectedYear, PDO::PARAM_STR);
    $getUnitGoals->bindParam(3, $viewpoint, PDO::PARAM_STR);
    $getUnitGoals->execute();

    while ($data = $getUnitGoals->fetchAll()) {

      if ($counter != 0) {

        echo "," . json_encode($data);

      } else {

        echo json_encode($data);

      }

      $counter++;

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
    $viewPoint = array("Service","Research","Teaching","Other");

    for ($i = 0; $i < count($viewPoint); $i++){

      $getCurrentOrder = $this->connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE AWARD_TYPE = ? AND OU_ABBREV = ? AND OUTCOMES_AY = ? AND ID_SORT != ? OR ID_SORT != ? ORDER BY ID_SORT ASC");
      $getCurrentOrder->bindParam(1, $viewPoint[$i], PDO::PARAM_STR);
      $getCurrentOrder->bindParam(2, $ouAbbrev, PDO::PARAM_STR);
      $getCurrentOrder->bindParam(3, $selectedYear, PDO::PARAM_STR);
      $getCurrentOrder->bindParam(4, $zeroCheck, PDO::PARAM_INT);
      $getCurrentOrder->bindParam(5, $null, PDO::PARAM_STR);
      $getCurrentOrder->execute();
      $rowsGetCurrentOrder = $getCurrentOrder->rowCount();

      $getNewOrder = $this->connection->prepare("SELECT * FROM `AC_FacultyAwards` WHERE AWARD_TYPE = ? AND OU_ABBREV = ? AND OUTCOMES_AY = ? AND ID_SORT = ? OR ID_SORT = ?");
      $getNewOrder->bindParam(1, $viewPoint[$i], PDO::PARAM_STR);
      $getNewOrder->bindParam(2, $ouAbbrev, PDO::PARAM_STR);
      $getNewOrder->bindParam(3, $selectedYear, PDO::PARAM_STR);
      $getNewOrder->bindParam(4, $zeroCheck, PDO::PARAM_INT);
      $getNewOrder->bindParam(5, $null, PDO::PARAM_STR);
      $getNewOrder->execute();

      while ($data = $getNewOrder->fetch()) {

        if ($data["ID_SORT"] == 0 || $data["ID_SORT"] == NULL) {

          $rowsGetCurrentOrder++;

          $updateItem = $this->connection->prepare("UPDATE `AC_FacultyAwards` SET ID_SORT = ? WHERE ID_FACULTY_AWARDS = ? AND AWARD_TYPE = ?");
          $updateItem->bindParam(1, $rowsGetCurrentOrder, PDO::PARAM_INT);
          $updateItem->bindParam(2, $data["ID_FACULTY_AWARDS"], PDO::PARAM_INT);
          $updateItem->bindParam(3, $viewPoint[$i], PDO::PARAM_STR);
          $updateItem->execute();

        }

      }

    }

  }

  public function saveFacultyAwardsOrder($itemData, $indexes)
  {

    $selectedYear = $_SESSION["bpayname"];

    if ($ouid == 4) {

      $ouAbbrev = $_SESSION['bpouabbrev'];

    } else {

      $ouAbbrev = $_SESSION['login_ouabbrev'];

    }

    for ($i = 0; count($itemData) > $i; $i++) {

      $counter = $i + 1;

      $updateList = $this->connection->prepare("UPDATE `AC_FacultyAwards` SET ID_SORT = ? WHERE ID_FACULTY_AWARDS = ?");
      $updateList->bindParam(1, $counter, PDO::PARAM_INT);
      $updateList->bindParam(2, $itemData[$i][0], PDO::PARAM_INT);
      $updateList->execute();

    }

  }

  ///////////////////////////////////////////////

  public function getBluePrintsContent()
  {

    $getBluePrintsContent = $this->connection->prepare("SELECT * FROM `BpContents`");
    $getBluePrintsContent->execute();
    $rowsGetBluePrintsContent = $getBluePrintsContent->rowCount();

    if ($rowsGetBluePrintsContent > 0) {

      $counter = 0;

      while ($data = $getBluePrintsContent->fetchAll()) {

        if ($counter != 0) {

          echo "," . json_encode($data);

        } else {

          echo json_encode($data);

        }

        $counter++;

      }

    } else {

      echo "<h6>No data was found!</h6>";

    }

  }

  public function getUnitGoalsOutcome()
  {

    $contentLinkId = $_GET['linkid'];
    $counter = 0;

    $getUnitGoals = $this->connection->prepare("SELECT * FROM `BpContents` WHERE Linked_BP_ID = ?");
    $getUnitGoals->bindParam(1, $contentLinkId, PDO::PARAM_STR);
    $getUnitGoals->execute();

    while ($data = $getUnitGoals->fetchAll()) {

      if ($counter != 0) {

        echo "," . json_encode($data);

      } else {

        echo json_encode($data);

      }

      $counter++;

    }

  }

}
