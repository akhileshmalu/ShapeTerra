<?php
  error_reporting(1);
  @ini_set('display_errors', 1);
  session_start();
  ob_start();
  require_once ("../Resources/Includes/connect.php");

  if(isset($_POST['functionNum']))
    $function = $_POST['functionNum'];

  if (isset($_GET['functionNum']))
    $function = $_GET['functionNum'];

  switch ($function) {
    case 1:
      displayDataTable();
      break;
    default:
      break;
  }

  Class VisualData{

    private $conection;

    function __construct(){
      $this->connection = $this->connection();
    }

    private function connection(){

      try{

        $connection = new PDO(sprintf('mysql:host=%s;dbname=%s', "localhost", "TESTDB"), "root", "");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;

      }catch(PDOException $e){

        return $e->getMessage();
        exit();

      }

    }

    public function getAcademicYears($mysqli){

      $getAcademicYears = $this->connection->prepare("SELECT * FROM `AcademicYears`");
      $getAcademicYears->execute();
      $rowsGetAcademicYears = $getAcademicYears->rowCount();

      if ($rowsGetAcademicYears > 0){

        echo "
          <div class='dropdown'>
            <button class='btn btn-default dropdown-toggle' type='button' id='academic-year' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
              Select By Year
              <span class='caret'></span>
            </button>
            <ul class='dropdown-menu' aria-labelledby='academic-year'>
        ";

        while ($data = $getAcademicYears->fetch()){

          $yearDescription = $data["ACAD_YEAR_DESC"];
          echo "<li><a href=''>$yearDescription</a></li>";

        }

        echo "
          </ul>
        </div>
        ";

      }else{

        echo "No Academic Years";

      }


    }
    
  }
?>
