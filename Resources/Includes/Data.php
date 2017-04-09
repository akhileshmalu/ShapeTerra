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
    case 7:
        // Used for Add User JsGrid
        $Data->getPermittedUsers($_GET["ouid"]);
        break;

    case 8:
        // Used for Faculty Nomination Award Grid
        $Data->getFacultyNominationsAwards($_GET["viewpoint"]);
        break;

    case 9:
        // Used for setting Order of Fac Nomination
        $itemData = $_POST["data"];
        $indexes = $_POST["indexes"];
        $Data->saveFacultyNominationsAwardsOrder($itemData, $indexes);
        break;

    case 10:
        // Used for Search User in Adduser page - Only available at Prod / test Servers
        $Data->searchUserInfo($_POST['username']);
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

        $viewPoint = array("Looking Back", "Real Time", "Looking Ahead");
        $zeroCheck = 0;

        for ($i = 0; $i < count($viewPoint); $i++) {

            $getCurrentOrder = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND ID_SORT != ? AND GOAL_VIEWPOINT = ? ORDER BY ID_SORT ASC");
            $getCurrentOrder->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
            $getCurrentOrder->bindParam(2, $selectedYear, PDO::PARAM_STR);
            $getCurrentOrder->bindParam(3, $zeroCheck, PDO::PARAM_STR);
            $getCurrentOrder->bindParam(4, $viewPoint[$i], PDO::PARAM_STR);
            $getCurrentOrder->execute();
            $rowsGetCurrentOrder = $getCurrentOrder->rowCount();
            // Current count is of element other than 0 hence new element will have rank current +1;
            $rowsGetCurrentOrder++;

            $getNewOrder = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE OU_ABBREV = ? AND UNIT_GOAL_AY = ? AND ID_SORT = ? AND GOAL_VIEWPOINT = ?");
            $getNewOrder->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
            $getNewOrder->bindParam(2, $selectedYear, PDO::PARAM_STR);
            $getNewOrder->bindParam(3, $zeroCheck, PDO::PARAM_STR);
            $getNewOrder->bindParam(4, $viewPoint[$i], PDO::PARAM_STR);
            $getNewOrder->execute();


            while ($data = $getNewOrder->fetch()) {

                if ($data["ID_SORT"] == 0
                //    || $data["ID_SORT"] == NULL || $data["ID_SORT"] == ""
                ) {
                    $updateItem = $this->connection->prepare("UPDATE `BP_UnitGoals` SET ID_SORT = ? WHERE ID_UNIT_GOAL = ? AND GOAL_VIEWPOINT = ?");
                    $updateItem->bindParam(1, $rowsGetCurrentOrder, PDO::PARAM_INT);
                    $updateItem->bindParam(2, $data["ID_UNIT_GOAL"], PDO::PARAM_INT);
                    $updateItem->bindParam(3, $viewPoint[$i], PDO::PARAM_STR);
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

//        $selectedYear = $_SESSION["bpayname"];
//
//        if ($ouid == 4) {
//
//            $ouAbbrev = $_SESSION['bpouabbrev'];
//
//        } else {
//
//            $ouAbbrev = $_SESSION['login_ouabbrev'];
//
//        }

        for ($i = 0; count($itemData) > $i; $i++) {

            $counter = $i + 1;

            $updateList = $this->connection->prepare("UPDATE `BP_UnitGoals` SET ID_SORT = ? WHERE ID_UNIT_GOAL = ?;");
            $updateList->bindParam(1, $counter, PDO::PARAM_INT);
            $updateList->bindParam(2, $itemData[$i][0], PDO::PARAM_INT);
            $updateList->execute();

        }

    }

    public function setUnitGoalsOrder($viewpoint)
    {

        for ($i = 0; count($itemData) > $i; $i++) {

            $counter = $i + 1;

            $updateList = $this->connection->prepare("UPDATE `BP_UnitGoals` SET ID_SORT = ? WHERE ID_UNIT_GOAL = ?");
            $updateList->bindParam(1, $counter, PDO::PARAM_INT);
            $updateList->bindParam(2, $itemData[$i][0], PDO::PARAM_INT);
            $updateList->execute();

        }

    }

    // Update of View Point in Unitgoal_detail tackle new SORT_ID here.
    public function setUpdateViewPointOrder($goal_id,$newViewPoint){

        if ($ouid == 4) {
            $ouAbbrev = $_SESSION['bpouabbrev'];

        } else {
            $ouAbbrev = $_SESSION['login_ouabbrev'];
        }

        $selectedYear = $_SESSION["bpayname"];

        $updateList = $this->connection->prepare("SELECT * FROM `BP_UnitGoals` WHERE GOAL_VIEWPOINT = :newViewPoint 
        AND UNIT_GOAL_AY = :selectedYear AND OU_ABBREV = :ouabbrev ;");
        $updateList->bindParam(':newViewPoint', $newViewPoint, 2);
        $updateList->bindParam(':selectedYear', $selectedYear, 2);
        $updateList->bindParam(':ouabbrev', $ouAbbrev, 2);
        $updateList->execute();
        $newCount = $updateList->rowCount();

        $updateList = $this->connection->prepare("UPDATE `BP_UnitGoals` SET ID_SORT = ? WHERE ID_UNIT_GOAL = ?;");
        $updateList->bindParam(1, $newCount, PDO::PARAM_INT);
        $updateList->bindParam(2, $goal_id, PDO::PARAM_INT);
        $updateList->execute();


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
        $viewPoint = array("Service", "Research", "Teaching", "Other");

        for ($i = 0; $i < count($viewPoint); $i++) {

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

                if ($data["ID_SORT"] == 0 || $data["ID_SORT"] == NULL || $data["ID_SORT"] == "") {

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

    ////////////////////Faculty Nominations Grid///////////////////////////

    public function getFacultyNominationsAwards($viewpoint)
    {

        $this->initOrderFacultyNominationsAwards();

        $selectedYear = $_SESSION["bpayname"];

        if ($ouid == 4) {

            $ouAbbrev = $_SESSION['bpouabbrev'];

        } else {

            $ouAbbrev = $_SESSION['login_ouabbrev'];

        }

        $counter = 0;

        $getFacultyNomination = $this->connection->prepare("SELECT * FROM `AC_FacultyNominations` WHERE OU_ABBREV = ? 
        AND OUTCOMES_AY = ? AND AWARD_TYPE = ? ORDER BY ID_SORT ASC");
        $getFacultyNomination->bindParam(1, $ouAbbrev, PDO::PARAM_STR);
        $getFacultyNomination->bindParam(2, $selectedYear, PDO::PARAM_STR);
        $getFacultyNomination->bindParam(3, $viewpoint, PDO::PARAM_STR);
        $getFacultyNomination->execute();

        while ($data = $getFacultyNomination->fetchAll()) {

            if ($counter != 0) {

                echo "," . json_encode($data);

            } else {

                echo json_encode($data);

            }

            $counter++;

        }

    }

    public function initOrderFacultyNominationsAwards()
    {

        $selectedYear = $_SESSION["bpayname"];
        if ($ouid == 4) {

            $ouAbbrev = $_SESSION['bpouabbrev'];

        } else {

            $ouAbbrev = $_SESSION['login_ouabbrev'];

        }

        $zeroCheck = 0;
        $null = null;
        $viewPoint = array("Service", "Research", "Teaching", "Other");

        for ($i = 0; $i < count($viewPoint); $i++) {

            $getCurrentOrder = $this->connection->prepare("SELECT * FROM `AC_FacultyNominations` WHERE AWARD_TYPE = ?
             AND OU_ABBREV = ? AND OUTCOMES_AY = ? AND ID_SORT != ? OR ID_SORT != ? ORDER BY ID_SORT ASC;");
            $getCurrentOrder->bindParam(1, $viewPoint[$i], PDO::PARAM_STR);
            $getCurrentOrder->bindParam(2, $ouAbbrev, PDO::PARAM_STR);
            $getCurrentOrder->bindParam(3, $selectedYear, PDO::PARAM_STR);
            $getCurrentOrder->bindParam(4, $zeroCheck, PDO::PARAM_INT);
            $getCurrentOrder->bindParam(5, $null, PDO::PARAM_STR);
            $getCurrentOrder->execute();
            $rowsGetCurrentOrder = $getCurrentOrder->rowCount();
            //$rowsGetCurrentOrder++;

            $getNewOrder = $this->connection->prepare("SELECT * FROM `AC_FacultyNominations` WHERE AWARD_TYPE = ? AND
             OU_ABBREV = ? AND OUTCOMES_AY = ? AND ID_SORT = ? OR ID_SORT = ?;");
            $getNewOrder->bindParam(1, $viewPoint[$i], PDO::PARAM_STR);
            $getNewOrder->bindParam(2, $ouAbbrev, PDO::PARAM_STR);
            $getNewOrder->bindParam(3, $selectedYear, PDO::PARAM_STR);
            $getNewOrder->bindParam(4, $zeroCheck, PDO::PARAM_INT);
            $getNewOrder->bindParam(5, $null, PDO::PARAM_STR);
            $getNewOrder->execute();

            while ($data = $getNewOrder->fetch()) {

                if ($data["ID_SORT"] == 0 || $data["ID_SORT"] == NULL || $data["ID_SORT"] == "") {

                    $rowsGetCurrentOrder++;

                    $updateItem = $this->connection->prepare("UPDATE `AC_FacultyNominations` SET ID_SORT = ? 
WHERE ID_FACULTY_NOMINATIONS = ?");
                    $updateItem->bindParam(1, $rowsGetCurrentOrder, PDO::PARAM_INT);
                    $updateItem->bindParam(2, $data["ID_FACULTY_NOMINATIONS"], PDO::PARAM_INT);
                    //$updateItem->bindParam(3, $viewPoint[$i], PDO::PARAM_STR);
                    $updateItem->execute();

                }

            }

        }

    }

    public function saveFacultyNominationsAwardsOrder($itemData, $indexes)
    {

        $selectedYear = $_SESSION["bpayname"];

        if ($ouid == 4) {

            $ouAbbrev = $_SESSION['bpouabbrev'];

        } else {

            $ouAbbrev = $_SESSION['login_ouabbrev'];

        }

        for ($i = 0; count($itemData) > $i; $i++) {

            $counter = $i + 1;

            $updateList = $this->connection->prepare("UPDATE `AC_FacultyNominations` SET ID_SORT = ? 
WHERE ID_FACULTY_NOMINATIONS = ?");
            $updateList->bindParam(1, $counter, PDO::PARAM_INT);
            $updateList->bindParam(2, $itemData[$i][0], PDO::PARAM_INT);
            $updateList->execute();

        }

    }

    // Update of Nominatoins type Nominatin_details page tackle new SORT_ID here.
    public function setUpdateAwardTypeOrder($award_id,$newAwardType){

        if ($ouid == 4) {
            $ouAbbrev = $_SESSION['bpouabbrev'];

        } else {
            $ouAbbrev = $_SESSION['login_ouabbrev'];
        }

        $selectedYear = $_SESSION["bpayname"];

        $updateList = $this->connection->prepare("SELECT * FROM `AC_FacultyNominations` WHERE AWARD_TYPE = 
        :newAwardType  AND OUTCOMES_AY = :selectedYear AND OU_ABBREV = :ouabbrev ;");
        $updateList->bindParam(':newAwardType', $newAwardType, 2);
        $updateList->bindParam(':selectedYear', $selectedYear, 2);
        $updateList->bindParam(':ouabbrev', $ouAbbrev, 2);
        $updateList->execute();
        $newCount = $updateList->rowCount();

        $updateList = $this->connection->prepare("UPDATE `AC_FacultyNominations` SET ID_SORT = ? 
WHERE ID_FACULTY_NOMINATIONS = ?;");
        $updateList->bindParam(1, $newCount, PDO::PARAM_INT);
        $updateList->bindParam(2, $award_id, PDO::PARAM_INT);
        $updateList->execute();


    }



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

    /*
     * User Management Add user Functions
     */
    public function getPermittedUsers($ouid)
    {


        if ($ouid == 'PROVOST') {

            $filter = "";

        } else {
            $filter = "WHERE OU_ABBREV = :ouid";
        }

        $counter = 0;

        $getUnitGoals = $this->connection->prepare("SELECT ID_STATUS,CONCAT(FNAME,space(1) ,LNAME) AS USERFULLNAME,
NETWORK_USERNAME, USER_ROLE  FROM `PermittedUsers` A INNER JOIN Hierarchy B ON B.ID_HIERARCHY = A.USER_OU_MEMBERSHIP
INNER JOIN UserRoles C ON C.ID_USER_ROLE = A.SYS_USER_ROLE $filter ORDER BY ID_STATUS ASC");
        if($ouid != 4) {
            $getUnitGoals->bindParam(':ouid', $ouid, PDO::PARAM_STR);
        }
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

    // Only available at Prod / Test Server as this requires LDAP
    public function searchUserInfo($user)
    {
//        include('/var/www/html/academicblueprint/Resources/Includes/ldap/ADLDAPV2.php');

//        $pClassUser = new LDAP();
//
//        //call via username
//        if ($pArrayOfFields = $pClassUser->GetAttr($user, array("department","cn","sn","givenName")))
//        {
//            echo json_encode($pArrayOfFields);
//        }
//
//        // call via vipid
//        if ($pArrayOfFields = $pClassUser->GetUsernameByVIPID($user, array("department","cn","sn","givenName")))
//        {
//            echo json_encode($pArrayOfFields);
//        }

    }

}
