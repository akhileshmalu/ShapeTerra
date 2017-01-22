<?php

  error_reporting(1);
  @ini_set('display_errors', 1);
  session_start();
  ob_start();

  if(isset($_POST['functionNum']))
    $function = $_POST['functionNum'];

  if (isset($_GET['functionNum']))
    $function = $_GET['functionNum'];

  if (!empty($function)){

    $Data = new Data;

  }

  switch ($function) {
    case 1:
      $Data->getBluePrintsContent();
      break;
    default:
      break;
  }

  Class Data{

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

    public function getBluePrintsContent(){

      $getBluePrintsContent = $this->connection->prepare("SELECT * FROM `BpContents`");
      $getBluePrintsContent->execute();
      $rowsGetBluePrintsContent = $getBluePrintsContent->rowCount();

      if ($rowsGetBluePrintsContent > 0){

        $counter = 0;

        while($data = $getBluePrintsContent->fetch()){

          if ($counter != 0){

            echo ",".json_encode($data);

          }else{

            echo json_encode($data);

          }

          $counter++;

        }

      }else{

        echo "<h6>No data was found!</h6>";

      }

    }

  }

?>
